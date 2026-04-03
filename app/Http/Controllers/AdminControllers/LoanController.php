<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LoanAgent;
use App\Models\LoanType;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;
use Exception;

class LoanController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $query = LoanAgent::where('trash', 0);

            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where(function ($inner) use ($request) {
                    $inner->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile', 'like', '%' . $request->search . '%')
                        ->orWhere('bank_name', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('loan_type'), function ($q) use ($request) {
                return $q->where('loan_types', 'like', '%' . $request->loan_type . '%');
            });

            $query->when($request->filled('district'), function ($q) use ($request) {
                return $q->where('district', 'like', '%' . $request->district . '%');
            });

            $agents = $query->latest()->paginate(10);
            return view('admin.manage_loan', compact('agents'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load loan agents list.']);
        }
    }

    public function create()
    {
        try {
            $loan_master_types = LoanType::where('trash', 0)->where('status', 1)->get();
            $states = State::where('trash', 0)->orderBy('state_name', 'asc')->get();
            return view('admin.add_loan', compact('loan_master_types', 'states'));
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Could not load the page.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/'
            ],
            'bank_name' => 'required|string|max:255',
            'bank_type' => 'required',
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'loan_types' => 'required',
            'office_address' => 'required',
            'aadhar' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['aadhar', 'profile_photo', '_token']);
            $data['created_by'] = Auth::id();
            $data['status'] = 1;
            $data['trash'] = 0;

            $uploadPath = public_path('uploads/loans');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            if ($request->hasFile('aadhar')) {
                $file = $request->file('aadhar');
                $name = time() . '_aadhar.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $name);
                $data['aadhar'] = $name;
            }

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $name = time() . '_photo.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $name);
                $data['profile_photo'] = $name;
            }

            LoanAgent::create($data);
            DB::commit();

            return redirect()->route('manageLoan')->with('success', 'Loan Agent added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $agent = LoanAgent::findOrFail($id);
            $loan_master_types = LoanType::where('trash', 0)->where('status', 1)->get();
            return view('admin.edit_loan', compact('agent', 'loan_master_types'));
        } catch (Exception $e) {
            return redirect()->route('manageLoan')->withErrors(['error' => 'Agent not found.']);
        }
    }

    public function update(Request $request, $id)
    {
        $agent = LoanAgent::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_type' => 'required',
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'loan_types' => 'required',
            'office_address' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['aadhar', 'profile_photo', '_token', '_method']);
            $uploadPath = public_path('uploads/loans');

            if ($request->hasFile('aadhar')) {
                if ($agent->aadhar && File::exists($uploadPath . '/' . $agent->aadhar)) {
                    File::delete($uploadPath . '/' . $agent->aadhar);
                }
                $file = $request->file('aadhar');
                $name = time() . '_aadhar.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $name);
                $data['aadhar'] = $name;
            }

            if ($request->hasFile('profile_photo')) {
                if ($agent->profile_photo && File::exists($uploadPath . '/' . $agent->profile_photo)) {
                    File::delete($uploadPath . '/' . $agent->profile_photo);
                }
                $file = $request->file('profile_photo');
                $name = time() . '_photo.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $name);
                $data['profile_photo'] = $name;
            }

            $agent->update($data);
            DB::commit();

            return redirect()->route('manageLoan')->with('success', 'Loan Agent updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $agent = LoanAgent::findOrFail($id);
            $agent->update(['trash' => 1]);
            return redirect()->route('manageLoan')->with('success', 'Agent moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function restore($id)
    {
        try {
            $agent = LoanAgent::findOrFail($id);
            $agent->update(['trash' => 0]);
            return redirect()->route('manageLoan')->with('success', 'Agent restored successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Restore failed.']);
        }
    }

    public function exportLoans(Request $request)
    {
        try {
            $fileName = 'loans_export_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $query = LoanAgent::where('trash', 0);

            // Dynamic Search Filter (Name, Mobile, or Bank)
            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where(function ($inner) use ($request) {
                    $inner->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile', 'like', '%' . $request->search . '%')
                        ->orWhere('bank_name', 'like', '%' . $request->search . '%');
                });
            });

            // Dynamic Loan Type Filter
            $query->when($request->filled('loan_type'), function ($q) use ($request) {
                return $q->where('loan_types', 'like', '%' . $request->loan_type . '%');
            });

            // Dynamic District Filter
            $query->when($request->filled('district'), function ($q) use ($request) {
                return $q->where('district', 'like', '%' . $request->district . '%');
            });

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');

                // CSV Headers
                fputcsv($file, [
                    'ID',
                    'Full Name',
                    'Mobile',
                    'Bank Name',
                    'Bank Type',
                    'Loan Types',
                    'Constituency',
                    'District',
                    'State',
                    'Status',
                    'Added On'
                ]);

                // Efficiently processing data in chunks
                $query->chunk(500, function ($agents) use ($file) {
                    foreach ($agents as $agent) {
                        fputcsv($file, [
                            $agent->id,
                            $agent->full_name,
                            $agent->mobile,
                            $agent->bank_name,
                            $agent->bank_type,
                            $agent->loan_types,
                            $agent->constituency,
                            $agent->district,
                            $agent->state,
                            $agent->status == 1 ? 'Active' : 'Inactive',
                            $agent->created_at->format('Y-m-d')
                        ]);
                    }
                });
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Export failed: ' . $e->getMessage()]);
        }
    }
}
<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use App\Models\LegalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Exception;
class AdvocateController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $query = Advocate::where('trash', 0);

            $query->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($inner) use ($request) {
                    $inner->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('legal_service'), function ($q) use ($request) {
                return $q->where('legal_services', 'like', '%' . $request->legal_service . '%');
            });

            // Dynamic District Filter
            $query->when($request->filled('district'), function ($q) use ($request) {
                return $q->where('district', 'like', '%' . $request->district . '%');
            });

            $advocates = $query->orderBy('id', 'desc')->paginate(10);
            return view('admin.manage_advocate', compact('advocates'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load advocates list.']);
        }
    }

    public function create()
    {
        try {
            $services = LegalService::where('trash', 0)->get();
            return view('admin.add_advocate', compact('services'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load form data.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'legal_services' => 'required',
            'aadhar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/advocates');
            if (!File::exists($uploadPath))
                File::makeDirectory($uploadPath, 0755, true);

            $data = $request->except(['aadhar', 'profile_photo', '_token']);
            $data['created_by'] = Auth::id();

            if ($request->hasFile('aadhar')) {
                $name = time() . '_aadhar.' . $request->file('aadhar')->getClientOriginalExtension();
                $request->file('aadhar')->move($uploadPath, $name);
                $data['aadhar'] = $name;
            }

            if ($request->hasFile('profile_photo')) {
                $name = time() . '_photo.' . $request->file('profile_photo')->getClientOriginalExtension();
                $request->file('profile_photo')->move($uploadPath, $name);
                $data['profile_photo'] = $name;
            }

            Advocate::create($data);
            DB::commit();
            return redirect()->route('manageAdvocate')->with('success', 'Advocate Added!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        try {
            $advocate = Advocate::findOrFail($id);
            $services = LegalService::where('trash', 0)->get();
            return view('admin.edit_advocate', compact('advocate', 'services'));
        } catch (Exception $e) {
            return redirect()->route('manageAdvocate')->withErrors(['error' => 'Advocate not found.']);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'legal_services' => 'required',
            'aadhar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $advocate = Advocate::findOrFail($id);
            $uploadPath = public_path('uploads/advocates');

            $data = $request->except(['aadhar', 'profile_photo', '_token']);

            // Handle Aadhaar File Update
            if ($request->hasFile('aadhar')) {
                // Delete old file if exists
                if ($advocate->aadhar && File::exists($uploadPath . '/' . $advocate->aadhar)) {
                    File::delete($uploadPath . '/' . $advocate->aadhar);
                }
                $name = time() . '_aadhar.' . $request->file('aadhar')->getClientOriginalExtension();
                $request->file('aadhar')->move($uploadPath, $name);
                $data['aadhar'] = $name;
            }

            // Handle Profile Photo Update
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($advocate->profile_photo && File::exists($uploadPath . '/' . $advocate->profile_photo)) {
                    File::delete($uploadPath . '/' . $advocate->profile_photo);
                }
                $name = time() . '_photo.' . $request->file('profile_photo')->getClientOriginalExtension();
                $request->file('profile_photo')->move($uploadPath, $name);
                $data['profile_photo'] = $name;
            }

            $advocate->update($data);
            DB::commit();

            return redirect()->route('manageAdvocate')->with('success', 'Advocate Updated Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }



    public function delete($id)
    {
        try {
            Advocate::findOrFail($id)->update(['trash' => 1]);
            return redirect()->route('manageAdvocate')->with('success', 'Advocate moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function exportAdvocates(Request $request)
    {
        try {
            $fileName = 'advocates_export_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            // Initialize query looking for active records
            $query = Advocate::where('trash', 0);

            // Apply Dynamic Search (Name or Mobile)
            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where(function ($sub) use ($request) {
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('legal_service'), function ($q) use ($request) {
                return $q->where('legal_services', 'like', '%' . $request->legal_service . '%');
            });

            $query->when($request->filled('district'), function ($q) use ($request) {
                return $q->where('district', 'like', '%' . $request->district . '%');
            });

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');

                fputcsv($file, [
                    'ID',
                    'Full Name',
                    'Mobile',
                    'Constituency',
                    'District',
                    'State',
                    'Legal Services',
                    'Office Location',
                    'Status',
                    'Joined Date'
                ]);

                $query->chunk(500, function ($advocates) use ($file) {
                    foreach ($advocates as $adv) {
                        fputcsv($file, [
                            $adv->id,
                            $adv->full_name,
                            $adv->mobile,
                            $adv->constituency ?? 'N/A',
                            $adv->district ?? 'N/A',
                            $adv->state ?? 'N/A',
                            $adv->legal_services,
                            $adv->office_location ?? 'N/A',
                            $adv->status == 1 ? 'Active' : 'Inactive',
                            $adv->created_at->format('Y-m-d')
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
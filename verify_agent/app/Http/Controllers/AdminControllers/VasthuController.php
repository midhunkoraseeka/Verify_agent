<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\VasthuConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\VasthuService;

class VasthuController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $query = VasthuConsultant::where('trash', 0);

            // Dynamic Search (Name or Mobile)
            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where(function ($inner) use ($request) {
                    $inner->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile', 'like', '%' . $request->search . '%');
                });
            });

            // Dynamic Vasthu Service Filter
            $query->when($request->filled('vasthu_service'), function ($q) use ($request) {
                return $q->where('vasthu_services', 'like', '%' . $request->vasthu_service . '%');
            });

            // Dynamic District Filter
            $query->when($request->filled('district'), function ($q) use ($request) {
                return $q->where('district', 'like', '%' . $request->district . '%');
            });

            $consultants = $query->orderBy('id', 'desc')->paginate(10);
            return view('admin.manage_vasthu', compact('consultants'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load consultants list.']);
        }
    }

    public function create()
    {
        try {
            // Fetch full objects instead of just strings
            $services = VasthuService::where('trash', 0)->where('status', 1)->get();
            return view('admin.add_vasthu', compact('services'));
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Could not load form.']);
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
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'vasthu_services' => 'required',
            'office_location' => 'required|string',
            'aadhar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'required|image|max:2048',   
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/vasthu');
            if (!File::exists($uploadPath))
                File::makeDirectory($uploadPath, 0755, true);

            $data = $request->except(['aadhar', 'profile_photo', '_token']);
            $data['created_by'] = Auth::id();
            $data['status'] = 1;
            $data['trash'] = 0;

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

            VasthuConsultant::create($data);
            DB::commit();
            return redirect()->route('manageVasthu')->with('success', 'Consultant added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $consultant = VasthuConsultant::findOrFail($id);
            // Fetch full objects instead of just strings
            $services = VasthuService::where('trash', 0)->where('status', 1)->get();
            return view('admin.edit_vasthu', compact('consultant', 'services'));
        } catch (Exception $e) {
            return redirect()->route('manageVasthu')->withErrors(['error' => 'Consultant not found.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'constituency' => 'required',
            'district' => 'required',
            'state' => 'required',
            'vasthu_services' => 'required',
            'office_location' => 'required',
            'aadhar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $consultant = VasthuConsultant::findOrFail($id);
            $uploadPath = public_path('uploads/vasthu');
            $data = $request->except(['aadhar', 'profile_photo', '_token']);

            if ($request->hasFile('aadhar')) {
                if ($consultant->aadhar && File::exists($uploadPath . '/' . $consultant->aadhar)) {
                    File::delete($uploadPath . '/' . $consultant->aadhar);
                }
                $name = time() . '_aadhar.' . $request->file('aadhar')->getClientOriginalExtension();
                $request->file('aadhar')->move($uploadPath, $name);
                $data['aadhar'] = $name;
            }

            if ($request->hasFile('profile_photo')) {
                if ($consultant->profile_photo && File::exists($uploadPath . '/' . $consultant->profile_photo)) {
                    File::delete($uploadPath . '/' . $consultant->profile_photo);
                }
                $name = time() . '_photo.' . $request->file('profile_photo')->getClientOriginalExtension();
                $request->file('profile_photo')->move($uploadPath, $name);
                $data['profile_photo'] = $name;
            }

            $consultant->update($data);
            DB::commit();
            return redirect()->route('manageVasthu')->with('success', 'Consultant Updated Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            VasthuConsultant::findOrFail($id)->update(['trash' => 1]);
            return redirect()->route('manageVasthu')->with('success', 'Consultant moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function exportVasthu(Request $request)
    {
        try {
            $fileName = 'vasthu_export_' . now()->format('Y-m-d') . '.csv';
            $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate, post-check=0, pre-check=0", "Expires" => "0"];
            $query = VasthuConsultant::where('trash', 0);

            // Synchronized filters for Export
            $query->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) {
                    $sub->where('full_name', 'like', '%' . request('search') . '%')->orWhere('mobile', 'like', '%' . request('search') . '%');
                });
            });
            $query->when($request->filled('vasthu_service'), function ($q) {
                $q->where('vasthu_services', 'like', '%' . request('vasthu_service') . '%');
            });
            $query->when($request->filled('district'), function ($q) {
                $q->where('district', 'like', '%' . request('district') . '%');
            });

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Name', 'Phone', 'Location', 'Constituency', 'Services', 'Status', 'Joined Date']);
                $query->chunk(500, function ($consultants) use ($file) {
                    foreach ($consultants as $consultant) {
                        fputcsv($file, [$consultant->id, $consultant->full_name, $consultant->mobile, $consultant->office_location ?? 'N/A', $consultant->constituency, $consultant->vasthu_services, $consultant->status == 1 ? 'Active' : 'Inactive', $consultant->created_at->format('Y-m-d')]);
                    }
                });
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Export failed.']);
        }
    }
}
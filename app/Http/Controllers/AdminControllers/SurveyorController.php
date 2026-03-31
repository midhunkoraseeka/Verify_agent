<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LandSurveyor;
use App\Models\SurveyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class SurveyorController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $query = LandSurveyor::where('trash', 0);

            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where(function ($sub) use ($request) {
                    $sub->where('full_name', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('survey_service'), function ($q) use ($request) {
                return $q->where('survey_services', 'like', '%' . $request->survey_service . '%');
            });

            $query->when($request->filled('district'), function ($q) use ($request) {
                return $q->where('district', 'like', '%' . $request->district . '%');
            });

            $surveyors = $query->orderBy('id', 'desc')->paginate(10);
            return view('admin.manage_surveyor', compact('surveyors'));
        } catch (Exception $e) {
            // echo $e->getMessage(); exit;
            return back()->withErrors(['error' => 'Could not load surveyors list.']);
        }
    }

    public function create()
    {
        try {
            $services = SurveyService::where('trash', 0)->where('status', 1)->get();
            return view('admin.add_surveyor', compact('services'));
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Could not load create surveyor page.']);
        }
    }

    public function edit($id)
    {
        try {
            $surveyor = LandSurveyor::findOrFail($id);
            $services = SurveyService::where('trash', 0)->where('status', 1)->get();
            return view('admin.edit_surveyor', compact('surveyor', 'services'));
        } catch (Exception $e) {
            return redirect()->route('manageSurveyor')->withErrors(['error' => 'Surveyor not found.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'survey_services' => 'required',
            'office_location' => 'required|string|max:255',
            'aadhar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $surveyor = LandSurveyor::findOrFail($id);
            $uploadPath = public_path('uploads/surveyors');

            $data = $request->except(['aadhar', 'profile_photo', '_token']);
            $data['updated_by'] = Auth::id();

            // Handle Aadhar File Update
            if ($request->hasFile('aadhar')) {
                // Delete old file if it exists
                if ($surveyor->aadhar && File::exists($uploadPath . '/' . $surveyor->aadhar)) {
                    File::delete($uploadPath . '/' . $surveyor->aadhar);
                }

                $file = $request->file('aadhar');
                $filename = time() . '_aadhar_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['aadhar'] = $filename;
            }

            // Handle Profile Photo Update
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if it exists
                if ($surveyor->profile_photo && File::exists($uploadPath . '/' . $surveyor->profile_photo)) {
                    File::delete($uploadPath . '/' . $surveyor->profile_photo);
                }

                $file = $request->file('profile_photo');
                $filename = time() . '_photo_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['profile_photo'] = $filename;
            }

            $surveyor->update($data);
            DB::commit();

            return redirect()->route('manageSurveyor')->with('success', 'Surveyor updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'survey_services' => 'required',
            'office_location' => 'required|string|max:255',
            'aadhar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/surveyors');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $data = $request->except(['aadhar', 'profile_photo', '_token']);
            $data['status'] = 1;
            $data['trash'] = 0;
            $data['created_by'] = Auth::id();

            if ($request->hasFile('aadhar')) {
                $file = $request->file('aadhar');
                $filename = time() . '_aadhar_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['aadhar'] = $filename;
            }

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = time() . '_photo_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['profile_photo'] = $filename;
            }

            LandSurveyor::create($data);
            DB::commit();

            return redirect()->route('manageSurveyor')->with('success', 'Surveyor added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $surveyor = LandSurveyor::findOrFail($id);
            $surveyor->update(['trash' => 1]);
            return redirect()->route('manageSurveyor')->with('success', 'Surveyor moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function exportSurveyors(Request $request)
    {
        try {
            $fileName = 'surveyors_export_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $query = LandSurveyor::where('trash', 0);

            $query->when($request->filled('search'), function ($q) use ($request) {
                return $q->where('full_name', 'like', '%' . $request->search . '%')
                    ->orWhere('mobile', 'like', '%' . $request->search . '%');
            });

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Name', 'Phone', 'Location', 'Constituency', 'Services', 'Status', 'Joined Date']);

                $query->chunk(500, function ($surveyors) use ($file) {
                    foreach ($surveyors as $surveyor) {
                        fputcsv($file, [
                            $surveyor->id,
                            $surveyor->full_name,
                            $surveyor->mobile,
                            $surveyor->office_location ?? 'N/A',
                            $surveyor->constituency,
                            $surveyor->survey_services,
                            $surveyor->status == 1 ? 'Active' : 'Inactive',
                            $surveyor->created_at->format('Y-m-d')
                        ]);
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
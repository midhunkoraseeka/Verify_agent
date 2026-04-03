<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Architecture;
use App\Models\ProjectType;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Exception;

class ArchitectureController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $query = Architecture::where('trash', 0);

            $query->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('project_name', 'like', '%' . $request->search . '%')
                        ->orWhere('architect_name', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('project_type'), function ($q) use ($request) {
                return $q->where('project_type', $request->project_type);
            });

            $query->when($request->filled('project_status'), function ($q) use ($request) {
                return $q->where('project_status', $request->project_status);
            });

            $architectures = $query->orderBy('id', 'desc')->paginate(10);
            return view('admin.manage_architecture', compact('architectures'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load projects: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $project_types = ProjectType::where('trash', 0)->where('status', 1)->get();
            $states = State::where('trash', 0)->orderBy('state_name', 'asc')->get();
            return view('admin.add_architecture', compact('project_types', 'states'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load project types.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name'      => 'required|string|max:255',
            'project_type'      => 'required',
            'other_project_type'=> 'required_if:project_type,other|nullable|string|max:255',
            'architect_name'    => 'required|string',
            'project_status'    => 'required',
            'submission_date'   => 'required|date',
            'plans'             => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Only pick known fillable fields manually to avoid mass-assignment issues
            $data = [
                'project_name'    => $request->project_name,
                'project_type'    => $request->project_type,
                'architect_name'  => $request->architect_name,
                'license_no'      => $request->license_no,
                'project_status'  => $request->project_status,
                'submission_date' => $request->submission_date,
                'approval_date'   => $request->approval_date,
                'project_address' => $request->project_address,
                'city'            => $request->city,
                'state'           => $request->state,
                'pincode'         => $request->pincode,
                'description'     => $request->description,
                'status'          => 1,
                'created_by'      => session('agent_id'),
                'trash'           => 0,
            ];

            // Handle "Other" project type — create a new ProjectType and use its ID
            if ($request->project_type === 'other') {
                $newType = ProjectType::create([
                    'project_type_name' => $request->other_project_type,
                    'status'            => 1,
                    'trash'             => 0,
                    'created_by'        => session('agent_id'),
                ]);
                $data['project_type'] = $newType->id;
            }

            // Handle file upload
            if ($request->hasFile('plans')) {
                $uploadPath = public_path('uploads/architectures');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $filename = time() . '_plan.' . $request->file('plans')->getClientOriginalExtension();
                $request->file('plans')->move($uploadPath, $filename);
                $data['plans'] = $filename;
            }

            Architecture::create($data);
            DB::commit();

            return redirect()->route('manageArchitecture')->with('success', 'Architecture Project saved successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $architecture  = Architecture::findOrFail($id);
            $project_types = ProjectType::where('trash', 0)->get();
            return view('admin.edit_architecture', compact('architecture', 'project_types'));
        } catch (Exception $e) {
            return redirect()->route('manageArchitecture')->withErrors(['error' => 'Project not found.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name'    => 'required|string|max:255',
            'project_type'    => 'required',
            'architect_name'  => 'required|string',
            'project_status'  => 'required',
            'submission_date' => 'required|date',
            'plans'           => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $project = Architecture::findOrFail($id);

            $data = [
                'project_name'    => $request->project_name,
                'project_type'    => $request->project_type,
                'architect_name'  => $request->architect_name,
                'license_no'      => $request->license_no,
                'project_status'  => $request->project_status,
                'submission_date' => $request->submission_date,
                'approval_date'   => $request->approval_date,
                'project_address' => $request->project_address,
                'city'            => $request->city,
                'state'           => $request->state,
                'pincode'         => $request->pincode,
                'description'     => $request->description,
            ];

            if ($request->hasFile('plans')) {
                $uploadPath = public_path('uploads/architectures');
                if ($project->plans && File::exists($uploadPath . '/' . $project->plans)) {
                    File::delete($uploadPath . '/' . $project->plans);
                }
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $name = time() . '_plan.' . $request->file('plans')->getClientOriginalExtension();
                $request->file('plans')->move($uploadPath, $name);
                $data['plans'] = $name;
            }

            $project->update($data);
            DB::commit();

            return redirect()->route('manageArchitecture')->with('success', 'Project Updated Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            Architecture::findOrFail($id)->update(['trash' => 1]);
            return redirect()->route('manageArchitecture')->with('success', 'Project moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function exportArchitectures(Request $request)
    {
        try {
            $fileName = 'architecture_projects_' . now()->format('Y-m-d') . '.csv';
            $headers  = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $query    = Architecture::where('trash', 0);
            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Project Name', 'Type', 'Architect', 'Status', 'Submission', 'Approval', 'City']);
                $query->chunk(500, function ($projects) use ($file) {
                    foreach ($projects as $p) {
                        fputcsv($file, [
                            $p->id,
                            $p->project_name,
                            $p->project_type,
                            $p->architect_name,
                            $p->project_status,
                            $p->submission_date,
                            $p->approval_date ?? 'N/A',
                            $p->city,
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
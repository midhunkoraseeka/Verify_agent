<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Architecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\ProjectType;

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
            return back()->withErrors(['error' => 'Could not load projects.']);
        }
    }


    public function create()
    {
        try {
            $project_types = ProjectType::where('trash', 0)->get();
            return view('admin.add_architecture', compact('project_types'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load project types.']);
        }
    }


    public function store(Request $request)
    {
        // 1. Validation Logic
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_type' => 'required',
            // other_project_type is required ONLY if project_type is 'other'
            'other_project_type' => 'required_if:project_type,other|nullable|string|max:255',
            'architect_name' => 'required|string',
            'project_status' => 'required',
            'submission_date' => 'required|date',
            'plans' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Prepare data for insertion
            $data = $request->except(['plans', 'other_project_type', '_token']);
            $data['created_by'] = Auth::id(); // Track creator

            // 2. Handle Dynamic Master Data Creation
            if ($request->project_type === 'other') {
                // Create the new type in rl_project_types_tbl
                $newType = ProjectType::create([
                    'project_type_name' => $request->other_project_type,
                    'status' => 1,
                    'trash' => 0,
                    'created_by' => Auth::id(),
                ]);

                // Override 'other' with the actual ID from the new master record
                $data['project_type'] = $newType->id;
            }

            // 3. Handle File Uploads
            if ($request->hasFile('plans')) {
                $uploadPath = public_path('uploads/architectures');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                $filename = time() . '_plan.' . $request->file('plans')->getClientOriginalExtension();
                $request->file('plans')->move($uploadPath, $filename);
                $data['plans'] = $filename;
            }

            // 4. Create Architecture Record
            Architecture::create($data);

            DB::commit();

            return redirect()->route('manageArchitecture')
                ->with('success', 'Architecture Project and Project Type successfully saved!');

        } catch (Exception $e) {
            DB::rollBack();
            // Return with input so user doesn't lose their form progress
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        try {
            $architecture = Architecture::findOrFail($id);
            $project_types = ProjectType::where('trash', 0)->get();
            return view('admin.edit_architecture', compact('architecture', 'project_types'));
        } catch (Exception $e) {
            return redirect()->route('manageArchitecture')->withErrors(['error' => 'Project not found.']);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_type' => 'required',
            'architect_name' => 'required|string',
            'project_status' => 'required',
            'submission_date' => 'required|date',
            'plans' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $project = Architecture::findOrFail($id);
            $uploadPath = public_path('uploads/architectures');

            $data = $request->except(['plans', '_token']);

            if ($request->hasFile('plans')) {
                if ($project->plans && File::exists($uploadPath . '/' . $project->plans)) {
                    File::delete($uploadPath . '/' . $project->plans);
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


    public function exportArchitectures(Request $request)
    {
        try {
            $fileName = 'architecture_projects_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $query = Architecture::where('trash', 0);

            $query->when($request->search, function ($q) use ($request) {
                $q->where('project_name', 'like', "%$request->search%")->orWhere('architect_name', 'like', "%$request->search%");
            });

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
                            $p->city
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


    public function delete($id)
    {
        try {
            Architecture::findOrFail($id)->update(['trash' => 1]);
            return redirect()->route('manageArchitecture')->with('success', 'Project moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
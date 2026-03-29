<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Exception;

class ProjectTypeController extends Controller
{
   
    public function manageProjectType()
    {
        try {
            $types = ProjectType::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.projectType', compact('types'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load project types.']);
        }
    }


    public function storeProjectType(Request $request)
    {
        $request->validate([
            'project_type_name' => 'required|string|max:255',
        ]);

        try {
            ProjectType::create([
                'project_type_name' => $request->project_type_name,
                'status'            => 1,
                'trash'             => 0,
                'created_by'        => session('agent_id'), // Track who created the record
            ]);
            return redirect()->route('manageProjectType')->with('success', 'Project Type added successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save project type.']);
        }
    }

    
    public function updateProjectType(Request $request, $id)
    {
        $request->validate([
            'project_type_name' => 'required|string|max:255',
            'status'            => 'required|in:0,1',
        ]);

        try {
            $type = ProjectType::findOrFail($id);
            $type->update([
                'project_type_name' => $request->project_type_name,
                'status'            => $request->status,
                'updated_by'        => session('agent_id'),
            ]);
            return redirect()->route('manageProjectType')->with('success', 'Project Type updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to update project type.']);
        }
    }

  
    public function deleteProjectType($id)
    {
        try {
            $type = ProjectType::findOrFail($id);
            $type->update([
                'trash'      => 1,
                'updated_by' => session('agent_id')
            ]);
            
            return redirect()->route('manageProjectType')->with('success', 'Project Type moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed. Could not delete record.']);
        }
    }
}
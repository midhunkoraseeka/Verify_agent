<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LandType;
use Illuminate\Http\Request;
use Exception;

class LandTypeController extends Controller
{
    public function manageLandType()
    {
        try {
            $types = LandType::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.landType', compact('types'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data.']);
        }
    }

    public function storeLandType(Request $request)
    {
        $request->validate([
            'land_type_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'land_type_name.regex' => 'The land type name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            LandType::create([
                'land_type_name' => $request->land_type_name,
                'status'         => 1,
                'trash'          => 0,
                'created_by'     => session('agent_id'),
            ]);
            return redirect()->route('manageLandType')->with('success', 'Land type added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save.']);
        }
    }

    public function updateLandType(Request $request, $id)
    {
        $request->validate([
            'land_type_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'         => 'required|in:0,1',
        ], [
            'land_type_name.regex' => 'The land type name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $type = LandType::findOrFail($id);
            $type->update([
                'land_type_name' => $request->land_type_name,
                'status'         => $request->status,
                'updated_by'     => session('agent_id'),
            ]);
            return redirect()->route('manageLandType')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteLandType($id)
    {
        try {
            LandType::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageLandType')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
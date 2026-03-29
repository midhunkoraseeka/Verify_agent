<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;
use Exception;

class FloorController extends Controller
{
    public function manageFloor()
    {
        try {
            $floors = Floor::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.floor', compact('floors'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load floor data.']);
        }
    }

    public function storeFloor(Request $request)
    {
        $request->validate([
            'floor_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-\+]+$/',
        ], [
            'floor_name.regex' => 'The floor name must contain letters. Numbers, hyphens, plus signs and spaces are optional.'
        ]);

        try {
            Floor::create([
                'floor_name' => $request->floor_name,
                'status'     => 1,
                'trash'      => 0,
                'created_by' => session('agent_id'),
            ]);
            return redirect()->route('manageFloor')->with('success', 'Floor added successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save floor.']);
        }
    }

    public function updateFloor(Request $request, $id)
    {
        $request->validate([
            'floor_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-\+]+$/',
            'status'     => 'required|in:0,1',
        ], [
            'floor_name.regex' => 'Should Contain atleast letters.'
        ]);

        try {
            $floor = Floor::findOrFail($id);
            $floor->update([
                'floor_name' => $request->floor_name,
                'status'     => $request->status,
                'updated_by' => session('agent_id'),
            ]);
            return redirect()->route('manageFloor')->with('success', 'Floor updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteFloor($id)
    {
        try {
            Floor::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageFloor')->with('success', 'Floor moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\RoadSize;
use Illuminate\Http\Request;
use Exception;

class RoadSizeController extends Controller
{
    public function manageRoadSize()
    {
        try {
            $roads = RoadSize::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.roadSize', compact('roads'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data.']);
        }
    }

    public function storeRoadSize(Request $request)
    {
        $request->validate([
            'road_size_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'road_size_name.regex' => 'The road size name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            RoadSize::create([
                'road_size_name' => $request->road_size_name,
                'status'         => 1,
                'trash'          => 0,
                'created_by'     => session('agent_id'),
            ]);
            return redirect()->route('manageRoadSize')->with('success', 'Road Size added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save.']);
        }
    }

    public function updateRoadSize(Request $request, $id)
    {
        $request->validate([
            'road_size_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'         => 'required|in:0,1',
        ], [
            'road_size_name.regex' => 'The road size name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $road = RoadSize::findOrFail($id);
            $road->update([
                'road_size_name' => $request->road_size_name,
                'status'         => $request->status,
                'updated_by'     => session('agent_id'),
            ]);
            return redirect()->route('manageRoadSize')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteRoadSize($id)
    {
        try {
            RoadSize::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageRoadSize')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
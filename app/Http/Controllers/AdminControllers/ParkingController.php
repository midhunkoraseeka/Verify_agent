<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use Illuminate\Http\Request;
use Exception;

class ParkingController extends Controller
{
    public function manageParking()
    {
        try {
            $parkings = Parking::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.parking', compact('parkings'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data.']);
        }
    }

    public function storeParking(Request $request)
    {
        $request->validate([
            'parking_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'parking_name.regex' => 'The parking name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            Parking::create([
                'parking_name' => $request->parking_name,
                'status'       => 1,
                'trash'        => 0,
                'created_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageParking')->with('success', 'Parking option added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save.']);
        }
    }

    public function updateParking(Request $request, $id)
    {
        $request->validate([
            'parking_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'       => 'required|in:0,1',
        ], [
            'parking_name.regex' => 'The parking name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $parking = Parking::findOrFail($id);
            $parking->update([
                'parking_name' => $request->parking_name,
                'status'       => $request->status,
                'updated_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageParking')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteParking($id)
    {
        try {
            Parking::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageParking')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Facing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class FacingController extends Controller
{
    public function manageFacing()
    {
        try {
            $facings = Facing::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.facing', compact('facings'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data: ' . $e->getMessage()]);
        }
    }

    public function createFacing()
    {
        try {
            return view('admin.createFacing');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not open create form.']);
        }
    }

    public function storeFacing(Request $request)
    {
        $request->validate([
            'facing_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'facing_name.regex' => 'The facing name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            Facing::create([
                'facing_name' => $request->facing_name,
                'status'      => 1,
                'trash'       => 0,
                'created_by'  => session('agent_id'),
            ]);

            return redirect()->route('manageFacing')->with('success', 'Facing added successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save: ' . $e->getMessage()]);
        }
    }

    public function editFacing($id)
    {
        try {
            $facing = Facing::findOrFail($id);
            return view('admin.editFacing', compact('facing'));
        } catch (Exception $e) {
            return redirect()->route('admin.manageFacing')->withErrors(['error' => 'Facing not found.']);
        }
    }

    public function updateFacing(Request $request, $id)
    {
        $request->validate([
            'facing_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'      => 'required|in:0,1',
        ], [
            'facing_name.regex' => 'The facing name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $facing = Facing::findOrFail($id);
            $facing->update([
                'facing_name' => $request->facing_name,
                'status'      => $request->status,
                'updated_by'  => session('agent_id'),
            ]);

            return redirect()->route('manageFacing')->with('success', 'Facing updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function deleteFacing($id)
    {
        try {
            $facing = Facing::findOrFail($id);
            $facing->update([
                'trash'      => 1,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('manageFacing')->with('success', 'Facing moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }
}
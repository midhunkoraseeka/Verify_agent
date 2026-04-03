<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;

class StateController extends Controller
{
    public function manageState()
    {
        try {
            // Only fetch states that are not trashed
            $states = State::where('trash', 0)->orderBy('state_name', 'asc')->get();
            return view('admin.manage_state', compact('states'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load states list.']);
        }
    }

    public function storeState(Request $request)
    {
        // Check uniqueness only among active (non-trashed) records
        $request->validate([
            'state_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('states')->where(function ($query) {
                    return $query->where('trash', 0);
                })
            ]
        ], [
            'state_name.unique' => 'This state name already exists in your active records.'
        ]);

        try {
            State::create([
                'state_name' => trim($request->state_name),
                'status'     => 1,
                'trash'      => 0
            ]);

            return back()->with('success', 'State added successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function updateState(Request $request, $id)
    {
        // Ignore the current ID during uniqueness check, but only for active records
        $request->validate([
            'state_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('states')->ignore($id)->where(function ($query) {
                    return $query->where('trash', 0);
                })
            ],
            'status' => 'required|in:0,1'
        ], [
            'state_name.unique' => 'This state name is already in use.'
        ]);

        try {
            $state = State::findOrFail($id);
            $state->update([
                'state_name' => trim($request->state_name),
                'status'     => $request->status
            ]);

            return back()->with('success', 'State updated successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function deleteState($id)
    {
        try {
            $state = State::findOrFail($id);
            $state->update(['trash' => 1]);
            
            return back()->with('success', 'State moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\BhkType;
use Illuminate\Http\Request;
use Exception;

class BhkTypeController extends Controller
{
    public function manageBhkType()
    {
        try {
            $bhks = BhkType::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.bhkType', compact('bhks'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load BHK data.']);
        }
    }

    public function storeBhkType(Request $request)
    {
        $request->validate([
            'bhk_type_name' => 'required|string|max:255',
        ]);

        try {
            BhkType::create([
                'bhk_type_name' => $request->bhk_type_name,
                'status' => 1,
                'trash' => 0,
                'created_by' => session('agent_id'),
            ]);
            return redirect()->route('manageBhkType')->with('success', 'BHK Type added successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save BHK type.']);
        }
    }

    public function updateBhkType(Request $request, $id)
    {
        $request->validate([
            'bhk_type_name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        try {
            $bhk = BhkType::findOrFail($id);
            $bhk->update([
                'bhk_type_name' => $request->bhk_type_name,
                'status' => $request->status,
                'updated_by' => session('agent_id'),
            ]);

            return redirect()->route('manageBhkType')->with('success', 'BHK Type updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function deleteBhkType($id)
    {
        try {
            BhkType::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageBhkType')->with('success', 'BHK Type moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
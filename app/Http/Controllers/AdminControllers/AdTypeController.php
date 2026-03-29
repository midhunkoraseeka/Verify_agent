<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\AdType;
use Illuminate\Http\Request;
use Exception;

class AdTypeController extends Controller
{
    public function manageAdType()
    {
        try {
            $adTypes = AdType::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.adType', compact('adTypes'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data.']);
        }
    }

    public function storeAdType(Request $request)
    {
        $request->validate([
            'ad_type_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'ad_type_name.regex' => 'The ad type name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            AdType::create([
                'ad_type_name' => $request->ad_type_name,
                'status'       => 1,
                'trash'        => 0,
                'created_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageAdType')->with('success', 'Ad type added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save.']);
        }
    }

    public function updateAdType(Request $request, $id)
    {
        $request->validate([
            'ad_type_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'       => 'required|in:0,1',
        ], [
            'ad_type_name.regex' => 'The ad type name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $type = AdType::findOrFail($id);
            $type->update([
                'ad_type_name' => $request->ad_type_name,
                'status'       => $request->status,
                'updated_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageAdType')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteAdType($id)
    {
        try {
            AdType::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageAdType')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
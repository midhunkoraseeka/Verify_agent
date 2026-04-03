<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistrictController extends Controller
{
    public function manageDistrict() {
        // Eager load state and filter trashed records
        $districts = District::with('state')->where('trash', 0)->orderBy('district_name', 'asc')->get();
        $states = State::where('trash', 0)->orderBy('state_name', 'asc')->get();
        return view('admin.manage_district', compact('districts', 'states'));
    }

    public function storeDistrict(Request $request) {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'district_name' => 'required|unique:districts,district_name,NULL,id,state_id,' . $request->state_id
        ]);

        District::create([
            'state_id'      => $request->state_id,
            'district_name' => $request->district_name,
            'status'        => 1,
            'trash'         => 0,
            'created_by'    => Auth::id(), // Capture logged-in user
            'updated_by'    => Auth::id()
        ]);

        return back()->with('success', 'District added successfully');
    }

    public function updateDistrict(Request $request, $id) {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'district_name' => 'required|unique:districts,district_name,' . $id . ',id,state_id,' . $request->state_id
        ]);

        $district = District::findOrFail($id);
        $district->update([
            'state_id'      => $request->state_id,
            'district_name' => $request->district_name,
            'updated_by'    => Auth::id()
        ]);

        return back()->with('success', 'District updated successfully');
    }

    public function deleteDistrict($id) {
        District::findOrFail($id)->update(['trash' => 1]);
        return back()->with('success', 'District moved to trash');
    }

    public function getDistricts($state_id) {
        $districts = District::where('state_id', $state_id)
                             ->where('trash', 0)
                             ->orderBy('district_name', 'asc')
                             ->get();
        return response()->json($districts);
    }
}
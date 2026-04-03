<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Constituency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ConstituencyController extends Controller
{
    public function manage() {
        $constituencies = Constituency::where('trash', 0)->orderBy('constituency_name', 'asc')->get();
        return view('admin.manage_constituency', compact('constituencies'));
    }

    public function store(Request $request) {
        $request->validate([
            'constituency_name' => [
                'required', 'string', 'max:255',
                Rule::unique('constituencies')->where(fn ($q) => $q->where('trash', 0))
            ]
        ], ['constituency_name.unique' => 'This constituency already exists.']);

        Constituency::create([
            'constituency_name' => trim($request->constituency_name),
            'status' => 1,
            'trash' => 0,
            'created_by' => Auth::id()
        ]); 
        return back()->with('success', 'Constituency added successfully');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'constituency_name' => [
                'required', 'string', 'max:255',
                Rule::unique('constituencies')->ignore($id)->where(fn ($q) => $q->where('trash', 0))
            ]
        ]);

        Constituency::findOrFail($id)->update([
            'constituency_name' => trim($request->constituency_name),
            'status' => $request->status,
            'updated_by' => Auth::id()
        ]);
        return back()->with('success', 'Constituency updated successfully');
    }

    public function delete($id) {
        Constituency::findOrFail($id)->update(['trash' => 1]);
        return back()->with('success', 'Constituency moved to trash');
    }
}

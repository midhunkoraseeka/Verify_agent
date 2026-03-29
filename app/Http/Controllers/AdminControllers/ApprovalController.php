<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use Illuminate\Http\Request;
use Exception;

class ApprovalController extends Controller
{
    public function manageApproval()
    {
        try {
            $approvals = Approval::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.approval', compact('approvals'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data.']);
        }
    }

    public function storeApproval(Request $request)
    {
        $request->validate([
            'approval_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'approval_name.regex' => 'The approval name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            Approval::create([
                'approval_name' => $request->approval_name,
                'status'        => 1,
                'trash'         => 0,
                'created_by'    => session('agent_id'),
            ]);
            return redirect()->route('manageApproval')->with('success', 'Approval type added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save.']);
        }
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'approval_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'        => 'required|in:0,1',
        ], [
            'approval_name.regex' => 'The approval name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $approval = Approval::findOrFail($id);
            $approval->update([
                'approval_name' => $request->approval_name,
                'status'        => $request->status,
                'updated_by'    => session('agent_id'),
            ]);
            return redirect()->route('manageApproval')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteApproval($id)
    {
        try {
            Approval::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageApproval')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
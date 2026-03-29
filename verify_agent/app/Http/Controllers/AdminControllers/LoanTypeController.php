<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LoanType; // Ensure you have created this Model
use Illuminate\Http\Request;
use Exception;

class LoanTypeController extends Controller
{
    
    public function manageLoanType()
    {
        try {
            // Fetch all non-trashed loan types
            $loan_types = LoanType::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.loanTypes', compact('loan_types'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load loan types data.']);
        }
    }

    public function storeLoanType(Request $request)
    {
        $request->validate([
            'loan_type_name' => 'required|string|max:255|unique:rl_loan_types_tbl,loan_type_name',
        ]);

        try {
            LoanType::create([
                'loan_type_name' => $request->loan_type_name,
                'status'         => 1,
                'trash'          => 0,
                'created_by'     => session('agent_id'), 
            ]);
            return redirect()->route('manageLoanType')->with('success', 'Loan type added successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save loan type.']);
        }
    }

    /**
     * Update an existing Loan Type
     */
    public function updateLoanType(Request $request, $id)
    {
        $request->validate([
            'loan_type_name' => 'required|string|max:255|unique:rl_loan_types_tbl,loan_type_name,' . $id,
            'status'         => 'required|in:0,1',
        ]);

        try {
            $loanType = LoanType::findOrFail($id);
            $loanType->update([
                'loan_type_name' => $request->loan_type_name,
                'status'         => $request->status,
                'updated_by'     => session('agent_id'),
            ]);
            return redirect()->route('manageLoanType')->with('success', 'Loan Type updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    /**
     * Soft delete a Loan Type (Move to Trash)
     */
    public function deleteLoanType($id)
    {
        try {
            // Soft delete by updating trash flag
            LoanType::findOrFail($id)->update([
                'trash'      => 1, 
                'updated_by' => session('agent_id')
            ]);
            return redirect()->route('manageLoanType')->with('success', 'Loan Type moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Exception;

class LoanTypeController extends Controller
{
    public function manageLoanType()
    {
        try {
            $loan_types = LoanType::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.loanTypes', compact('loan_types'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load loan types data.']);
        }
    }

    public function storeLoanType(Request $request)
    {
        $request->validate([
            'loan_type_name' => 'required|string|max:255|unique:rl_loan_types_tbl,loan_type_name|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'loan_type_name.regex' => 'The loan type name must contain letters. Numbers, hyphens and spaces are optional.'
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

    public function updateLoanType(Request $request, $id)
    {
        $request->validate([
            'loan_type_name' => 'required|string|max:255|unique:rl_loan_types_tbl,loan_type_name,' . $id . '|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'         => 'required|in:0,1',
        ], [
            'loan_type_name.regex' => 'The loan type name must contain letters. Numbers, hyphens and spaces are optional.'
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

    public function deleteLoanType($id)
    {
        try {
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
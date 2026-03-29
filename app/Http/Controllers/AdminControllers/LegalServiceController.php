<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\LegalService;
use Illuminate\Http\Request;
use Exception;

class LegalServiceController extends Controller
{
    public function manageLegalService()
    {
        try {
            $services = LegalService::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.legalServices', compact('services'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load legal services data.']);
        }
    }

    public function storeLegalService(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'service_name.regex' => 'The service name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            LegalService::create([
                'service_name' => $request->service_name,
                'status'       => 1,
                'trash'        => 0,
                'created_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageLegalService')->with('success', 'Legal service added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save service.']);
        }
    }

    public function updateLegalService(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'       => 'required|in:0,1',
        ], [
            'service_name.regex' => 'The service name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $service = LegalService::findOrFail($id);
            $service->update([
                'service_name' => $request->service_name,
                'status'       => $request->status,
                'updated_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageLegalService')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteLegalService($id)
    {
        try {
            LegalService::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageLegalService')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
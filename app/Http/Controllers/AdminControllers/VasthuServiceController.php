<?php
namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\VasthuService;
use Illuminate\Http\Request;
use Exception;

class VasthuServiceController extends Controller
{
    public function manageVasthuService()
    {
        try {
            $services = VasthuService::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.vasthuService', compact('services'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load data.']);
        }
    }

    public function storeVasthuService(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:rl_vasthu_services_tbl,service_name|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
        ], [
            'service_name.regex' => 'The service name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            VasthuService::create([
                'service_name' => $request->service_name,
                'status'       => 1,
                'trash'        => 0,
                'created_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageVasthuService')->with('success', 'Service added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to save.']);
        }
    }

    public function updateVasthuService(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:rl_vasthu_services_tbl,service_name,' . $id . '|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'status'       => 'required|in:0,1',
        ], [
            'service_name.regex' => 'The service name must contain letters. Numbers, hyphens and spaces are optional.'
        ]);

        try {
            $service = VasthuService::findOrFail($id);
            $service->update([
                'service_name' => $request->service_name,
                'status'       => $request->status,
                'updated_by'   => session('agent_id'),
            ]);
            return redirect()->route('manageVasthuService')->with('success', 'Updated!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteVasthuService($id)
    {
        try {
            VasthuService::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageVasthuService')->with('success', 'Trashed!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
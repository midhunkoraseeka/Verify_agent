<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\SurveyService;
use Illuminate\Http\Request;
use Exception;

class SurveyServiceController extends Controller
{
    public function manageSurveyService()
    {
        try {
            $services = SurveyService::where('trash', 0)->orderBy('id', 'desc')->get();
            return view('admin.surveyServices', compact('services'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load survey services.']);
        }
    }

    public function storeSurveyService(Request $request)
    {
        $request->validate(['service_name' => 'required|string|max:255|unique:rl_survey_services_tbl,service_name']);

        try {
            SurveyService::create([
                'service_name' => $request->service_name,
                'status' => 1,
                'trash' => 0,
                'created_by' => session('agent_id'),
            ]);
            return redirect()->route('manageSurveyService')->with('success', 'Survey service added!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Save failed.']);
        }
    }

    public function updateSurveyService(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255|unique:rl_survey_services_tbl,service_name,' . $id,
            'status' => 'required|in:0,1',
        ]);

        try {
            $service = SurveyService::findOrFail($id);
            $service->update([
                'service_name' => $request->service_name,
                'status' => $request->status,
                'updated_by' => session('agent_id'),
            ]);
            return redirect()->route('manageSurveyService')->with('success', 'Updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed.']);
        }
    }

    public function deleteSurveyService($id)
    {
        try {
            SurveyService::findOrFail($id)->update(['trash' => 1, 'updated_by' => session('agent_id')]);
            return redirect()->route('manageSurveyService')->with('success', 'Moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }
}
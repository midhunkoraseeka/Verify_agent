<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\AdType;

class AdController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $query = Advertisement::where('trash', 0);
            $adTypes = AdType::where('trash', 0)->get();

            $query->when($request->search, function ($q) use ($request) {
                return $q->where(function ($sub) use ($request) {
                    $sub->where('ad_title', 'like', '%' . $request->search . '%')
                        ->orWhere('ad_text', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            });

            $query->when($request->filled('type'), function ($q) use ($request) {
                return $q->where('ad_type', $request->type);
            });

            $ads = $query->with('adType')->orderBy('id', 'desc')->paginate(10);


            return view('admin.manage_ad', compact('ads', 'adTypes'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load advertisements list.']);
        }
    }

    public function create()
    {
        try{
            $adTypes = AdType::where('trash', 0)->get();
            return view('admin.add_ad', compact('adTypes'));
        }
        catch(Exception $e){
                return back()->withErrors(['error' => 'Could not load form.']);
            }
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'ad_title'     => 'required|string|max:100',
            'ad_type'      => 'required',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'ad_image'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'ad_title.required'   => 'The ad title field is required.',
            'ad_type.required'    => 'The ad type field is required.',
            'start_date.required' => 'The start date field is required.',
            'end_date.required'   => 'The end date field is required.',
            'ad_image.required'   => 'The ad image field is required.',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/ads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $data = [
                'ad_title'     => $request->ad_title,
                'ad_type'      => $request->ad_type,
                'start_date'   => date('Y-m-d', strtotime($request->start_date)),
                'end_date'     => date('Y-m-d', strtotime($request->end_date)),
                'external_url' => $request->external_url,
                'ad_text'      => $request->ad_text,
                'status'       => 1,
                'trash'        => 0,
                'created_by'   => session('agent_id'),
            ];

            if ($request->hasFile('ad_image')) {
                $file = $request->file('ad_image');
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['ad_image'] = $filename;
            }

            if ($request->hasFile('ad_video')) {
                $file = $request->file('ad_video');
                $filename = time() . '_vid_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['ad_video'] = $filename;
            }

            Advertisement::create($data);
            DB::commit();

            return redirect()->route('manageAds')->with('success', 'Advertisement added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $ad = Advertisement::findOrFail($id);
            $adTypes = AdType::where('trash', 0)->get();
            return view('admin.edit_ad', compact('ad', 'adTypes'));
        } catch (Exception $e) {
            return redirect()->route('manageAds')->withErrors(['error' => 'Ad not found.']);
        }
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        $request->validate([
            'ad_title'     => 'required|string|max:100',
            'ad_type'      => 'required',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/ads');
            $data = [
                'ad_title'     => $request->ad_title,
                'ad_type'      => $request->ad_type,
                'start_date'   => date('Y-m-d', strtotime($request->start_date)),
                'end_date'     => date('Y-m-d', strtotime($request->end_date)),
                'external_url' => $request->external_url,
                'ad_text'      => $request->ad_text,
            ];

            if ($request->hasFile('ad_image')) {
                if ($ad->ad_image && File::exists($uploadPath . '/' . $ad->ad_image)) { 
                    File::delete($uploadPath . '/' . $ad->ad_image); 
                }
                $file = $request->file('ad_image');
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['ad_image'] = $filename;
            }

            if ($request->hasFile('ad_video')) {
                if ($ad->ad_video && File::exists($uploadPath . '/' . $ad->ad_video)) { 
                    File::delete($uploadPath . '/' . $ad->ad_video); 
                }
                $file = $request->file('ad_video');
                $filename = time() . '_vid_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['ad_video'] = $filename;
            }

            $ad->update($data);
            DB::commit();

            return redirect()->route('manageAds')->with('success', 'Ad updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $ad = Advertisement::findOrFail($id);
            $ad->update(['trash' => 1]);
            return redirect()->route('manageAds')->with('success', 'Ad moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function exportAds(Request $request)
    {
        try {
            $fileName = 'ads_export_' . now()->format('Y-m-d') . '.csv';
            $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName"];
            $query = Advertisement::where('trash', 0);
            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Title', 'Type', 'Start Date', 'End Date', 'Status']);
                $query->chunk(500, function ($ads) use ($file) {
                    foreach ($ads as $ad) {
                        fputcsv($file, [$ad->id, $ad->ad_title, $ad->ad_type, $ad->start_date, $ad->end_date, $ad->status]);
                    }
                });
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Export failed.']);
        }
    }
}
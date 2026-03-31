<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Exception;
use App\Models\Facing;
use App\Models\BhkType;
use App\Models\Floor;
use App\Models\Parking;
use App\Models\RoadSize;
use App\Models\Approval;
use App\Models\LandType;
class PropertyController extends Controller
{
    public function manageProperty(Request $request)
    {
        try {

            $query = Property::with(['bhk', 'floor', 'facingDirection', 'parking'])
                ->where('trash', 0);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('location', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('type')) {
                $query->where('property_type', $request->type);
            }

            if ($request->filled('approved_by')) {
                $query->where('approved_by', $request->approved_by);
            }

            if ($request->filled('price_type')) {
                $query->where('price_type', $request->price_type);
            }

            $properties = $query
                ->orderByDesc('id')
                ->paginate(10);

            return view('admin.manage_property', compact('properties'));

        } catch (\Exception $e) {

            return back()->withErrors([
                'error' => 'Could not load properties.'
            ]);
        }
    }

    public function createProperty()
    {
        try {
            $facing = Facing::where('trash', 0)->get();
            $bhks = BhkType::where('trash', 0)->get();
            $floors = Floor::where('trash', 0)->get();
            $parkings = Parking::where('trash', 0)->get();
            $roads = RoadSize::where('trash', 0)->get();
            $approvals = Approval::where('trash', 0)->get();
            $land_types = LandType::where('trash', 0)->get();
            return view('admin.add_property', compact('facing', 'bhks', 'floors', 'parkings', 'roads', 'approvals', 'land_types'));
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Could not load form.']);
        }


    }

    public function editProperty($id)
    {
        try {
            $property = Property::findOrFail($id);

            // Fetch all master data for dropdowns
            $facing = Facing::where('trash', 0)->get();
            $bhks = BhkType::where('trash', 0)->get();
            $floors = Floor::where('trash', 0)->get();
            $parkings = Parking::where('trash', 0)->get();
            $roads = RoadSize::where('trash', 0)->get();
            $approvals = Approval::where('trash', 0)->get();
            $land_types = LandType::where('trash', 0)->get();

            return view('admin.edit_property', compact(
                'property',
                'facing',
                'bhks',
                'floors',
                'parkings',
                'roads',
                'approvals',
                'land_types'
            ));
        } catch (Exception $e) {
            return redirect()->route('manageProperty')->withErrors(['error' => 'Property not found.']);
        }
    }

    public function storeProperty(Request $request)
    {
        $type = $request->input('property_type');

        // These rules apply regardless of section
        $rules = [
            'property_type' => 'required|in:1,2,3,4,5,6',
            'location' => 'required|string|max:255',
            'price' => 'required|string|max:255', // Kept as string to allow comma formatting if needed
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'property_video' => 'nullable|mimes:mp4,mov,ogg|max:20000',
        ];

        // Optional: Section specific required fields
        if (in_array($type, ['1', '2', '3'])) { // Residential
            $rules['bhk_type'] = 'required';
            $rules['facing'] = 'required';
        } elseif ($type == '4') { // Plot
            $rules['approved_by'] = 'required';
        }

        $request->validate($rules);

        try {
            $uploadPath = public_path('uploads/properties');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Image Handling
            $imageNames = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);
                    $imageNames[] = $filename;
                }
            }

            // Video Handling
            $videoName = null;
            if ($request->hasFile('property_video')) {
                $video = $request->file('property_video');
                $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $video->move($uploadPath, $videoName);
            }

            // Data Preparation
            $data = $request->except(['_token', 'images', 'property_video']);

            // Clean up empty strings to nulls
            $data = array_map(fn($val) => ($val === '' ? null : $val), $data);

            $data['images'] = $imageNames;
            $data['video'] = $videoName;
            $data['status'] = 1;
            $data['trash'] = 0;
            $data['created_by'] = session('agent_id');

            Property::create($data);

            return redirect()->route('manageProperty')->with('success', 'Property listed successfully!');

        } catch (Exception $e) {
    dd($e->getMessage()); // This will stop the app and show the exact SQL error
}
    }

    public function updateProperty(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $type = $request->input('property_type');

        $rules = [
            'property_type' => 'required|in:1,2,3,4,5,6',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'property_video' => 'nullable|mimes:mp4,mov,ogg|max:20000',
        ];

        if ($type === '4') {
            $rules['approved_by'] = 'required';
            $rules['size'] = 'required';
        }
        if (in_array($type, ['5', '6'])) {
            $rules['land_type'] = 'required';
        }

        $request->validate($rules);

        try {
            $uploadPath = public_path('uploads/properties');
            $data = $request->except(['_token', 'images', 'property_video']);
            $data = array_map(fn($val) => ($val === '' ? null : $val), $data);

            // Handle New Images
            if ($request->hasFile('images')) {
                $imageNames = is_array($property->images) ? $property->images : [];
                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);
                    $imageNames[] = $filename;
                }
                $data['images'] = $imageNames;
            }

            // Handle Video Update
            if ($request->hasFile('property_video')) {
                // Delete old video if exists
                if ($property->video && File::exists($uploadPath . '/' . $property->video)) {
                    File::delete($uploadPath . '/' . $property->video);
                }
                $video = $request->file('property_video');
                $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $video->move($uploadPath, $videoName);
                $data['video'] = $videoName;
            }

            $data['updated_by'] = session('agent_id');
            $property->update($data);

            return redirect()->route('manageProperty')->with('success', 'Property updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update Error: ' . $e->getMessage()]);
        }
    }

    public function deleteProperty($id)
    {
        try {
            $property = Property::findOrFail($id);
            $property->update(['trash' => 1]);
            return redirect()->route('manageProperty')
                ->with('success', 'Property moved to trash.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function exportProperties(Request $request)
    {
        try {
            $fileName = 'properties_export_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            // Start query ignoring trashed items
            $query = Property::where('trash', 0);

            // APPLY DYNAMIC FILTERS (Must match manageProperty logic)
            $query->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('location', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('type'), function ($q) use ($request) {
                $q->where('property_type', $request->type);
            });

            $query->when($request->filled('approved_by'), function ($q) use ($request) {
                $q->where('approved_by', $request->approved_by);
            });

            $query->when($request->filled('price_type'), function ($q) use ($request) {
                $q->where('price_type', $request->price_type);
            });

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');

                // Add CSV Headers
                fputcsv($file, [
                    'Property ID',
                    'Type',
                    'Location',
                    'Price',
                    'Price Type',
                    'Approved By',
                    'Status',
                    'Added Date'
                ]);

                // Stream records in chunks to prevent memory issues
                $query->chunk(500, function ($properties) use ($file) {
                    foreach ($properties as $property) {
                        fputcsv($file, [
                            $property->id,
                            ucfirst($property->property_type),
                            $property->location,
                            $property->price,
                            $property->price_type ?? 'N/A',
                            $property->approved_by ?? 'N/A',
                            $property->status == 1 ? 'Active' : 'Inactive',
                            $property->created_at->format('Y-m-d')
                        ]);
                    }
                });

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Export failed: ' . $e->getMessage()]);
        }
    }
}
<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AgentController extends Controller
{
    public function index()
    {
        try {
            $agents = Agent::where('trash', 0)->where('usertype', 1)->get();
            return view('admin.index', compact('agents'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load dashboard.']);
        }
    }

    public function manage(Request $request)
    {
        try {
            $query = Agent::where('trash', 0)->where('usertype', 1);

            $query->when($request->search, function ($q) use ($request) {
                return $q->where(function ($sub) use ($request) {
                    $sub->where('agent_name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile_number', 'like', '%' . $request->search . '%');
                });
            });

            $query->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            });

            $query->when($request->filled('priority'), function ($q) use ($request) {
                return $q->where('priority', $request->priority);
            });

            $query->when($request->constituency, function ($q) use ($request) {
                return $q->where('constituency', 'like', '%' . $request->constituency . '%');
            });

            $agents = $query->orderBy('id', 'desc')->paginate(10);

            return view('admin.manage_agent', compact('agents'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Could not load agents list.']);
        }
    }

    public function create()
    {
        try {
            $lastAgent = Agent::orderBy('id', 'desc')->first();
            $nextId = $lastAgent ? 'VA-' . str_pad($lastAgent->id + 1, 3, '0', STR_PAD_LEFT) : 'VA-001';
            return view('admin.add_agent', compact('nextId'));
        } catch (Exception $e) {
            return redirect()->route('manageAgent')->withErrors(['error' => 'Error generating ID.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'priority' => 'required|in:0,1,2',
            'full_name' => 'required|string|min:3|max:100',
            'father_name' => 'required|string|max:100',
            'dob' => 'required|date_format:d-m-Y',
            'location' => 'required|string|max:255',
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'mobile_number' => [
                'required',
                'regex:/^[6-9]\d{9}$/'
            ],
            'address' => 'required|string|max:500',
            'rera_number' => 'required|string|max:50',
            'username' => 'required|string',
            'password' => 'nullable|min:6',
            'aadhaar_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'dob.date_format' => 'Date of birth must be in DD-MM-YYYY format.',
            'mobile_number.unique' => 'This mobile number is already registered.',
            'username.unique' => 'This username is already taken.',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/agents');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $data = [
                'usertype' => 1,
                'agent_id' => $request->display_agent_id,
                'priority' => $request->priority,
                'agent_name' => $request->full_name,
                'father_name' => $request->father_name,
                'date_of_birth' => date('Y-m-d', strtotime($request->dob)),
                'location' => $request->location,
                'constituency' => $request->constituency,
                'district' => $request->district,
                'mobile_number' => $request->mobile_number,
                'address' => $request->address,
                'rera_no' => $request->rera_number,
                'username' => $request->username,
                'password' => $request->filled('password') ? Hash::make($request->password) : Hash::make('123456'),
                'status' => 1,
                'trash' => 0,
                'created_by' => session('agent_id'),
                'updated_by' => session('agent_id'),
            ];

            if ($request->hasFile('aadhaar_file')) {
                $file = $request->file('aadhaar_file');
                $filename = time() . '_aadhaar_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['agent_aadhar'] = $filename;
            }

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $filename = time() . '_photo_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['agent_photo'] = $filename;
            }

            Agent::create($data);
            DB::commit();

            return redirect()->route('manageAgent')->with('success', 'Agent added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $agent = Agent::findOrFail($id);
            return view('admin.edit_agents', compact('agent'));
        } catch (Exception $e) {
            return redirect()->route('manageAgent')->withErrors(['error' => 'Agent not found.']);
        }
    }

    public function update(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $request->validate([
            'priority' => 'required|in:0,1,2',
            'full_name' => 'required|string|min:3|max:100',
            'father_name' => 'required|string|max:100',
            'dob' => 'required|date_format:d-m-Y',
            'location' => 'required|string|max:255',
            'constituency' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'mobile_number' => [
                'required',
                'regex:/^[6-9]\d{9}$/'
            ],
            'address' => 'required|string|max:500',
            'rera_number' => 'required|string|max:50',
            'username' => 'required|string|max:50',
            'password' => 'nullable|min:6',
            'aadhaar_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $uploadPath = public_path('uploads/agents');

            $data = [
                'priority' => $request->priority,
                'agent_name' => $request->full_name,
                'father_name' => $request->father_name,
                'date_of_birth' => date('Y-m-d', strtotime($request->dob)),
                'location' => $request->location,
                'constituency' => $request->constituency,
                'district' => $request->district,
                'mobile_number' => $request->mobile_number,
                'address' => $request->address,
                'rera_no' => $request->rera_number,
                'username' => $request->username,
                'updated_by' => session('agent_id'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('aadhaar_file')) {
                if ($agent->agent_aadhar && File::exists($uploadPath . '/' . $agent->agent_aadhar)) {
                    File::delete($uploadPath . '/' . $agent->agent_aadhar);
                }
                $file = $request->file('aadhaar_file');
                $filename = time() . '_aadhaar_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['agent_aadhar'] = $filename;
            }

            if ($request->hasFile('profile_photo')) {
                if ($agent->agent_photo && File::exists($uploadPath . '/' . $agent->agent_photo)) {
                    File::delete($uploadPath . '/' . $agent->agent_photo);
                }
                $file = $request->file('profile_photo');
                $filename = time() . '_photo_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $data['agent_photo'] = $filename;
            }

            $agent->update($data);
            DB::commit();

            return redirect()->route('manageAgent')->with('success', 'Agent updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Update Error: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->update(['trash' => 1]);
            return redirect()->route('manageAgent')->with('success', 'Agent moved to trash!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Action failed.']);
        }
    }

    public function restore($id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->update(['trash' => 0]);
            return redirect()->route('manageAgent')->with('success', 'Agent restored successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Restore failed.']);
        }
    }

    public function exportAgents(Request $request)
    {
        try {
            $fileName = 'agents_export_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $query = Agent::where('trash', 0)->where('usertype', 1);

            $query->when($request->search, function ($q) use ($request) {
                return $q->where(function ($sub) use ($request) {
                    $sub->where('agent_name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('mobile_number', 'like', '%' . $request->search . '%');
                });
            });

            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Agent ID', 'Name', 'Username', 'Phone', 'Location', 'Constituency', 'Priority', 'Status', 'Joined Date']);

                $query->chunk(500, function ($agents) use ($file) {
                    foreach ($agents as $agent) {
                        fputcsv($file, [
                            $agent->agent_id,
                            $agent->agent_name,
                            $agent->username,
                            $agent->mobile_number,
                            $agent->location,
                            $agent->constituency,
                            $agent->priority == 0 ? 'High' : ($agent->priority == 1 ? 'Medium' : 'Low'),
                            $agent->status == 1 ? 'Active' : 'Inactive',
                            $agent->created_at->format('Y-m-d')
                        ]);
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
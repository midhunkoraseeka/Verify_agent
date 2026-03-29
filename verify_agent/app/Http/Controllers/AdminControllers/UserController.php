<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserController extends Controller
{
    public function manageUser(Request $request)
    {
        $query = UserManagement::where('trash', 0);

        $query->when($request->search, function ($q) use ($request) {
            return $q->where(function ($sub) use ($request) {
                $sub->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('mobile_number', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        });

        $query->when($request->city, function ($q) use ($request) {
            return $q->where('city', 'like', "%{$request->city}%");
        });

        $query->when($request->constituency, function ($q) use ($request) {
            return $q->where('constituency', 'like', "%{$request->constituency}%");
        });

        $users = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.manage_user', compact('users'));
    }

    public function createUser()
    {
        try {
            return view('admin.add_user');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Could not load create user page.']);
        }
    }

    public function editUser($id)
    {
        try {
            $user = UserManagement::findOrFail($id);
            return view('admin.edit_user', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('manageUser')->withErrors(['error' => 'User not found.']);
        }
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => [
                'required',
                'regex:/^[6-9]\d{9}$/'
            ],
            'username' => 'required|max:50',
            'password' => 'required|min:6',
        ]);

        try {
            $data = $request->only(['first_name', 'last_name', 'email', 'city', 'constituency', 'pincode', 'address', 'username']);
            $data['mobile_number'] = $request->phone;
            $data['password'] = Hash::make($request->password);
            $data['status'] = 1;
            $data['trash'] = 0;
            $data['created_by'] = Auth::id();

            UserManagement::create($data);
            return redirect()->route('manageUser')->with('success', 'User added successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()]);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => [
                'required',
                'regex:/^[6-9]\d{9}$/'
            ],
            'username' => 'required|max:50',
            'password' => 'nullable|min:6',
        ]);

        try {
            $user = UserManagement::findOrFail($id);

            $data = $request->only(['first_name', 'last_name', 'email', 'city', 'constituency', 'pincode', 'address', 'username']);
            $data['mobile_number'] = $request->phone;
            $data['updated_by'] = Auth::id();

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            return redirect()->route('manageUser')->with('success', 'User updated successfully!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = UserManagement::findOrFail($id);
            $user->update(['trash' => 1]);
            return redirect()->route('manageUser')->with('success', 'User moved to trash successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Delete failed: User not found.']);
        }
    }

    public function exportUsers(Request $request)
    {
        try {
            $fileName = 'users_export_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $query = UserManagement::where('trash', 0);
            $callback = function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'City', 'Constituency', 'Status', 'Created Date']);

                $query->chunk(500, function ($users) use ($file) {
                    foreach ($users as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->first_name,
                            $user->last_name,
                            $user->email,
                            $user->mobile_number,
                            $user->city,
                            $user->constituency,
                            $user->status == 1 ? 'Active' : 'Inactive',
                            $user->created_at->format('Y-m-d')
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
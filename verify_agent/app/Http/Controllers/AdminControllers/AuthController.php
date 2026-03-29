<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showLogin()
    {
        try {
            return view('admin.login');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'An error occurred while loading the login page. Please try again later.']);
        }

    }
    public function login(Request $request)
{
    try {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'password' => 'required',
        ]);
        
        $agent = Agent::where('mobile_number', $request->mobile_number)
            ->where('trash', 0)
            ->where('status', 1)
            ->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {
            return back()->withErrors([
                'mobile_number' => 'Invalid mobile number or password.',
            ])->withInput($request->only('mobile_number'));
        }

        // 🟢 THE FIX: Log the user into Laravel's Auth system
        Auth::login($agent);

        // Store extra details in session if needed for your custom logic
        session([
            'agent_id' => $agent->id,
            'agent_name' => $agent->agent_name,
            'usertype' => $agent->usertype, 
        ]);

        return redirect()->route('dashboard')->with('success', 'Welcome back!');              

    } catch (\Throwable $th) {
        return back()->withErrors(['error' => 'Login failed.']);
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
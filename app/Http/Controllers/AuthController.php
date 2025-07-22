<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return response()->json(['message' => 'Login successful', 'redirect' => '/dashboard']);
            } else {
                return response()->json(['message' => 'Login successful', 'redirect' => '/headmaster/dashboard']);
            }
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ], 422);
    }

    public function headmasterRegister(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'headteacher_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Create school first
        $school = School::create([
            'name' => $request->school_name,
            'headteacher_name' => $request->headteacher_name,
            'headteacher_email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create headmaster user
        $user = User::create([
            'name' => $request->headteacher_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'headmaster',
            'school_id' => $school->id,
        ]);

        return response()->json([
            'message' => 'Registration successful! You can now log in.',
            'school' => $school,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return a simple response indicating successful logout
        return response()->json(['message' => 'Logout successful']);
    }

    public function createAdmin()
    {
        // Create default admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@jarvis.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        return 'Admin user created successfully!';
    }

    // Headmaster API endpoints - Only for headmasters
    public function headmasterUser()
    {
        $user = Auth::user();
        if (!$user || !$user->isHeadmaster()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user);
    }

    public function headmasterSchool()
    {
        $user = Auth::user();
        if (!$user || !$user->isHeadmaster()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user->school);
    }

    public function headmasterVisits()
    {
        $user = Auth::user();
        if (!$user || !$user->isHeadmaster()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Only return visits for the headmaster's school
        $visits = $user->school->visits()->orderBy('visit_date', 'desc')->get();
        return response()->json($visits);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;
use App\Models\Visit;

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
                return response()->json(['message' => 'Login successful', 'redirect' => '/headteacher/dashboard']);
            }
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ], 422);
    }

    public function headteacherRegister(Request $request)
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
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create headteacher user
        $user = User::create([
            'name' => $request->headteacher_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'headteacher',
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
        // User::create([
        //     'name' => 'Super Admin',
        //     'email' => 'admin@jarvis.com',
        //     'password' => Hash::make('password123'),
        //     'role' => 'admin',
        // ]);

        return 'Admin user created successfully!';
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return response()->json($user);
    }

    // Headteacher API endpoints - Only for headteachers
    public function headteacherUser()
    {
        $user = Auth::user();
        if (!$user || !$user->isHeadteacher()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user);
    }

    public function headteacherSchool()
    {
        $user = Auth::user();
        if (!$user || !$user->isHeadteacher()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user->school);
    }

    public function headteacherVisits()
    {
        $user = Auth::user();
        if (!$user || !$user->isHeadteacher()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $visits = Visit::where('school_id', $user->school_id)
            ->with(['school'])
            ->latest()
            ->get();

        return response()->json($visits);
    }

    /**
     * Get all headteachers for admin dashboard
     */
    public function getAllHeadteachers()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $headteachers = User::where('role', 'headteacher')
            ->with([
                'school' => function ($query) {
                    $query->withCount('visits');
                }
            ])
            ->orderBy('name')
            ->get();

        return response()->json($headteachers);
    }

    /**
     * Delete a headteacher
     */
    public function deleteHeadteacher(User $headteacher)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($headteacher->role !== 'headteacher') {
            return response()->json(['message' => 'User is not a headteacher'], 400);
        }

        try {
            // Delete associated school if it exists
            if ($headteacher->school) {
                // Delete all visits for this school
                Visit::where('school_id', $headteacher->school->id)->delete();
                // Delete the school
                $headteacher->school->delete();
            }

            // Delete the headteacher user
            $headteacher->delete();

            return response()->json(['message' => 'Headteacher deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete headteacher'], 500);
        }
    }
}

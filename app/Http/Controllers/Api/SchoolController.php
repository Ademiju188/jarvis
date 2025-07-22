<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
    public function index(): JsonResponse
    {
        $schools = School::with(['visits', 'headteacher'])->get();
        // Add visits_count and headteacher info to each school
        $schools = $schools->map(function ($school) {
            $school->visits_count = $school->visits->count();
            $school->headteacher = $school->headteacher;
            return $school;
        });
        return response()->json($schools);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headteacher_name' => 'required|string|max:255',
            'headteacher_email' => 'required|email|unique:users',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'password' => 'required|string|confirmed',
        ]);

        $school = School::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
        ]);

        $user = User::create([
            'name' => $validated['headteacher_name'],
            'email' => $validated['headteacher_email'],
            'password' => bcrypt($validated['password']),
            'role' => 'headteacher',
            'school_id' => $school->id,
        ]);
        return response()->json($school, 201);
    }

    public function show(School $school): JsonResponse
    {
        $school->load(['visits', 'headteacher']);
        $school->visits_count = $school->visits->count();
        $school->headteacher = $school->headteacher;
        return response()->json($school);
    }

    public function update(Request $request, School $school): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'headteacher_name' => 'sometimes|required|string|max:255',
            'headteacher_email' => 'sometimes|required|email|unique:users,email,' . $school->headteacher->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'password' => 'sometimes|required|string|confirmed',
        ]);

        // Update school fields
        $school->update([
            'name' => $validated['name'] ?? $school->name,
            'address' => $validated['address'] ?? $school->address,
            'phone' => $validated['phone'] ?? $school->phone,
        ]);

        // Update headteacher information if provided
        if (isset($validated['headteacher_name']) || isset($validated['headteacher_email']) || isset($validated['password'])) {
            $headteacher = $school->headteacher;
            if ($headteacher) {
                $headteacherData = [];

                if (isset($validated['headteacher_name'])) {
                    $headteacherData['name'] = $validated['headteacher_name'];
                }

                if (isset($validated['headteacher_email'])) {
                    $headteacherData['email'] = $validated['headteacher_email'];
                }

                if (isset($validated['password'])) {
                    $headteacherData['password'] = bcrypt($validated['password']);
                }

                $headteacher->update($headteacherData);
            }
        }

        // Reload the school with relationships
        $school->load(['visits', 'headteacher']);
        $school->visits_count = $school->visits->count();

        return response()->json($school);
    }

    public function destroy(School $school): JsonResponse
    {
        $school->delete();
        return response()->json(null, 204);
    }

    public function getByToken($token): JsonResponse
    {
        $school = School::where('access_token', $token)->with(['visits', 'headteacher'])->first();

        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }
        $school->visits_count = $school->visits->count();
        $school->headteacher = $school->headteacher;
        return response()->json($school);
    }

    public function deleteInvite(School $school): JsonResponse
    {
        $school->access_token = null;
        $school->save();
        return response()->json($school);
    }
}

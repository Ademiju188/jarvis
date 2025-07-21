<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SchoolController extends Controller
{
    public function index(): JsonResponse
    {
        $schools = School::with('visits')->get();
        return response()->json($schools);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'headteacher_name' => 'required|string|max:255',
            'headteacher_email' => 'required|email|unique:schools',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $school = School::create($validated);
        return response()->json($school, 201);
    }

    public function show(School $school): JsonResponse
    {
        $school->load('visits');
        return response()->json($school);
    }

    public function update(Request $request, School $school): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'headteacher_name' => 'sometimes|required|string|max:255',
            'headteacher_email' => 'sometimes|required|email|unique:schools,headteacher_email,' . $school->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $school->update($validated);
        return response()->json($school);
    }

    public function destroy(School $school): JsonResponse
    {
        $school->delete();
        return response()->json(null, 204);
    }

    public function getByToken($token): JsonResponse
    {
        $school = School::where('access_token', $token)->with('visits')->first();

        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        return response()->json($school);
    }
}

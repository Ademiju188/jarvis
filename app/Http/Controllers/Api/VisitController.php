<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class VisitController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user->isHeadteacher()) {
            // Headteacher only sees visits for their school
            $visits = $user->school->visits()->with('school')->orderBy('visit_date', 'desc')->get();
        } else {
            // Admin sees all visits
            $visits = Visit::with('school')->orderBy('visit_date', 'desc')->get();
        }

        return response()->json($visits);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'visit_date' => 'required|date',
            'consultant_name' => 'required|string|max:255',
            'context' => 'required|string',
            'activities_undertaken' => 'required|string',
            'progress' => 'nullable|string',
            'key_findings' => 'required|string',
            'recommendations' => 'required|string',
            'next_visit_date' => 'nullable|date',
        ]);

        $visit = Visit::create($validated);
        $visit->load('school');

        return response()->json($visit, 201);
    }

    public function show(Visit $visit): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Check if headteacher is trying to access a visit from another school
        if ($user->isHeadteacher() && $visit->school_id !== $user->school_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $visit->load('school');
        return response()->json($visit);
    }

    public function update(Request $request, Visit $visit): JsonResponse
    {
        $validated = $request->validate([
            'school_id' => 'sometimes|required|exists:schools,id',
            'visit_date' => 'sometimes|required|date',
            'consultant_name' => 'sometimes|required|string|max:255',
            'context' => 'sometimes|required|string',
            'activities_undertaken' => 'sometimes|required|string',
            'progress' => 'nullable|string',
            'key_findings' => 'sometimes|required|string',
            'recommendations' => 'sometimes|required|string',
            'next_visit_date' => 'nullable|date',
            'status' => 'sometimes|in:draft,pending_review,approved,finalized',
            'headteacher_feedback' => 'nullable|string',
        ]);

        $visit->update($validated);
        $visit->load('school');

        return response()->json($visit);
    }

    public function updateStatus(Request $request, Visit $visit): JsonResponse
    {
        $user = Auth::user();

        // Check if headteacher is trying to access a visit from another school
        if ($user && $user->role === 'headteacher' && $visit->school_id !== $user->school_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,pending_review,approved,finalized',
        ]);

        $visit->update($validated);
        $visit->load('school');

        return response()->json($visit);
    }

    public function destroy(Visit $visit): JsonResponse
    {
        $visit->delete();
        return response()->json(null, 204);
    }

    public function getByShareToken($token): JsonResponse
    {
        $visit = Visit::where('share_token', $token)->with('school')->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        return response()->json($visit);
    }

    public function generatePdf(Visit $visit)
    {
        $visit->load('school');

        $pdf = Pdf::loadView('pdfs.visit-record', compact('visit'));

        return $pdf->download('visit-record-' . $visit->id . '.pdf');
    }

    public function submitFeedback(Request $request, $token): JsonResponse
    {
        $visit = Visit::where('share_token', $token)->first();

        if (!$visit) {
            return response()->json(['error' => 'Visit not found'], 404);
        }

        $validated = $request->validate([
            'headteacher_feedback' => 'required|string',
        ]);

        $visit->update([
            'headteacher_feedback' => $validated['headteacher_feedback'],
            'status' => 'pending_review'
        ]);

        return response()->json(['message' => 'Feedback submitted successfully']);
    }

    public function submitFeedbackById(Request $request, Visit $visit): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->isHeadteacher() && $visit->school_id !== $user->school_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'headteacher_feedback' => 'required|string',
        ]);
        $visit->update([
            'headteacher_feedback' => $validated['headteacher_feedback'],
            'status' => 'pending_review',
        ]);
        $visit->load('school');
        return response()->json(['message' => 'Feedback submitted successfully', 'visit' => $visit]);
    }
}

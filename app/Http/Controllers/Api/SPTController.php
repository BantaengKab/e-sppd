<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSPTRequest;
use App\Http\Requests\UpdateSPTRequest;
use App\Models\SPT;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SPTController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $spts = SPT::with(['user', 'estimatedCosts', 'approvals', 'sppd'])->get();
        } else {
            $spts = SPT::where('user_id', $user->id)
                ->with(['estimatedCosts', 'approvals', 'sppd'])
                ->get();
        }

        return response()->json($spts);
    }

    public function store(StoreSPTRequest $request): JsonResponse
    {
        $spt = SPT::create(array_merge($request->validated(), [
            'user_id' => $request->user()->id,
            'status' => 'draft'
        ]));

        return response()->json($spt->load(['estimatedCosts', 'approvals']), 201);
    }

    public function show(Request $request, SPT $spt): JsonResponse
    {
        $user = $request->user();

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($spt->load(['user', 'estimatedCosts', 'approvals.approver', 'sppd']));
    }

    public function update(UpdateSPTRequest $request, SPT $spt): JsonResponse
    {
        $user = $request->user();

        // Only allow editing if draft status
        if ($spt->status !== 'draft') {
            return response()->json(['message' => 'Cannot edit submitted SPT'], 403);
        }

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $spt->update($request->validated());

        return response()->json($spt->load(['estimatedCosts', 'approvals']));
    }

    public function destroy(Request $request, SPT $spt): JsonResponse
    {
        $user = $request->user();

        // Only allow deletion if draft status
        if ($spt->status !== 'draft') {
            return response()->json(['message' => 'Cannot delete submitted SPT'], 403);
        }

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $spt->delete();

        return response()->json(['message' => 'SPT deleted successfully']);
    }

    public function submit(Request $request, SPT $spt): JsonResponse
    {
        $user = $request->user();

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($spt->status !== 'draft') {
            return response()->json(['message' => 'SPT is already submitted'], 400);
        }

        if ($spt->estimatedCosts->isEmpty()) {
            return response()->json(['message' => 'Add estimated costs before submitting'], 400);
        }

        $spt->update(['status' => 'submitted']);

        return response()->json(['message' => 'SPT submitted successfully']);
    }
}

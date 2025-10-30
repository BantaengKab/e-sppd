<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardWebController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'total_spts' => SPT::count(),
                'draft_spts' => SPT::where('status', 'draft')->count(),
                'submitted_spts' => SPT::where('status', 'submitted')->count(),
                'approved_spts' => SPT::where('status', 'approved')->count(),
                'rejected_spts' => SPT::where('status', 'rejected')->count(),
                'total_users' => User::count(),
            ];

            $recent_spts = SPT::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

        } else {
            $stats = [
                'total_spts' => SPT::where('user_id', $user->id)->count(),
                'draft_spts' => SPT::where('user_id', $user->id)->where('status', 'draft')->count(),
                'submitted_spts' => SPT::where('user_id', $user->id)->where('status', 'submitted')->count(),
                'approved_spts' => SPT::where('user_id', $user->id)->where('status', 'approved')->count(),
                'rejected_spts' => SPT::where('user_id', $user->id)->where('status', 'rejected')->count(),
            ];

            $recent_spts = SPT::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'recent_spts'));
    }
}

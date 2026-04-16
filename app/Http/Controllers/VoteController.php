<?php

namespace App\Http\Controllers;

use App\Services\VoteService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    //
    protected $voteService;
    public function __construct(VoteService $voteService){
        $this->voteService=$voteService;
    }
    public function toggle(Request $request){
        $validated = $request->validate([
            'report_id' => 'required|exists:reports,id',
        ]);
        $userId = auth()->id();
        if (!$userId) {
            return response()->json([
                'message' => 'non authentifier'
            ], 401);
        }
        $result = $this->voteService->toggleVote($validated['report_id'], $userId);
        return response()->json($result);
    }

    public function countByReport($reportId){
        $count = $this->voteService->countVotesByReport($reportId);
        return response()->json([
            'report_id' => $reportId,
            'votes_count' => $count,
        ]);
    }
}

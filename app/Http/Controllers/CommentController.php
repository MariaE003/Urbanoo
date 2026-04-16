<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService){
        $this->commentService=$commentService;
    }
    public function commentByReport($reportId){
        $comments = $this->commentService->CommentsByReportId($reportId);
        return response()->json($comments);
    }

    public function store(Request $request){
        $validated=$request->validate([
            'content'=>'required|string',
            'report_id'=>'required|exists:reports,id'
        ]);

        if (auth()->user() && auth()->user()->role === "admin"){
            $validated['is_admin']=true;
        }
        $validated['user_id']=auth()->id();
        $comment=$this->commentService->createComment($validated);
        return response()->json([
            'message' => 'comment created',
            'comment' => $comment,
        ],201);
    }
}

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

    
}

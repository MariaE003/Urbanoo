<?php
namespace App\Repositories;

use App\Models\Comment;

class CommentRepository{
    public function getByReportId($report_id){
        $comment=Comment::with(['user','report'])->where('report_id',$report_id)->latest()->get();
        return $comment;
    }
    public function create($data){
        return Comment::create($data);
    }
    
    
}

?>
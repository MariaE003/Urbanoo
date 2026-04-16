<?php
namespace App\Services;
use App\Repositories\CommentRepository;

class CommentService{
    protected $commentRepo;
    public function __construct(CommentRepository $commentRepo){
        $this->commentRepo=$commentRepo;
    }
    public function CommentsByReportId($report_id){
        return $this->commentRepo->getByReportId($report_id);
    }
    public function createComment($data){
        return $this->commentRepo->create($data);
    }
    
}

?>
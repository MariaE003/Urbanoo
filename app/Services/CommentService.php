<?php
namespace App\Services;

use App\Notifications\NewCommentOnReport;
use App\Repositories\CommentRepository;
use App\Repositories\ReportRepository;

class CommentService{
    protected $commentRepo;
    protected $reportRepo;
    public function __construct(CommentRepository $commentRepo, ReportRepository $reportRepo){
        $this->commentRepo=$commentRepo;
        $this->reportRepo = $reportRepo;
    }
    public function CommentsByReportId($report_id){
        return $this->commentRepo->getByReportId($report_id);
    }
    public function createComment($data){
        $comment=$this->commentRepo->create($data);
        $report=$this->reportRepo->findById($data['report_id']);
        //en virifiant si createur du report ce nest pas qui fait le comment
        if ($report && $report->user && $report->user->id !== $data['user_id']) {
            $report->user->notify(new NewCommentOnReport($report,$comment));
        }
        return $comment;
    }
    public function updateComment($id,$data){
        return $this->commentRepo->update($id,$data);
    }
    public function getCommentById($id){
        return $this->commentRepo->findById($id);
    }
    public function deleteComment($id){
        $this->commentRepo->delete($id);
    }
}

?>
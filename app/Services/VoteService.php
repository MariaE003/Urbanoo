<?php
namespace App\Services;

use App\Notifications\NewVoteOnReport;
use App\Repositories\ReportRepository;
use App\Repositories\VoteRepository;

class VoteService{
   protected $voteRepo;
   protected $reportRepo;

   public function __construct(VoteRepository $voteRepo,ReportRepository $reportRepo){
        $this->voteRepo=$voteRepo;
        $this->reportRepo = $reportRepo;
   }
   public function allVotes(){
        return $this->voteRepo->getAll();
   }
   public function createVote($data){
        return $this->voteRepo->create($data);
   }
   public function deleteVote($id){
        return $this->voteRepo->remove($id);
   }
   public function countVotesByReport($reportId){
       return $this->voteRepo->countByReport($reportId);
   }

   public function toggleVote($reportId, $userId){
        $exist = $this->voteRepo->findByReportAndUser($reportId, $userId);
        if ($exist) {
            $this->voteRepo->remove($exist->id);
            return [
                'voted' => false,
                'votes_count' => $this->voteRepo->countByReport($reportId),
            ];
        }
        $vote=$this->voteRepo->create([
            'report_id' => $reportId,
            'user_id' => $userId,
        ]);
        $report=$this->reportRepo->findById($reportId);
        if ($report && $report->user && $report->user->id !== $userId){
            $report->user->notify(new NewVoteOnReport($report));
        }
        
        return [
            'voted' => true,
            'votes_count' => $this->voteRepo->countByReport($reportId),
        ];
    }

}

?>
<?php
namespace App\Services;

use App\Repositories\VoteRepository;

class VoteService{
   protected $voteRepo;

   public function __construct(VoteRepository $voteRepo){
        $this->voteRepo=$voteRepo;
   }
   public function allVotes(){
        return $this->voteRepo->getAll();
   }
   public function createVote($data){
        return $this->voteRepo->create($data);
   }
   public function deleteVote($id){
        $this->voteRepo->remove($id);
   }
   public function countVotesByReport($reportId){
       return $this->voteRepo->countByReport($reportId);
   }

   

}

?>
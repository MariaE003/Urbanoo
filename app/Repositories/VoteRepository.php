<?php
namespace App\Repositories;

use App\Models\Vote;

class VoteRepository{
    public function getAll(){
        return Vote::all();
    }
    public function create($data){
        return Vote::create($data);
    }
    public function remove($id){
        $vote= Vote::find($id);
        if (!$vote) {
            return false;
        }
        return $vote->delete();
    }
    public function findByReportAndUser($reportId, $userId){
        return Vote::where('report_id', $reportId)->where('user_id', $userId)->first();
    }
    public function countByReport($reportId){
        return Vote::where('report_id', $reportId)->count();
    }

}

?>
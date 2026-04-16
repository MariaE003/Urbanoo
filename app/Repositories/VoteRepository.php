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
    

}

?>
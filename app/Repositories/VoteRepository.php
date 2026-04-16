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
    

}

?>
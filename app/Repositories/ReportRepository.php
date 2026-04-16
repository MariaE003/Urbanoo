<?php
namespace App\Repositories;

use App\Models\Report;

class ReportRepository{
    public function getAll(){
        $report=Report::with(['user','category','images'])->latest()->get();
        return $report;
    }
    public function reportCreatedByMe(){
        $user=auth()->id();
        return Report::where('user_id',$user)->get();
    }
    
}

?>
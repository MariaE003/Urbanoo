<?php
namespace App\Repositories;

use App\Models\Report;
use Carbon\Carbon;

class ReportRepository{
    public function getAll(){
        $report=Report::with(['user','category','images'])->latest()->get();
        return $report;
    }
    public function reportCreatedByMe(){
        $user=auth()->id();
        return Report::where('user_id',$user)->get();
    }
    public function findById($id){
        return Report::findOrFail($id);
    }
    public function create($data){
        return Report::create($data);
    }
    public function updateStatus($id,$data){
        $report=Report::find($id);
        $report->update($data);
        return $report;
    }
    public function delete($id){
        $report=Report::find($id);
        return $report->delete();
    }
    public function lastReports(){
        $report=Report::with(['user','category','images'])->where('created_at','>=',Carbon::now()->subDay(3))->latest()->get();
        return $report;
    }
}

?>
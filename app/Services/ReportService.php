<?php
namespace App\Services;

use App\Notifications\ReportStatusChanged;
use App\Repositories\ReportRepository;

class ReportService{
    protected $reportRepo;
    public function __construct(ReportRepository $reportRepo){
        $this->reportRepo=$reportRepo;
    }
    public function allReports(){
        return $this->reportRepo->getAll();
    }
    public function getReportById($id){
        return $this->reportRepo->findById($id);
    }
    public function createReport($data){
        return $this->reportRepo->create($data);
    }
    public function updateReportStatus($id,$data){
        $report = $this->reportRepo->updateStatus($id,$data);
        if ($report &&  $report->user) {
            $report->user->notify(new ReportStatusChanged($report));
        }
        return $report;
    }
    public function deleteReport($id){
        $this->reportRepo->delete($id);
    }
    public function lastReports(){
        return $this->reportRepo->lastReports();
    }
}

?>
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
    
}

?>
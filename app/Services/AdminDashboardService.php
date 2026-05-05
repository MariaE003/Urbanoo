<?php

namespace App\Services;

use App\Repositories\AdminDashboardRepository;

class AdminDashboardService{
    protected $adminDashboardRepo;

    public function __construct(AdminDashboardRepository $adminDashboardRepo){
        $this->adminDashboardRepo = $adminDashboardRepo;
    }
    public function getStats(){
        return $this->adminDashboardRepo->getStatistique();
    }
    public function TopVoteReports(){
        return $this->adminDashboardRepo->TopReportsByVote();
    }
    public function reportsBycat(){
        return $this->adminDashboardRepo->ReportsByCategorie();
    }
    public function citizens(){
        return $this->adminDashboardRepo->citizens();
    }
    public function toggleUserStatus($id){
        return $this->adminDashboardRepo->toggleUserStatus($id);
    }

}


?>

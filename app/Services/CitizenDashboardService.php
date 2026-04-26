<?php

namespace App\Services;

use App\Repositories\CitizenDashboardRepository;

class CitizenDashboardService{
    protected $citizenDashboardRepo;

    public function __construct(CitizenDashboardRepository $citizenDashboardRepo){
        $this->citizenDashboardRepo = $citizenDashboardRepo;
    }

    public function getStats($userId){
        return $this->citizenDashboardRepo->getStats($userId);
    }

    public function getLatestReports($userId){
        return $this->citizenDashboardRepo->getLatestReports($userId);
    }

    public function getAllMyReports($userId){
        return $this->citizenDashboardRepo->getAllMyReports($userId);
    }
}
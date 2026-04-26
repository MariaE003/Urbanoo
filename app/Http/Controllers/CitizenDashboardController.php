<?php

namespace App\Http\Controllers;

use App\Services\CitizenDashboardService;

class CitizenDashboardController extends Controller
{
    protected $citizenDashboardService;

    public function __construct(CitizenDashboardService $citizenDashboardService){
        $this->citizenDashboardService = $citizenDashboardService;
    }

    public function index(){
        if (!auth()->check()) {
            return redirect('/login');
        }
        if (auth()->user()->role !== 'citizen') {
            return redirect('/');
        }
        $userId = auth()->id();

        $stats = $this->citizenDashboardService->getStats($userId);
        $latestReports = $this->citizenDashboardService->getLatestReports($userId);
        $myReports = $this->citizenDashboardService->getAllMyReports($userId);

        return view('citizen.dashboard', compact('stats', 'latestReports', 'myReports'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;

class AdminDashboardController extends Controller
{
    //
    protected $adminDashboardService;
    public function __construct(AdminDashboardService $adminDashboardService){
        $this->adminDashboardService=$adminDashboardService;
    }
    public function statistique(){
        if(!auth()->check() || auth()->user()->role != 'admin'){
            return response()->json([
                'message'=>'non autorise'
            ]);
        }
        $statis=$this->adminDashboardService->getStats();
        $top=$this->adminDashboardService->TopVoteReports();
        $catReports=$this->adminDashboardService->reportsBycat();
        // dd($catReports);
        return view('admin.dashboard',['statis'=>$statis,'top'=>$top,'reportsByCats'=>$catReports]);
    }
    
}

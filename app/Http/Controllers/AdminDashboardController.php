<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

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
        $citizens=$this->adminDashboardService->citizens();
        // dd($catReports);
        return view('admin.dashboard',[
            'statis'=>$statis,
            'top'=>$top,
            'reportsByCats'=>$catReports,
            'citizens'=>$citizens
        ]);
    }

    public function toggleUserStatus($id){
        if(!auth()->check() || auth()->user()->role != 'admin'){
            return response()->json([
                'message'=>'non autorise'
            ], 403);
        }

        $user = $this->adminDashboardService->toggleUserStatus($id);

        return redirect()->route('admin.dashboard')->with(
            'status', $user->is_active ? 'utilisateur active avec succes' : 'utilisateur desactive avec succes'
        );
    }
    
}
<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;

class AdminDashboardRepository
{
    public function getStatistique(){
        return[
            'total_reports'=> Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in_progress')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'total_users' => User::where('role', 'citizen')->count(),
            'total_categories' => Category::count(),
            'last_3_days_reports' => Report::where('created_at', '>=', now()->subDays(3))->count(),
        ];
    }
    public function TopReportsByVote(){
        return $reports=Report::with(['user','category'])->withCount('votes')->orderByDesc('votes_count')->take(5)->get();
    }
    public function ReportsByCategorie(){
        // return Report::selectRow('category_id , count(*) as total')->with('category')->groupBy('category_id')->get();
        return Category::withCount('report')->get();
    }
}
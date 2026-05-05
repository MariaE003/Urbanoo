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
    public function ReportsByCategorie(){//nombre des report par categorie
        return Category::withCount('report')->get();
    }

    public function citizens(){
        return User::where('role', 'citizen')->latest()->get();
    }

    public function toggleUserStatus($id){
        $user = User::where('role', 'citizen')->findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return $user;
    }
}

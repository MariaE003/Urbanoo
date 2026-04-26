<?php

namespace App\Repositories;

use App\Models\Report;

class CitizenDashboardRepository{
    public function getStats($userId){
        return [
            'total_reports' => Report::where('user_id', $userId)->count(),
            'pending_reports' => Report::where('user_id', $userId)->where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'resolved_reports' => Report::where('user_id', $userId)->where('status', 'resolved')->count(),
        ];
    }

    public function getLatestReports($userId){
        return Report::with(['category', 'images'])->where('user_id', $userId)->latest()->take(5)->get();
    }

    public function getAllMyReports($userId){
        return Report::with(['category', 'images'])->where('user_id', $userId)->latest()->get();
    }
}
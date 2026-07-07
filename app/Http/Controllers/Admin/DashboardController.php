<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ConversionLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalConversions = ConversionLog::count();
        $successConversions = ConversionLog::where('status', 'success')->count();
        $failedConversions = $totalConversions - $successConversions;
        $successRate = $totalConversions > 0 ? ($successConversions / $totalConversions) * 100 : 100;
        
        $avgExecutionTime = ConversionLog::where('status', 'success')->avg('execution_time') ?? 0;
        $totalUsers = User::whereNull('role_id')->count();

        $recentLogs = ConversionLog::orderBy('created_at', 'desc')->limit(10)->get();
        $recentUsers = User::whereNull('role_id')->orderBy('created_at', 'desc')->limit(5)->get();

        // Optimize 30-day statistics into just 2 queries
        $chartDays = 30;
        $startDate = now()->subDays($chartDays - 1)->startOfDay();

        // 1. Daily Conversions count
        $conversionsByDate = ConversionLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // 2. Daily Signups count
        $signupsByDate = User::whereNull('role_id')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $conversionsData = [];
        $signupsData = [];
        $maxConversions = 1;
        $maxSignups = 1;

        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $displayDate = now()->subDays($i)->format('d/M');
            
            $countConv = $conversionsByDate[$dateStr] ?? 0;
            $countSign = $signupsByDate[$dateStr] ?? 0;
            
            if ($countConv > $maxConversions) {
                $maxConversions = $countConv;
            }
            if ($countSign > $maxSignups) {
                $maxSignups = $countSign;
            }
            
            $conversionsData[] = ['label' => $displayDate, 'val' => $countConv];
            $signupsData[] = ['label' => $displayDate, 'val' => $countSign];
        }

        // Build SVG coordinates
        $conversionsPoints = [];
        $signupsPoints = [];
        
        foreach ($conversionsData as $idx => $data) {
            $x = $idx * (1000 / ($chartDays - 1));
            $y = 180 - ($data['val'] / $maxConversions * 150);
            $conversionsPoints[] = "$x,$y";
        }
        
        foreach ($signupsData as $idx => $data) {
            $x = $idx * (1000 / ($chartDays - 1));
            $y = 180 - ($data['val'] / $maxSignups * 150);
            $signupsPoints[] = "$x,$y";
        }
        
        $conversionsPointsStr = implode(' ', $conversionsPoints);
        $signupsPointsStr = implode(' ', $signupsPoints);
        
        $conversionsAreaStr = "0,180 " . $conversionsPointsStr . " 1000,180";
        $signupsAreaStr = "0,180 " . $signupsPointsStr . " 1000,180";

        return view('admin.dashboard', compact(
            'totalConversions',
            'successConversions',
            'failedConversions',
            'successRate',
            'avgExecutionTime',
            'totalUsers',
            'recentLogs',
            'recentUsers',
            'conversionsPointsStr',
            'signupsPointsStr',
            'conversionsAreaStr',
            'signupsAreaStr',
            'conversionsData',
            'signupsData'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ReportService;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $role = Auth::user()->role;
        $routeName = ($role === 'produksi') ? 'produksi.dashboard' : $role . '.dashboard';
        return redirect()->route($routeName);
    }

    public function admin(Request $request)
    {
        $period = $request->input('period', 'today');
        $validPeriods = ['today', 'month', 'year'];
        if (!in_array($period, $validPeriods)) $period = 'today';

        $stats = $this->reportService->getStatsByPeriod($period);
        // Merge with other stats needed for Admin (like active orders which are stateless)
        $quickStats = $this->reportService->getQuickStats(); // Still need active orders count
        $stats = array_merge($stats, [
            'statusCounts' => $quickStats['pending_orders'], // actually used in statusCounts? No, statusCounts is separate
            'revenue_growth' => 0 // dynamic growth not implemented yet
        ]);

        $incomeChart = $this->reportService->getChartData($period);
        $recentTransactions = $this->reportService->getRecentTransactions(5, $period);
        $topServices = $this->reportService->getServicePopularity(5, $period);
        $statusCounts = $this->reportService->getStatusCounts();

        return view('dashboards.admin', compact('stats', 'incomeChart', 'recentTransactions', 'topServices', 'statusCounts', 'period'));
    }

    public function kasir()
    {
        $stats = $this->reportService->getQuickStats();
        // Kasir always today
        $recentTransactions = $this->reportService->getTodayTransactions(5);

        return view('kasir.dashboard', compact('stats', 'recentTransactions'));
    }

    public function owner(Request $request)
    {
        $period = $request->input('period', 'today');
        $validPeriods = ['today', 'month', 'year'];
        if (!in_array($period, $validPeriods)) $period = 'today';

        $stats = $this->reportService->getStatsByPeriod($period);
        $incomeChart = $this->reportService->getChartData($period);
        
        $topServices = $this->reportService->getServicePopularity(5, $period);
        $recentTransactions = $this->reportService->getRecentTransactions(5, $period);
        $recentExpenses = $this->reportService->getRecentExpenses(5, $period);

        return view('dashboards.owner', compact('stats', 'incomeChart', 'topServices', 'recentTransactions', 'recentExpenses', 'period'));
    }

    public function produksi()
    {
        $queue = $this->reportService->getProductionQueue();
        $statusCounts = $this->reportService->getStatusCounts();

        return view('dashboards.produksi', compact('queue', 'statusCounts'));
    }
}

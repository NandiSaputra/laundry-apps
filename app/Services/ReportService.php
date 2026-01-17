<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get basic statistics for income and transactions.
     */
    public function getQuickStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $incomeMonth = Pembayaran::whereDate('tanggal_bayar', '>=', $thisMonth)
            ->whereHas('transaksi', function ($query) {
                $query->where('status', '!=', 'batal');
            })->sum('jumlah');

        $expenseMonth = Pengeluaran::whereDate('tanggal', '>=', $thisMonth)->sum('jumlah');

        $sevenDaysAgo = Carbon::today()->subDays(6);
        $incomeWeek = Pembayaran::whereDate('tanggal_bayar', '>=', $sevenDaysAgo)
            ->whereHas('transaksi', function ($query) {
                $query->where('status', '!=', 'batal');
            })->sum('jumlah');
        $expenseWeek = Pengeluaran::whereDate('tanggal', '>=', $sevenDaysAgo)->sum('jumlah');

        $incomeToday = Pembayaran::whereDate('tanggal_bayar', $today)
            ->whereHas('transaksi', function ($query) {
                $query->where('status', '!=', 'batal');
            })->sum('jumlah');

        $expenseToday = Pengeluaran::whereDate('tanggal', $today)->sum('jumlah');

        return [
            'income_today' => $incomeToday,
            'expense_today' => $expenseToday,
            'net_profit_today' => $incomeToday - $expenseToday,
            'income_month' => $incomeMonth,
            'expense_month' => $expenseMonth,
            'net_profit_month' => $incomeMonth - $expenseMonth,
            'income_week' => $incomeWeek,
            'expense_week' => $expenseWeek,
            'net_profit_week' => $incomeWeek - $expenseWeek,
            'pending_orders' => Transaksi::whereIn('status', ['pending', 'proses', 'cuci', 'setrika', 'packing'])->count(),
            'total_customers' => \App\Models\Pelanggan::count(),
        ];
    }

    /**
     * Get counts of transactions grouped by status.
     */
    public function getStatusCounts()
    {
        return Transaksi::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
    }

    /**
     * Get income data for a chart (last 7 days).
     */
    public function getIncomeChartData()
    {
        $sevenDaysAgo = Carbon::today()->subDays(6);
        
        // Batch query for income data (grouped by date)
        $incomeData = Pembayaran::whereDate('tanggal_bayar', '>=', $sevenDaysAgo)
            ->whereHas('transaksi', function ($query) {
                $query->where('status', '!=', 'batal');
            })
            ->selectRaw('DATE(tanggal_bayar) as date, SUM(jumlah) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Batch query for expense data (grouped by date)
        $expenseData = Pengeluaran::whereDate('tanggal', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(tanggal) as date, SUM(jumlah) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $days = [];
        $income = [];
        $expenses = [];
        $netProfit = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $days[] = $date->translatedFormat('D');
            
            $dailyIncome = $incomeData[$dateString] ?? 0;
            $dailyExpense = $expenseData[$dateString] ?? 0;

            $income[] = $dailyIncome;
            $expenses[] = $dailyExpense;
            $netProfit[] = $dailyIncome - $dailyExpense;
        }

        return [
            'labels' => $days,
            'income' => $income,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
        ];
    }

    /**
     * Get service popularity data for an area chart or pie chart.
     */
    public function getServicePopularity()
    {
        return DB::table('detail_transaksis')
            ->join('layanans', 'detail_transaksis.layanan_id', '=', 'layanans.id')
            ->select('layanans.nama', DB::raw('SUM(detail_transaksis.jumlah) as total_qty'))
            ->groupBy('layanans.id', 'layanans.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
    }

    /**
     * Get recent transactions for the dashboard.
     */
    public function getRecentTransactions(int $limit = 5, $period = 'month')
    {
        $dates = $this->getDateRange($period);
        
        return Transaksi::with(['pelanggan', 'user'])
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent transactions for today (Kasir Dashboard).
     */
    public function getTodayTransactions(int $limit = 5)
    {
        return Transaksi::with(['pelanggan', 'user'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent expenses for the dashboard.
     */
    public function getRecentExpenses(int $limit = 5, $period = 'month')
    {
        $dates = $this->getDateRange($period);

        return Pengeluaran::whereBetween('tanggal', [$dates['start'], $dates['end']])
            ->latest('tanggal')
            ->limit($limit)
            ->get();
    }

    /**
     * Get detailed transaction data for exports.
     */
    public function getTransactionData($startDate = null, $endDate = null)
    {
        $query = Transaksi::with(['pelanggan', 'user', 'pembayarans']);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->latest()->get();
    }

    /**
     * Get expense data for reports.
     */
    public function getExpenseData($startDate = null, $endDate = null)
    {
        $query = Pengeluaran::with('user');

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        return $query->latest('tanggal')->get();
    }

    /**
     * Get active queue for production staff.
     */
    public function getProductionQueue(int $limit = 10)
    {
        return Transaksi::with(['pelanggan', 'user'])
            ->whereIn('status', ['pending', 'proses', 'cuci', 'setrika', 'packing'])
            ->orderBy('tanggal_estimasi', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get staff performance data.
     */
    public function getStaffPerformance()
    {
        return \App\Models\User::withCount(['transaksis' => function($query) {
            $query->where('status', '!=', 'batal');
        }])
        ->withSum(['transaksis' => function($query) {
            $query->where('status', '!=', 'batal');
        }], 'total')
        ->orderByDesc('transaksis_count')
        ->get();
    }

    /**
     * Get top customers by total spending.
     */
    public function getTopCustomers(int $limit = 5)
    {
        return \App\Models\Pelanggan::withSum(['transaksis' => function($query) {
            $query->where('status', '!=', 'batal');
        }], 'total')
        ->withCount(['transaksis' => function($query) {
            $query->where('status', '!=', 'batal');
        }])
        ->orderByDesc('transaksis_sum_total')
        ->limit($limit)
        ->get();
    }
    /**
     * Get statistics based on a specific period (today, month, year).
     */
    public function getStatsByPeriod($period = 'month')
    {
        $dates = $this->getDateRange($period);
        $start = $dates['start'];
        $end = $dates['end'];
        
        $income = Pembayaran::whereBetween('tanggal_bayar', [$start, $end])
            ->whereHas('transaksi', function ($query) {
                $query->where('status', '!=', 'batal');
            })->sum('jumlah');

        $expense = Pengeluaran::whereBetween('tanggal', [$start, $end])->sum('jumlah');

        $netProfit = $income - $expense;

        return [
            'income' => $income,
            'expense' => $expense,
            'net_profit' => $netProfit,
        ];
    }

    /**
     * Get chart data based on period.
     */
    public function getChartData($period = 'month')
    {
        $dates = $this->getDateRange($period);
        $start = $dates['start'];
        $end = $dates['end'];

        $labels = [];
        $income = [];
        $expenses = [];
        
        if ($period === 'today') {
            // Group by Hour (0-23)
            for ($i = 0; $i < 24; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $income[$i] = 0;
                $expenses[$i] = 0;
            }

            $incomeData = Pembayaran::whereDate('tanggal_bayar', $start)
                ->whereHas('transaksi', function ($query) { $query->where('status', '!=', 'batal'); })
                ->selectRaw('HOUR(created_at) as hour, SUM(jumlah) as total')
                ->groupBy('hour')
                ->pluck('total', 'hour')->toArray();
            
            $expenseData = Pengeluaran::whereDate('tanggal', $start)
                ->selectRaw('HOUR(created_at) as hour, SUM(jumlah) as total')
                ->groupBy('hour')
                ->pluck('total', 'hour')->toArray();

            foreach ($incomeData as $hour => $total) $income[$hour] = $total;
            foreach ($expenseData as $hour => $total) $expenses[$hour] = $total;

        } elseif ($period === 'month') {
            // Group by Date
            $daysInMonth = $end->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = (string)$i;
                $income[$i] = 0;
                $expenses[$i] = 0;
            }

            $incomeData = Pembayaran::whereBetween('tanggal_bayar', [$start, $end])
                ->whereHas('transaksi', function ($query) { $query->where('status', '!=', 'batal'); })
                ->selectRaw('DAY(tanggal_bayar) as day, SUM(jumlah) as total')
                ->groupBy('day')
                ->pluck('total', 'day')->toArray();

            $expenseData = Pengeluaran::whereBetween('tanggal', [$start, $end])
                ->selectRaw('DAY(tanggal) as day, SUM(jumlah) as total')
                ->groupBy('day')
                ->pluck('total', 'day')->toArray();

            foreach ($incomeData as $day => $total) $income[$day] = $total;
            foreach ($expenseData as $day => $total) $expenses[$day] = $total;
            
            // Re-index to 0-based for JS arrays
            $income = array_values($income);
            $expenses = array_values($expenses);

        } elseif ($period === 'year') {
            // Group by Month
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $labels = $months;
            $income = array_fill(1, 12, 0);
            $expenses = array_fill(1, 12, 0);

            $incomeData = Pembayaran::whereYear('tanggal_bayar', $start->year)
                ->whereHas('transaksi', function ($query) { $query->where('status', '!=', 'batal'); })
                ->selectRaw('MONTH(tanggal_bayar) as month, SUM(jumlah) as total')
                ->groupBy('month')
                ->pluck('total', 'month')->toArray();

            $expenseData = Pengeluaran::whereYear('tanggal', $start->year)
                ->selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
                ->groupBy('month')
                ->pluck('total', 'month')->toArray();

            foreach ($incomeData as $month => $total) $income[$month] = $total;
            foreach ($expenseData as $month => $total) $expenses[$month] = $total;
            
            $income = array_values($income);
            $expenses = array_values($expenses);
        }

        $netProfit = [];
        for ($i = 0; $i < count($income); $i++) {
            $netProfit[] = $income[$i] - $expenses[$i];
        }

        return [
            'labels' => $labels,
            'income' => $income,
            'expenses' => $expenses,
            'net_profit' => $netProfit
        ];
    }

    private function getDateRange($period)
    {
        switch ($period) {
            case 'today':
                return ['start' => Carbon::today(), 'end' => Carbon::today()->endOfDay()];
            case 'year':
                return ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()->endOfYear()];
            case 'month':
            default:
                return ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()];
        }
    }
}

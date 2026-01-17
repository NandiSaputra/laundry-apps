<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $stats = $this->reportService->getQuickStats();
        $incomeChart = $this->reportService->getIncomeChartData();
        $topServices = $this->reportService->getServicePopularity();
        
        // Detailed data for tabular view if needed on index
        $transactions = $this->reportService->getTransactionData($startDate, $endDate);
        $expenses = $this->reportService->getExpenseData($startDate, $endDate);

        return view('admin.report.index', compact('stats', 'incomeChart', 'topServices', 'transactions', 'expenses'));
    }

    /**
     * Export to Excel (HTML format interpreted as Excel)
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $transactions = $this->reportService->getTransactionData($startDate, $endDate);
        $expenses = $this->reportService->getExpenseData($startDate, $endDate);
        
        $settingService = app(\App\Services\SettingService::class);
        $shopName = $settingService->get('shop_name', 'LaundryBiz');
        $shopAddress = $settingService->get('shop_address', '');

        $fileName = 'Laporan_Laundry_' . Carbon::now()->format('Ymd_His') . '.xls';
        
        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->view('admin.report.excel-report', compact(
            'transactions', 'expenses', 'shopName', 'shopAddress', 'startDate', 'endDate'
        ), 200, $headers);
    }

    /**
     * Print View for PDF
     */
    public function print(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $transactions = $this->reportService->getTransactionData($startDate, $endDate);
        $expenses = $this->reportService->getExpenseData($startDate, $endDate);

        return view('admin.report.print-report', compact('transactions', 'expenses', 'startDate', 'endDate'));
    }
}

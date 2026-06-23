<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalClients = Client::count();
        $totalCompanies = Company::count();
        $totalDocuments = Document::count();
        $totalQuotes = Document::where('type', 'quote')->count();
        $totalInvoices = Document::where('type', 'invoice')->count();
        $totalRevenue = Document::where('type', 'invoice')->sum('total');

        $recentUsers = User::latest()->take(5)->get();
        $recentDocuments = Document::with(['user:id,name', 'client:id,name'])
            ->latest()
            ->take(8)
            ->get();

        $documentsByMonth = Document::select(
            DB::raw("MONTH(issue_date) as month"),
            DB::raw("YEAR(issue_date) as year"),
            'type',
            DB::raw('count(*) as count')
        )
            ->whereYear('issue_date', Carbon::now()->year)
            ->groupBy('year', 'month', 'type')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $found = $documentsByMonth->get($i);
            $months[] = [
                'month' => $i,
                'label' => Carbon::create()->month($i)->translatedFormat('M'),
                'quotes' => $found ? $found->where('type', 'quote')->sum('count') : 0,
                'invoices' => $found ? $found->where('type', 'invoice')->sum('count') : 0,
            ];
        }

        $usersByMonth = User::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("YEAR(created_at) as year"),
            DB::raw('count(*) as count')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $userMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $userMonths[] = [
                'month' => $i,
                'label' => Carbon::create()->month($i)->translatedFormat('M'),
                'count' => $usersByMonth->get($i)?->count ?? 0,
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdmins',
            'totalClients', 'totalCompanies',
            'totalDocuments', 'totalQuotes', 'totalInvoices',
            'totalRevenue',
            'recentUsers', 'recentDocuments',
            'months', 'userMonths',
        ));
    }
}

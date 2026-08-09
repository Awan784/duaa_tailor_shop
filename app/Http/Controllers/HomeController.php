<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Ledger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'totalCustomers' => Customer::count(),
            'totalStocks' => Stock::where('expense', 0)->count(),
            'totalExpensesItems' => Stock::where('expense', 1)->count(),
            'totalSales' => Sale::count(),
            'todaySales' => Sale::whereDate('date', $today)->count(),
            'todayRevenue' => (float) Sale::whereDate('date', $today)->sum('net_total'),
            'monthSales' => Sale::whereDate('date', '>=', $monthStart)->count(),
            'monthRevenue' => (float) Sale::whereDate('date', '>=', $monthStart)->sum('net_total'),
            'inProcessing' => Sale::where('status', 'Inprocessing')->count(),
            'completed' => Sale::where('status', 'Completed')->count(),
            'lowStock' => Stock::where('expense', 0)->where('quantity', '<=', 5)->count(),
            'stockQty' => (float) Stock::where('expense', 0)->sum('quantity'),
            'todayCashReceived' => (float) Ledger::whereDate('created_at', $today)
                ->where('transaction_type', 'debit')
                ->sum('amount'),
        ];

        $recentSales = Sale::with('customer')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        $lowStockItems = Stock::where('expense', 0)
            ->where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->limit(6)
            ->get(['id', 'name', 'sku', 'quantity']);

        return view('admin.dashboard', compact('stats', 'recentSales', 'lowStockItems'));
    }

    public function changePassword() {
        return view('admin.change_password');
    }
    public function changePasswordPost(Request $request) {
        $user = auth()->user();
        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => 'required',
        ]);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
    }
}

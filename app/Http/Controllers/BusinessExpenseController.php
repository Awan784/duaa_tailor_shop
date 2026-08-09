<?php

namespace App\Http\Controllers;

use App\Models\BusinessExpense;
use App\Models\Cash;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($year < 2000 || $year > ((int) now()->year + 1)) {
            $year = now()->year;
        }

        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = (clone $monthStart)->endOfMonth();

        $query = BusinessExpense::query()
            ->whereDate('expense_date', '>=', $monthStart)
            ->whereDate('expense_date', '<=', $monthEnd)
            ->orderBy('expense_date', 'desc')
            ->orderBy('id', 'desc');

        $totalData = $query->get();

        $monthTotal = (float) $totalData->sum('amount');
        $monthCount = $totalData->count();

        $todayTotal = (float) BusinessExpense::whereDate('expense_date', now()->toDateString())->sum('amount');
        $yearTotal = (float) BusinessExpense::whereYear('expense_date', $year)->sum('amount');
        $allTotal = (float) BusinessExpense::sum('amount');

        $years = BusinessExpense::selectRaw('YEAR(expense_date) as y')
            ->whereNotNull('expense_date')
            ->distinct()
            ->orderBy('y', 'desc')
            ->pluck('y')
            ->filter()
            ->values();

        if ($years->isEmpty() || !$years->contains($year)) {
            $years = $years->push($year)->unique()->sortDesc()->values();
        }

        $monthLabel = $monthStart->format('F Y');

        return view('admin.business_expenses.index', compact(
            'totalData',
            'month',
            'year',
            'years',
            'monthLabel',
            'monthTotal',
            'monthCount',
            'todayTotal',
            'yearTotal',
            'allTotal'
        ));
    }

    public function create()
    {
        return view('admin.business_expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        BusinessExpense::create($validated);

        return redirect()
            ->route('business-expenses.index')
            ->with('success', 'Business expense added successfully.');
    }

    public function edit($id)
    {
        $edit = BusinessExpense::findOrFail($id);

        return view('admin.business_expenses.update', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense = BusinessExpense::findOrFail($id);
        $expense->update($validated);

        return redirect()
            ->route('business-expenses.index')
            ->with('success', 'Business expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = BusinessExpense::findOrFail($id);
        $expense->delete();

        return redirect()
            ->route('business-expenses.index')
            ->with('success', 'Business expense deleted successfully.');
    }

    public function report(Request $request)
    {
        $data = $this->buildFinanceReport($request);

        return view('admin.report.business_expense', $data);
    }

    public function reportPrint(Request $request)
    {
        $data = $this->buildFinanceReport($request);

        if (empty($data['fromDate']) || empty($data['toDate'])) {
            return redirect()->route('report.business.expense');
        }

        return view('admin.report.business_expense_print', $data);
    }

    private function buildFinanceReport(Request $request): array
    {
        $filterMode = $request->query('filter_mode', 'month');
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($year < 2000 || $year > ((int) now()->year + 1)) {
            $year = now()->year;
        }

        $hasRange = false;
        $rangeLabel = null;

        if ($filterMode === 'range' && $fromDate && $toDate) {
            $start = Carbon::parse($fromDate)->startOfDay();
            $end = Carbon::parse($toDate)->endOfDay();
            if ($start->gt($end)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
                $fromDate = $start->toDateString();
                $toDate = $end->toDateString();
            }
            $hasRange = true;
            $rangeLabel = $start->format('d M Y') . ' - ' . $end->format('d M Y');
        } elseif ($filterMode === 'month' && $request->filled('month')) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $fromDate = $start->toDateString();
            $toDate = $end->toDateString();
            $hasRange = true;
            $rangeLabel = $start->format('F Y');
        } elseif ($fromDate && $toDate) {
            $start = Carbon::parse($fromDate)->startOfDay();
            $end = Carbon::parse($toDate)->endOfDay();
            $hasRange = true;
            $rangeLabel = $start->format('d M Y') . ' - ' . $end->format('d M Y');
            $filterMode = 'range';
        }

        $expenses = collect();
        $cashes = collect();
        $totalExpenses = 0.0;
        $totalPayments = 0.0;
        $totalReceives = 0.0;
        $netCash = 0.0;

        if ($hasRange) {
            $expenses = BusinessExpense::query()
                ->whereDate('expense_date', '>=', $fromDate)
                ->whereDate('expense_date', '<=', $toDate)
                ->orderBy('expense_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $cashes = Cash::query()
                ->whereDate('cash_date', '>=', $fromDate)
                ->whereDate('cash_date', '<=', $toDate)
                ->orderBy('cash_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $totalExpenses = (float) $expenses->sum('amount');
            $totalPayments = (float) $cashes->where('type', 'cash_payment')->sum('amount');
            $totalReceives = (float) $cashes->where('type', 'cash_receive')->sum('amount');
            $netCash = $totalReceives - $totalPayments;
        }

        $years = collect(range(now()->year - 5, now()->year + 1))->reverse()->values();

        return compact(
            'filterMode',
            'month',
            'year',
            'years',
            'fromDate',
            'toDate',
            'hasRange',
            'rangeLabel',
            'expenses',
            'cashes',
            'totalExpenses',
            'totalPayments',
            'totalReceives',
            'netCash'
        );
    }
}

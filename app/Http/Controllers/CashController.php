<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);
        $type = $request->query('type', 'all');

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($year < 2000 || $year > ((int) now()->year + 1)) {
            $year = now()->year;
        }
        if (!in_array($type, ['all', 'cash_payment', 'cash_receive'], true)) {
            $type = 'all';
        }

        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = (clone $monthStart)->endOfMonth();

        $query = Cash::query()
            ->whereDate('cash_date', '>=', $monthStart)
            ->whereDate('cash_date', '<=', $monthEnd)
            ->when($type !== 'all', fn ($q) => $q->where('type', $type))
            ->orderBy('cash_date', 'desc')
            ->orderBy('id', 'desc');

        $totalData = $query->get();

        $monthPayment = (float) Cash::whereDate('cash_date', '>=', $monthStart)
            ->whereDate('cash_date', '<=', $monthEnd)
            ->where('type', 'cash_payment')
            ->sum('amount');

        $monthReceive = (float) Cash::whereDate('cash_date', '>=', $monthStart)
            ->whereDate('cash_date', '<=', $monthEnd)
            ->where('type', 'cash_receive')
            ->sum('amount');

        $monthNet = $monthReceive - $monthPayment;
        $monthCount = $totalData->count();
        $monthLabel = $monthStart->format('F Y');

        $years = Cash::selectRaw('YEAR(cash_date) as y')
            ->whereNotNull('cash_date')
            ->distinct()
            ->orderBy('y', 'desc')
            ->pluck('y')
            ->filter()
            ->values();

        if ($years->isEmpty() || !$years->contains($year)) {
            $years = $years->push($year)->unique()->sortDesc()->values();
        }

        return view('admin.cash.index', compact(
            'totalData',
            'month',
            'year',
            'years',
            'type',
            'monthLabel',
            'monthPayment',
            'monthReceive',
            'monthNet',
            'monthCount'
        ));
    }

    public function create()
    {
        return view('admin.cash.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:cash_payment,cash_receive',
            'amount' => 'required|numeric|min:0',
            'cash_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Cash::create($validated);

        return redirect()
            ->route('cash.index')
            ->with('success', 'Cash entry added successfully.');
    }

    public function edit($id)
    {
        $edit = Cash::findOrFail($id);

        return view('admin.cash.update', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:cash_payment,cash_receive',
            'amount' => 'required|numeric|min:0',
            'cash_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $cash = Cash::findOrFail($id);
        $cash->update($validated);

        return redirect()
            ->route('cash.index')
            ->with('success', 'Cash entry updated successfully.');
    }

    public function destroy($id)
    {
        $cash = Cash::findOrFail($id);
        $cash->delete();

        return redirect()
            ->route('cash.index')
            ->with('success', 'Cash entry deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShipmentController extends Controller
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

        $totalData = Shipment::with(['customer', 'sales'])
            ->whereDate('shipment_date', '>=', $monthStart)
            ->whereDate('shipment_date', '<=', $monthEnd)
            ->orderBy('shipment_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $monthCount = $totalData->count();
        $monthCharges = (float) $totalData->sum('charges');
        $monthInvoices = $totalData->sum(fn ($s) => $s->sales->count());
        $monthLabel = $monthStart->format('F Y');

        $years = Shipment::selectRaw('YEAR(shipment_date) as y')
            ->whereNotNull('shipment_date')
            ->distinct()
            ->orderBy('y', 'desc')
            ->pluck('y')
            ->filter()
            ->values();

        if ($years->isEmpty() || !$years->contains($year)) {
            $years = $years->push($year)->unique()->sortDesc()->values();
        }

        return view('admin.shipments.index', compact(
            'totalData',
            'month',
            'year',
            'years',
            'monthLabel',
            'monthCount',
            'monthCharges',
            'monthInvoices'
        ));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('admin.shipments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateShipment($request);

        DB::transaction(function () use ($validated) {
            $shipment = Shipment::create([
                'customer_id' => $validated['customer_id'],
                'shipment_number' => $validated['shipment_number'],
                'shipment_date' => $validated['shipment_date'],
                'charges' => $validated['charges'] ?? null,
                'description' => $validated['description'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $shipment->sales()->sync($validated['sale_ids']);
        });

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment created successfully.');
    }

    public function edit($id)
    {
        $edit = Shipment::with('sales')->findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $selectedSaleIds = $edit->sales->pluck('id')->all();

        return view('admin.shipments.update', compact('edit', 'customers', 'selectedSaleIds'));
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);
        $validated = $this->validateShipment($request, $shipment->id);

        DB::transaction(function () use ($shipment, $validated) {
            $shipment->update([
                'customer_id' => $validated['customer_id'],
                'shipment_number' => $validated['shipment_number'],
                'shipment_date' => $validated['shipment_date'],
                'charges' => $validated['charges'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $shipment->sales()->sync($validated['sale_ids']);
        });

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment updated successfully.');
    }

    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);

        DB::transaction(function () use ($shipment) {
            $shipment->sales()->detach();
            $shipment->delete();
        });

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment deleted successfully.');
    }

    public function customerInvoices(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $status = $request->query('status', 'all');
        $excludeShipmentId = $request->query('shipment_id');

        if (!in_array($status, ['all', 'Completed', 'Inprocessing'], true)) {
            $status = 'all';
        }

        $assignedSaleIds = DB::table('shipment_sale')
            ->when($excludeShipmentId, function ($q) use ($excludeShipmentId) {
                $q->where('shipment_id', '!=', $excludeShipmentId);
            })
            ->pluck('sale_id')
            ->all();

        $invoices = Sale::query()
            ->where('customer_id', $customer->id)
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when(!empty($assignedSaleIds), fn ($q) => $q->whereNotIn('id', $assignedSaleIds))
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get(['id', 'bill_no', 'date', 'status', 'net_total']);

        return response()->json([
            'invoices' => $invoices->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'bill_no' => $sale->bill_no,
                    'date' => $sale->date ? Carbon::parse($sale->date)->format('Y-m-d') : '-',
                    'status' => $sale->status,
                    'net_total' => number_format((float) $sale->net_total, 2),
                    'label' => sprintf(
                        '%s | %s | %s | %s',
                        $sale->bill_no,
                        $sale->date ? Carbon::parse($sale->date)->format('Y-m-d') : '-',
                        $sale->status,
                        number_format((float) $sale->net_total, 2)
                    ),
                ];
            })->values(),
        ]);
    }

    private function validateShipment(Request $request, ?int $shipmentId = null): array
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'shipment_number' => 'required|string|max:255',
            'shipment_date' => 'required|date',
            'charges' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'sale_ids' => 'required|array|min:1',
            'sale_ids.*' => 'required|integer|exists:sales,id',
        ]);

        $saleIds = array_values(array_unique(array_map('intval', $validated['sale_ids'])));
        $customerId = (int) $validated['customer_id'];

        $ownedCount = Sale::where('customer_id', $customerId)
            ->whereIn('id', $saleIds)
            ->count();

        if ($ownedCount !== count($saleIds)) {
            throw ValidationException::withMessages([
                'sale_ids' => 'All selected invoices must belong to the chosen customer.',
            ]);
        }

        $conflictQuery = DB::table('shipment_sale')
            ->whereIn('sale_id', $saleIds);

        if ($shipmentId) {
            $conflictQuery->where('shipment_id', '!=', $shipmentId);
        }

        if ($conflictQuery->exists()) {
            throw ValidationException::withMessages([
                'sale_ids' => 'One or more selected invoices are already assigned to another shipment.',
            ]);
        }

        $validated['sale_ids'] = $saleIds;
        $validated['charges'] = $request->filled('charges') ? $validated['charges'] : null;

        return $validated;
    }

    public function report(Request $request)
    {
        $data = $this->buildShipmentReport($request);

        return view('admin.report.shipment', $data);
    }

    public function reportPrint(Request $request)
    {
        $data = $this->buildShipmentReport($request);

        if (empty($data['hasRange'])) {
            return redirect()->route('report.shipment');
        }

        return view('admin.report.shipment_print', $data);
    }

    private function buildShipmentReport(Request $request): array
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
        } elseif ($filterMode === 'month' && ($request->filled('month') || $request->filled('filter_mode'))) {
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

        $shipments = collect();
        $groupedByCustomer = collect();
        $totalShipments = 0;
        $totalCharges = 0.0;
        $totalInvoices = 0;
        $customerCount = 0;

        if ($hasRange) {
            $shipments = Shipment::with(['customer', 'sales'])
                ->whereDate('shipment_date', '>=', $fromDate)
                ->whereDate('shipment_date', '<=', $toDate)
                ->orderBy('shipment_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $groupedByCustomer = $shipments
                ->groupBy(fn ($s) => $s->customer_id)
                ->sortBy(fn ($group) => optional($group->first()->customer)->name ?? 'zzz')
                ->values();

            $totalShipments = $shipments->count();
            $totalCharges = (float) $shipments->sum('charges');
            $totalInvoices = $shipments->sum(fn ($s) => $s->sales->count());
            $customerCount = $groupedByCustomer->count();
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
            'shipments',
            'groupedByCustomer',
            'totalShipments',
            'totalCharges',
            'totalInvoices',
            'customerCount'
        );
    }
}

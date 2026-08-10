@extends('admin.layout.interface')
@section('content')
    <div class="main_content_iner overly_inner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="f_s_30 f_w_700 text_white">Shipment Report</h3>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Darzi Shop</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item">Report</li>
                                <li class="breadcrumb-item active">Shipments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="white_card mb-3">
                <div class="card-body">
                    <h4 class="mb-3">Report Filter</h4>
                    <form method="GET" action="{{ route('report.shipment') }}" id="shipmentReportForm">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label" for="filter_mode">Filter By</label>
                                <select name="filter_mode" id="filter_mode" class="form-control">
                                    <option value="month" {{ ($filterMode ?? 'month') === 'month' ? 'selected' : '' }}>Month</option>
                                    <option value="range" {{ ($filterMode ?? '') === 'range' ? 'selected' : '' }}>Date From / To</option>
                                </select>
                            </div>

                            <div class="col-md-3 filter-month">
                                <label class="form-label" for="month">Month</label>
                                <select name="month" id="month" class="form-control">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ (int) $month === $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 filter-month">
                                <label class="form-label" for="year">Year</label>
                                <select name="year" id="year" class="form-control">
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}" {{ (int) $year === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 filter-range d-none">
                                <label class="form-label" for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3 filter-range d-none">
                                <label class="form-label" for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Report</button>
                            </div>
                            @if (!empty($hasRange))
                                <div class="col-md-2">
                                    <a href="{{ route('report.shipment.print', request()->query()) }}" target="_blank" class="btn_1 d-inline-block text-center w-100">Print</a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            @if (!empty($hasRange))
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box rpt-box-main">
                            <span>Shipments</span>
                            <h4>{{ number_format($totalShipments) }}</h4>
                            <small>{{ $rangeLabel }}</small>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box">
                            <span>Customers</span>
                            <h4>{{ number_format($customerCount) }}</h4>
                            <small>With shipments</small>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box">
                            <span>Invoices</span>
                            <h4>{{ number_format($totalInvoices) }}</h4>
                            <small>Linked invoices</small>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box">
                            <span>Total Charges</span>
                            <h4>{{ number_format($totalCharges, 2) }}</h4>
                            <small>{{ $rangeLabel }}</small>
                        </div>
                    </div>
                </div>

                @forelse ($groupedByCustomer as $customerIndex => $group)
                    @php
                        $customer = optional($group->first())->customer;
                        $invoices = $group->flatMap(fn ($s) => $s->sales)->unique('id')->values();
                        $customerInvoiceTotal = (float) $invoices->sum('net_total');
                        $customerCharges = (float) $group->sum('charges');
                        $customerCombined = $customerInvoiceTotal + $customerCharges;
                    @endphp
                    <div class="white_card mb-3">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted text-uppercase d-block mb-1">Sr No. {{ $customerIndex + 1 }}</small>
                                <h3 class="mb-0 shipment-customer-name">{{ $customer->name ?? 'Unknown Customer' }}</h3>
                            </div>

                            <div class="border rounded-top px-3 py-2 bg-light mb-0">
                                @foreach ($group as $shipKey => $shipment)
                                    <div class="{{ !$loop->last ? 'mb-1' : '' }}">
                                        <strong>Shipment {{ $shipKey + 1 }}:</strong>
                                        {{ $shipment->shipment_number }}
                                        &nbsp;|&nbsp; Date: {{ optional($shipment->shipment_date)->format('Y-m-d') }}
                                        &nbsp;|&nbsp; Charges: {{ $shipment->charges !== null ? number_format((float) $shipment->charges, 2) : '-' }}
                                        @if(!empty($shipment->description))
                                            &nbsp;|&nbsp; Notes: {{ $shipment->description }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr class="table-dark">
                                            <th style="width: 90px;">Sr No.</th>
                                            <th>Name</th>
                                            <th>bill no</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invKey => $sale)
                                            <tr>
                                                <td>{{ $invKey + 1 }}</td>
                                                <td class="{{ $invKey === 0 ? 'shipment-customer-name' : '' }}">
                                                    {{ $invKey === 0 ? ($customer->name ?? '-') : '' }}
                                                </td>
                                                <td>{{ $sale->bill_no }}</td>
                                                <td class="text-end">{{ number_format((float) $sale->net_total, 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>1</td>
                                                <td class="shipment-customer-name">{{ $customer->name ?? '-' }}</td>
                                                <td colspan="2">No invoices</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total Bills Payment</th>
                                            <th class="text-end">{{ number_format($customerInvoiceTotal, 0) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Total Shipment Charges</th>
                                            <th class="text-end">{{ number_format($customerCharges, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Bills + Shipment</th>
                                            <th class="text-end">{{ number_format($customerCombined, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="white_card mb-3">
                        <div class="card-body text-center">No shipments found for {{ $rangeLabel }}.</div>
                    </div>
                @endforelse
            @endif
        </div>
    </div>

    <style>
        .rpt-box {
            background: #fff;
            border: 1px solid #e6e3dc;
            border-radius: 12px;
            padding: 16px;
            min-height: 110px;
        }
        .rpt-box span {
            display: block;
            font-size: 12px;
            color: #6f6a63;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 6px;
        }
        .rpt-box h4 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #2c2a26;
        }
        .rpt-box small {
            display: block;
            margin-top: 6px;
            color: #6f6a63;
            font-size: 12px;
        }
        .rpt-box-main {
            background: linear-gradient(135deg, #2c2a26 0%, #4a453c 100%);
            border-color: transparent;
        }
        .rpt-box-main span,
        .rpt-box-main h4,
        .rpt-box-main small { color: #fff; }
        .rpt-box-main small { color: rgba(255,255,255,.78); }
        .shipment-customer-name {
            font-weight: 800 !important;
            font-size: 1.35rem !important;
            color: #111827 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mode = document.getElementById('filter_mode');
            const monthBlocks = document.querySelectorAll('.filter-month');
            const rangeBlocks = document.querySelectorAll('.filter-range');

            function syncFilter() {
                const isRange = mode.value === 'range';
                monthBlocks.forEach(el => el.classList.toggle('d-none', isRange));
                rangeBlocks.forEach(el => el.classList.toggle('d-none', !isRange));
            }

            mode.addEventListener('change', syncFilter);
            syncFilter();
        });
    </script>
@endsection

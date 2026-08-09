@extends('admin.layout.interface')
@section('content')
    <div class="main_content_iner overly_inner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="f_s_30 f_w_700 text_white">Business Expense Report</h3>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Darzi Shop</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item">Report</li>
                                <li class="breadcrumb-item active">Business Expense</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="white_card mb-3">
                <div class="card-body">
                    <h4 class="mb-3">Report Filter</h4>
                    <form method="GET" action="{{ route('report.business.expense') }}" id="financeReportForm">
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
                                    <a href="{{ route('report.business.expense.print', request()->query()) }}" target="_blank" class="btn_1 d-inline-block text-center w-100">Print</a>
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
                            <span>Total Expenses</span>
                            <h4>{{ number_format($totalExpenses, 2) }}</h4>
                            <small>{{ $rangeLabel }}</small>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box">
                            <span>Cash Payments</span>
                            <h4>{{ number_format($totalPayments, 2) }}</h4>
                            <small>Outflow</small>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box">
                            <span>Cash Receive</span>
                            <h4>{{ number_format($totalReceives, 2) }}</h4>
                            <small>Inflow</small>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-box">
                            <span>Net Cash</span>
                            <h4>{{ number_format($netCash, 2) }}</h4>
                            <small>Receive - Payment</small>
                        </div>
                    </div>
                </div>

                <div class="white_card mb-3">
                    <div class="card-body">
                        <h4 class="mb-3">Business Expenses ({{ $expenses->count() }})</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Expense</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenses as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ optional($item->expense_date)->format('Y-m-d') }}</td>
                                            <td>{{ $item->description ?: '-' }}</td>
                                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center">No expenses found.</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total Expenses</th>
                                        <th class="text-end">{{ number_format($totalExpenses, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="white_card mb-3">
                    <div class="card-body">
                        <h4 class="mb-3">Cash Entries ({{ $cashes->count() }})</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr#</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cashes as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->type_label }}</td>
                                            <td>{{ optional($item->cash_date)->format('Y-m-d') }}</td>
                                            <td>{{ $item->description ?: '-' }}</td>
                                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center">No cash entries found.</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Cash Payments</th>
                                        <th class="text-end">{{ number_format($totalPayments, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">Cash Receive</th>
                                        <th class="text-end">{{ number_format($totalReceives, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
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

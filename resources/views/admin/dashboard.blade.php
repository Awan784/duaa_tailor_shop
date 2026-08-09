@extends('admin.layout.interface')
@section('content')
    <div class="main_content_iner overly_inner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="f_s_30 f_w_700 text_white">Dashboard</h3>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Darzi Shop</a></li>
                                <li class="breadcrumb-item active">Overview</li>
                            </ol>
                        </div>
                        <div class="ds-welcome text-end d-none d-md-block">
                            <p class="mb-0">Welcome back, {{ auth()->user()->name }}</p>
                            <span>{{ now()->format('l, d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI cards --}}
            <div class="row g-3 mb-3">
                <div class="col-6 col-xl-3">
                    <div class="ds-kpi ds-kpi-ink">
                        <div>
                            <span class="ds-kpi-label">Today Sales</span>
                            <h4>{{ number_format($stats['todaySales']) }}</h4>
                            <small>${{ number_format($stats['todayRevenue'], 2) }} revenue</small>
                        </div>
                        <div class="ds-kpi-icon"><i class="fas fa-receipt"></i></div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="ds-kpi ds-kpi-gold">
                        <div>
                            <span class="ds-kpi-label">This Month</span>
                            <h4>${{ number_format($stats['monthRevenue'], 2) }}</h4>
                            <small>{{ number_format($stats['monthSales']) }} orders</small>
                        </div>
                        <div class="ds-kpi-icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="ds-kpi ds-kpi-ink">
                        <div>
                            <span class="ds-kpi-label">Customers</span>
                            <h4>{{ number_format($stats['totalCustomers']) }}</h4>
                            <small>Active customer base</small>
                        </div>
                        <div class="ds-kpi-icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="ds-kpi ds-kpi-gold">
                        <div>
                            <span class="ds-kpi-label">Stock Items</span>
                            <h4>{{ number_format($stats['totalStocks']) }}</h4>
                            <small>{{ number_format($stats['stockQty'], 0) }} total qty</small>
                        </div>
                        <div class="ds-kpi-icon"><i class="fas fa-boxes"></i></div>
                    </div>
                </div>
            </div>

            {{-- Status strip --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="ds-stat">
                        <span>In Processing</span>
                        <strong>{{ number_format($stats['inProcessing']) }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="ds-stat">
                        <span>Completed Sales</span>
                        <strong>{{ number_format($stats['completed']) }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="ds-stat {{ $stats['lowStock'] > 0 ? 'ds-stat-warn' : '' }}">
                        <span>Low Stock</span>
                        <strong>{{ number_format($stats['lowStock']) }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="ds-stat">
                        <span>Cash Received Today</span>
                        <strong>${{ number_format($stats['todayCashReceived'], 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                {{-- Recent sales --}}
                <div class="col-lg-8">
                    <div class="white_card ds-panel h-100">
                        <div class="ds-panel-head">
                            <div>
                                <h4>Recent Sales</h4>
                                <p>Latest bills and order status</p>
                            </div>
                            <a href="{{ route('sales.index') }}" class="btn_1">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table ds-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Bill</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentSales as $sale)
                                        <tr>
                                            <td>
                                                <a href="{{ route('sales.print', $sale->id) }}" target="_blank">
                                                    {{ $sale->bill_no }}
                                                </a>
                                            </td>
                                            <td>{{ $sale->customer->name ?? '-' }}</td>
                                            <td>{{ $sale->date ? date('Y-m-d', strtotime($sale->date)) : '-' }}</td>
                                            <td>
                                                <span class="ds-badge {{ $sale->status == 'Completed' ? 'ds-badge-ok' : 'ds-badge-pending' }}">
                                                    {{ $sale->status }}
                                                </span>
                                            </td>
                                            <td class="text-end">${{ number_format($sale->net_total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No sales found yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Side panels --}}
                <div class="col-lg-4">
                    <div class="white_card ds-panel mb-3">
                        <div class="ds-panel-head">
                            <div>
                                <h4>Quick Actions</h4>
                                <p>Jump into daily work</p>
                            </div>
                        </div>
                        <div class="ds-actions">
                            <a href="{{ route('sales.create') }}"><i class="fas fa-plus"></i> Add Sale</a>
                            <a href="{{ route('stocks.create') }}"><i class="fas fa-box"></i> Add Stock</a>
                            <a href="{{ route('customers.create') }}"><i class="fas fa-user-plus"></i> Add Customer</a>
                            <a href="{{ route('sales.index') }}"><i class="fas fa-list"></i> All Sales</a>
                            <a href="{{ route('report.stock') }}"><i class="fas fa-file-alt"></i> Stock Report</a>
                        </div>
                    </div>

                    <div class="white_card ds-panel">
                        <div class="ds-panel-head">
                            <div>
                                <h4>Low Stock Alert</h4>
                                <p>Items with qty ≤ 5</p>
                            </div>
                        </div>
                        <ul class="ds-lowlist">
                            @forelse ($lowStockItems as $item)
                                <li>
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                        <span>{{ $item->sku }}</span>
                                    </div>
                                    <em>{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</em>
                                </li>
                            @empty
                                <li class="ds-empty">All stock levels look healthy.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ds-welcome p {
            color: rgba(255,255,255,.92);
            font-weight: 600;
            font-size: 14px;
        }
        .ds-welcome span {
            color: rgba(255,255,255,.7);
            font-size: 12px;
        }

        .ds-kpi {
            border-radius: 14px;
            padding: 18px 18px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 118px;
            color: #fff;
            box-shadow: 0 8px 20px rgba(44, 42, 38, 0.08);
        }
        .ds-kpi-ink { background: linear-gradient(135deg, #2c2a26 0%, #4a453c 100%); }
        .ds-kpi-gold { background: linear-gradient(135deg, #3f3b35 0%, #c2a15a 100%); }
        .ds-kpi-label {
            display: block;
            font-size: 12px;
            letter-spacing: .04em;
            text-transform: uppercase;
            opacity: .8;
            margin-bottom: 6px;
        }
        .ds-kpi h4 {
            margin: 0;
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.1;
        }
        .ds-kpi small {
            display: block;
            margin-top: 6px;
            color: rgba(255,255,255,.78);
            font-size: 12px;
        }
        .ds-kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .ds-stat {
            background: #fff;
            border: 1px solid #e6e3dc;
            border-radius: 12px;
            padding: 14px 16px;
        }
        .ds-stat span {
            display: block;
            color: #6f6a63;
            font-size: 12px;
            margin-bottom: 4px;
        }
        .ds-stat strong {
            color: #2c2a26;
            font-size: 20px;
            font-weight: 700;
        }
        .ds-stat-warn strong { color: #b45309; }

        .ds-panel {
            border: 1px solid #e6e3dc !important;
            border-radius: 14px !important;
            padding: 18px !important;
            box-shadow: 0 1px 2px rgba(44,42,38,.04) !important;
        }
        .ds-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }
        .ds-panel-head h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #2c2a26;
        }
        .ds-panel-head p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #6f6a63;
        }

        .ds-table thead th {
            background: #f8f7f4 !important;
            color: #6f6a63 !important;
            font-size: 11px !important;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #e6e3dc !important;
            white-space: nowrap;
        }
        .ds-table tbody td {
            border-color: #eeebe4 !important;
            vertical-align: middle;
            font-size: 13px;
            color: #2c2a26;
        }
        .ds-table a { color: #2c2a26; font-weight: 600; }
        .ds-table a:hover { color: #c2a15a; }

        .ds-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .ds-badge-ok { background: #ecfdf3; color: #067647; }
        .ds-badge-pending { background: #fff7ed; color: #b45309; }

        .ds-actions {
            display: grid;
            gap: 8px;
        }
        .ds-actions a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 10px;
            background: #f8f7f4;
            border: 1px solid #e6e3dc;
            color: #2c2a26;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: .15s ease;
        }
        .ds-actions a i { color: #c2a15a; width: 16px; text-align: center; }
        .ds-actions a:hover {
            background: #2c2a26;
            border-color: #2c2a26;
            color: #fff;
        }
        .ds-actions a:hover i { color: #c2a15a; }

        .ds-lowlist {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .ds-lowlist li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #eeebe4;
        }
        .ds-lowlist li:last-child { border-bottom: 0; }
        .ds-lowlist strong {
            display: block;
            color: #2c2a26;
            font-size: 13px;
        }
        .ds-lowlist span {
            color: #6f6a63;
            font-size: 11px;
        }
        .ds-lowlist em {
            font-style: normal;
            font-weight: 700;
            color: #b45309;
            background: #fff7ed;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 12px;
        }
        .ds-empty {
            color: #6f6a63 !important;
            font-size: 13px;
            justify-content: center !important;
        }

        @media (max-width: 575px) {
            .ds-kpi h4 { font-size: 22px; }
            .ds-kpi { min-height: 104px; padding: 14px; }
        }
    </style>
@endsection

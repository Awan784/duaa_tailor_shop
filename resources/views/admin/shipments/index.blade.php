@extends('admin.layout.interface')
@section('content')
    <div class="main_content_iner overly_inner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="f_s_30 f_w_700 text_white">Shipments</h3>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Darzi Shop</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Shipments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="white_card mb-3">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('shipments.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label" for="month">Month</label>
                            <select name="month" id="month" class="form-control">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (int) $month === $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="year">Year</label>
                            <select name="year" id="year" class="form-control">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ (int) $year === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('shipments.index') }}" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6 col-xl-4">
                    <div class="cash-box cash-box-main">
                        <span>Shipments</span>
                        <h4>{{ number_format($monthCount) }}</h4>
                        <small>{{ $monthLabel }}</small>
                    </div>
                </div>
                <div class="col-6 col-xl-4">
                    <div class="cash-box">
                        <span>Invoices Shipped</span>
                        <h4>{{ number_format($monthInvoices) }}</h4>
                        <small>Linked invoices</small>
                    </div>
                </div>
                <div class="col-6 col-xl-4">
                    <div class="cash-box cash-box-payment">
                        <span>Shipment Charges</span>
                        <h4>{{ number_format($monthCharges, 2) }}</h4>
                        <small>{{ $monthLabel }}</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="white_card">
                        <div class="card-body">
                            <div class="white_card card_height_100 mb_30">
                                <div class="white_card_body">
                                    <div class="QA_section">
                                        <div class="white_box_tittle list_header">
                                            <h4>{{ $monthLabel }} Shipments</h4>
                                            <div class="box_right d-flex lms_block">
                                                <div class="add_button ms-2">
                                                    <a href="{{ route('shipments.create') }}" class="btn_1">Add Shipment</a>
                                                </div>
                                            </div>
                                        </div>

                                        @if(session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        <div class="QA_table mb_30">
                                            <table class="table lms_table_active">
                                                <thead>
                                                    <tr>
                                                        <th>Sr#</th>
                                                        <th>Shipment No</th>
                                                        <th>Customer</th>
                                                        <th>Date</th>
                                                        <th>Invoices</th>
                                                        <th>Charges</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($totalData as $key => $data)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $data->shipment_number }}</td>
                                                            <td>{{ $data->customer->name ?? '-' }}</td>
                                                            <td>{{ $data->shipment_date ? $data->shipment_date->format('Y-m-d') : '-' }}</td>
                                                            <td>
                                                                @if($data->sales->isNotEmpty())
                                                                    {{ $data->sales->pluck('bill_no')->implode(', ') }}
                                                                    <small class="d-block text-muted">({{ $data->sales->count() }})</small>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{ $data->charges !== null ? number_format($data->charges, 2) : '-' }}</td>
                                                            <td class="td-width">
                                                                <a href="{{ route('shipments.edit', $data->id) }}" class="btn btn-dark btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a type="button"
                                                                   class="btn btn-danger btn-sm modalDeleteButton"
                                                                   data-form-action="{{ route('shipments.destroy', $data->id) }}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#confirmDeleteModal1">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No shipments found for {{ $monthLabel }}.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cash-box {
            background: #fff;
            border: 1px solid #e6e3dc;
            border-radius: 12px;
            padding: 16px;
            min-height: 110px;
        }
        .cash-box span {
            display: block;
            font-size: 12px;
            color: #6f6a63;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 6px;
        }
        .cash-box h4 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #2c2a26;
            line-height: 1.15;
        }
        .cash-box small {
            display: block;
            margin-top: 6px;
            color: #6f6a63;
            font-size: 12px;
        }
        .cash-box-main {
            background: linear-gradient(135deg, #2c2a26 0%, #4a453c 100%);
            border-color: transparent;
        }
        .cash-box-main span,
        .cash-box-main small,
        .cash-box-main h4 { color: #fff; }
        .cash-box-main small { color: rgba(255,255,255,.78); }
        .cash-box-payment { border-top: 3px solid #c2a15a; }
    </style>
@endsection

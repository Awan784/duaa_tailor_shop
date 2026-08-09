@extends('admin.layout.interface')
@section('content')
    <div class="main_content_iner overly_inner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="f_s_30 f_w_700 text_white">Business Expenses</h3>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Darzi Shop</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Business Expenses</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Month / Year filter --}}
            <div class="white_card mb-3">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('business-expenses.index') }}" class="row g-2 align-items-end">
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
                        <div class="col-md-3">
                            <label class="form-label" for="year">Year</label>
                            <select name="year" id="year" class="form-control">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ (int) $year === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('business-expenses.index') }}" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary boxes --}}
            <div class="row g-3 mb-3">
                <div class="col-6 col-xl-3">
                    <div class="be-box be-box-main">
                        <span>Selected Month Total</span>
                        <h4>{{ number_format($monthTotal, 2) }}</h4>
                        <small>{{ $monthLabel }}</small>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="be-box">
                        <span>Month Entries</span>
                        <h4>{{ number_format($monthCount) }}</h4>
                        <small>Records in {{ $monthLabel }}</small>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="be-box">
                        <span>Today Expenses</span>
                        <h4>{{ number_format($todayTotal, 2) }}</h4>
                        <small>{{ now()->format('d M Y') }}</small>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="be-box">
                        <span>{{ $year }} Year Total</span>
                        <h4>{{ number_format($yearTotal, 2) }}</h4>
                        <small>All time: {{ number_format($allTotal, 2) }}</small>
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
                                            <h4>{{ $monthLabel }} Expenses</h4>
                                            <div class="box_right d-flex lms_block">
                                                <div class="serach_field_2">
                                                    <div class="search_inner">
                                                        <form action="#">
                                                            <div class="search_field">
                                                                <input type="text" placeholder="Search content here..." id="customSearchDataTable"/>
                                                            </div>
                                                            <button type="submit"><i class="ti-search"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="add_button ms-2">
                                                    <a href="{{ route('business-expenses.create') }}" class="btn_1">Add Expense</a>
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
                                                        <th scope="col">Sr#</th>
                                                        <th scope="col">Expense</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($totalData as $key => $data)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $data->title }}</td>
                                                            <td>{{ number_format($data->amount, 2) }}</td>
                                                            <td>{{ $data->expense_date ? $data->expense_date->format('Y-m-d') : '-' }}</td>
                                                            <td>{{ \Illuminate\Support\Str::limit($data->description, 60) }}</td>
                                                            <td class="td-width">
                                                                <a href="{{ route('business-expenses.edit', $data->id) }}" class="btn btn-dark btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a type="button"
                                                                   class="btn btn-danger btn-sm modalDeleteButton"
                                                                   data-form-action="{{ route('business-expenses.destroy', $data->id) }}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#confirmDeleteModal1">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center">No expenses found for {{ $monthLabel }}.</td>
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
        .be-box {
            background: #fff;
            border: 1px solid #e6e3dc;
            border-radius: 12px;
            padding: 16px;
            min-height: 110px;
        }
        .be-box span {
            display: block;
            font-size: 12px;
            color: #6f6a63;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 6px;
        }
        .be-box h4 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #2c2a26;
            line-height: 1.15;
        }
        .be-box small {
            display: block;
            margin-top: 6px;
            color: #6f6a63;
            font-size: 12px;
        }
        .be-box-main {
            background: linear-gradient(135deg, #2c2a26 0%, #4a453c 100%);
            border-color: transparent;
        }
        .be-box-main span,
        .be-box-main small {
            color: rgba(255,255,255,.78);
        }
        .be-box-main h4 {
            color: #fff;
        }
    </style>
@endsection

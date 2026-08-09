@extends('admin.layout.interface')
@section('content')
    <div class="main_content_iner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard_header mb_50">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dashboard_header_title">
                                    <h3>Add Business Expense</h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dashboard_breadcam text-end">
                                    <p>
                                        <a href="{{ url('dashboard') }}">Dashboard</a>
                                        <i class="fas fa-caret-right"></i> Add Business Expense
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="white_card card_height_100 mb_30">
                        <div class="white_card_body">
                            <div class="card-body">
                                <div class="white_card_header">
                                    <div class="box_header m-0">
                                        <div class="main-title">
                                            <h3 class="m-0">Expense Info</h3>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('business-expenses.store') }}" method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label" for="title">Expense</label>
                                            <input name="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                                   id="title" value="{{ old('title') }}" placeholder="e.g. Rent, Electricity, Transport" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label" for="amount">Amount</label>
                                            <input name="amount" type="number" step="0.01" min="0"
                                                   class="form-control @error('amount') is-invalid @enderror"
                                                   id="amount" value="{{ old('amount') }}" placeholder="0.00" required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label" for="expense_date">Expense Date</label>
                                            <input name="expense_date" type="date"
                                                   class="form-control @error('expense_date') is-invalid @enderror"
                                                   id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                                            @error('expense_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" id="description" rows="4"
                                                      class="form-control @error('description') is-invalid @enderror"
                                                      placeholder="Write expense details...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('business-expenses.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

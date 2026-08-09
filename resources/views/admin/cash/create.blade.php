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
                                    <h3>Add Cash</h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dashboard_breadcam text-end">
                                    <p>
                                        <a href="{{ url('dashboard') }}">Dashboard</a>
                                        <i class="fas fa-caret-right"></i> Add Cash
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
                                            <h3 class="m-0">Cash Info</h3>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('cash.store') }}" method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label" for="type">Type</label>
                                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                                <option value="">Select Type</option>
                                                <option value="cash_payment" {{ old('type') === 'cash_payment' ? 'selected' : '' }}>Cash Payment</option>
                                                <option value="cash_receive" {{ old('type') === 'cash_receive' ? 'selected' : '' }}>Cash Receive</option>
                                            </select>
                                            @error('type')
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
                                            <label class="form-label" for="cash_date">Date</label>
                                            <input name="cash_date" type="date"
                                                   class="form-control @error('cash_date') is-invalid @enderror"
                                                   id="cash_date" value="{{ old('cash_date', date('Y-m-d')) }}" required>
                                            @error('cash_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" id="description" rows="4"
                                                      class="form-control @error('description') is-invalid @enderror"
                                                      placeholder="Write details...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('cash.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layout.interface')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="main_content_iner">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard_header mb_50">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dashboard_header_title">
                                    <h3>Add Shipment</h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dashboard_breadcam text-end">
                                    <p>
                                        <a href="{{ url('dashboard') }}">Dashboard</a>
                                        <i class="fas fa-caret-right"></i> Add Shipment
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
                                            <h3 class="m-0">Shipment Info</h3>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('shipments.store') }}" method="post" id="shipmentForm">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label" for="customer_id">Customer</label>
                                            <select name="customer_id" id="customer_id"
                                                    class="form-control select2-select @error('customer_id') is-invalid @enderror" required>
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ (string) old('customer_id') === (string) $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label" for="invoice_status">Invoice Status</label>
                                            <select id="invoice_status" class="form-control">
                                                <option value="all">All</option>
                                                <option value="Inprocessing">Inprocessing</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="sale_ids">Invoices</label>
                                            <select name="sale_ids[]" id="sale_ids" multiple
                                                    class="form-control select2-select @error('sale_ids') is-invalid @enderror" required>
                                            </select>
                                            <small class="text-muted">Select one or more invoices for this customer. Already shipped invoices are hidden.</small>
                                            @error('sale_ids')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="form-label" for="shipment_date">Shipment Date</label>
                                            <input name="shipment_date" type="date"
                                                   class="form-control @error('shipment_date') is-invalid @enderror"
                                                   id="shipment_date" value="{{ old('shipment_date', date('Y-m-d')) }}" required>
                                            @error('shipment_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="form-label" for="shipment_number">Shipment Number</label>
                                            <input name="shipment_number" type="text"
                                                   class="form-control @error('shipment_number') is-invalid @enderror"
                                                   id="shipment_number" value="{{ old('shipment_number') }}"
                                                   placeholder="Shipment / tracking number" required>
                                            @error('shipment_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="form-label" for="charges">Shipment Charges (optional)</label>
                                            <input name="charges" type="number" step="0.01" min="0"
                                                   class="form-control @error('charges') is-invalid @enderror"
                                                   id="charges" value="{{ old('charges') }}" placeholder="0.00">
                                            @error('charges')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-label" for="description">Notes</label>
                                            <textarea name="description" id="description" rows="3"
                                                      class="form-control @error('description') is-invalid @enderror"
                                                      placeholder="Optional notes...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('shipments.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function () {
            const invoicesUrl = @json(url('shipments/customer-invoices'));
            const oldSaleIds = @json(array_map('intval', (array) old('sale_ids', [])));

            $('#customer_id').select2({ placeholder: 'Choose Customer', allowClear: true, width: '100%' });
            $('#sale_ids').select2({ placeholder: 'Select invoices', allowClear: true, width: '100%' });

            function loadInvoices(preserveSelected) {
                const customerId = $('#customer_id').val();
                const status = $('#invoice_status').val() || 'all';
                const $saleIds = $('#sale_ids');
                const selected = preserveSelected ? ($saleIds.val() || oldSaleIds) : [];

                $saleIds.empty().trigger('change');

                if (!customerId) {
                    return;
                }

                $.get(invoicesUrl + '/' + customerId, { status: status })
                    .done(function (res) {
                        (res.invoices || []).forEach(function (inv) {
                            const option = new Option(inv.label, inv.id, false, selected.map(String).includes(String(inv.id)));
                            $saleIds.append(option);
                        });
                        $saleIds.trigger('change');
                    });
            }

            $('#customer_id').on('change', function () {
                loadInvoices(false);
            });

            $('#invoice_status').on('change', function () {
                loadInvoices(true);
            });

            if ($('#customer_id').val()) {
                loadInvoices(true);
            }
        })();
    </script>
@endsection

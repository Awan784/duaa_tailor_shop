@php
    $customer = $sale->customer;
    $products = $sale->getProducts() ?? [];
    $cashReceived = (float) $sale->ledgerAmount();
    $balanceDue = max(0, (float) $sale->net_total - $cashReceived);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $sale->bill_no }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy: #0b2a4a;
            --teal: #1f6f7a;
            --gold: #e2b93b;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
            --soft: #f3f4f6;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        html, body {
            width: 100%;
            background: #fff;
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .toolbar { display: none; }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            position: relative;
            overflow: hidden;
            padding: 18mm 16mm 14mm;
        }

        /* Corner accents like the sample */
        .corner-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            border-top: 78px solid var(--navy);
            border-right: 78px solid transparent;
            z-index: 1;
        }

        .corner-top::after {
            content: "";
            position: absolute;
            top: -78px;
            left: 0;
            width: 0;
            height: 0;
            border-top: 48px solid var(--teal);
            border-right: 48px solid transparent;
        }

        .corner-bottom {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 0;
            height: 0;
            border-bottom: 54px solid var(--teal);
            border-left: 54px solid transparent;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            padding-left: 18px;
        }

        .header h1 {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #111827;
            line-height: 1;
            margin-top: 8px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .brand img {
            width: 54px;
            height: 54px;
            object-fit: contain;
            background: transparent;
            border-radius: 0;
            padding: 0;
        }

        .brand .name {
            line-height: 1.05;
        }

        .brand .name strong {
            display: block;
            color: var(--teal);
            font-size: 18px;
            font-weight: 800;
        }

        .brand .name span {
            display: block;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        /* Bill + meta */
        .info-row {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            margin-bottom: 26px;
        }

        .bill-to h3,
        .invoice-meta h3,
        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .bill-to p,
        .invoice-meta p {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.55;
        }

        .bill-to .client {
            color: #111827;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta .line {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 4px;
            font-size: 12.5px;
        }

        .invoice-meta .line span {
            color: var(--muted);
            min-width: 110px;
            text-align: right;
        }

        .invoice-meta .line strong {
            color: #111827;
            min-width: 120px;
            text-align: left;
        }

        /* Table */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 22px;
        }

        table.items thead th {
            background: var(--gold);
            color: #111827;
            font-size: 12px;
            font-weight: 800;
            text-align: left;
            padding: 11px 10px;
        }

        table.items thead th.center { text-align: center; }
        table.items thead th.right { text-align: right; }

        table.items tbody td {
            padding: 11px 10px;
            font-size: 12.5px;
            border-bottom: 1px solid #eceff3;
            color: #1f2937;
            vertical-align: middle;
        }

        table.items tbody tr:nth-child(even) td {
            background: #f3f4f6;
        }

        table.items td.center { text-align: center; }
        table.items td.right { text-align: right; font-weight: 700; }

        /* Terms + totals */
        .mid-row {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 28px;
            margin-bottom: 22px;
            align-items: start;
        }

        .terms ul {
            padding-left: 18px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .terms li { margin-bottom: 4px; }

        .totals {
            padding-top: 4px;
        }

        .totals .row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            font-size: 13px;
            margin-bottom: 8px;
            color: var(--muted);
        }

        .totals .row strong {
            color: #111827;
            font-weight: 700;
        }

        .totals .divider {
            border-top: 1px solid #d1d5db;
            margin: 8px 0 10px;
        }

        .totals .grand {
            font-size: 15px;
            color: #111827;
            font-weight: 800;
        }

        .totals .grand span,
        .totals .grand strong {
            color: #111827;
            font-weight: 800;
        }

        /* Payment bar */
        .pay-bar {
            background: var(--navy);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            padding: 10px 14px;
            margin: 8px 0 14px;
        }

        /* Footer */
        .footer {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: end;
            padding-bottom: 10px;
        }

        .pay-info h4,
        .questions h4 {
            font-size: 13px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .pay-info p,
        .questions p {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.55;
        }

        .questions { margin-top: 14px; }

        .sign-wrap {
            text-align: right;
        }

        .sign-wrap .date {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 28px;
        }

        .sign-line {
            border-bottom: 1px solid #9ca3af;
            width: 180px;
            margin-left: auto;
            margin-bottom: 6px;
            height: 28px;
        }

        .sign-wrap .name {
            font-size: 12px;
            color: #111827;
            font-weight: 700;
        }

        @media screen {
            body {
                background: #dbe1e8;
                padding: 18px 0 30px;
            }

            .toolbar {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-bottom: 14px;
            }

            .toolbar button,
            .toolbar a {
                border: 0;
                border-radius: 8px;
                padding: 10px 16px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                font-family: Arial, Helvetica, sans-serif;
            }

            .toolbar .print-btn {
                background: var(--navy);
                color: #fff;
            }

            .toolbar .back-btn {
                background: #fff;
                color: var(--navy);
                border: 1px solid #cbd5e1;
            }

            .page {
                box-shadow: 0 12px 34px rgba(11, 42, 74, 0.18);
            }
        }

        @media print {
            .toolbar { display: none !important; }
            .page {
                width: 100%;
                min-height: 297mm;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="print-btn" type="button" onclick="window.print()">Print Invoice</button>
        <a class="back-btn" href="{{ route('sales.index') }}">Back to Sales</a>
    </div>

    <div class="page">
        <div class="corner-top"></div>
        <div class="corner-bottom"></div>

        <div class="content">
            <div class="header">
                <h1>INVOICE</h1>
                <div class="brand">
                    <img src="{{ asset('admin-assets/img/logo.png') }}" alt="Darzi Shop"
                         onerror="this.src='{{ asset('admin-assets/img/pdf/logo.png') }}'">
                    <div class="name">
                        <strong>Darzi</strong>
                        <span>Shop</span>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="bill-to">
                    <h3>Bill To:</h3>
                    <p class="client">{{ $customer->name ?? 'Walk-in Customer' }}</p>
                    @if(!empty($customer?->address))
                        <p>{{ $customer->address }}</p>
                    @endif
                    @if(!empty($customer?->phone))
                        <p>{{ $customer->phone }}</p>
                    @endif
                    @if(!empty($customer?->email))
                        <p>{{ $customer->email }}</p>
                    @endif
                </div>
                <div class="invoice-meta">
                    <div class="line">
                        <span>Invoice Number</span>
                        <strong>{{ $sale->bill_no }}</strong>
                    </div>
                    <div class="line">
                        <span>Invoice Date</span>
                        <strong>{{ $sale->date ? \Carbon\Carbon::parse($sale->date)->format('F j, Y') : '-' }}</strong>
                    </div>
                    <div class="line">
                        <span>Status</span>
                        <strong>{{ $sale->status }}</strong>
                    </div>
                </div>
            </div>

            <h3 class="section-title">Service Details:</h3>
            <table class="items">
                <thead>
                    <tr>
                        <th style="width: 8%;">No</th>
                        <th style="width: 36%;">Description of Service</th>
                        <th class="center" style="width: 14%;">SKU</th>
                        <th class="center" style="width: 12%;">Quantity</th>
                        <th class="center" style="width: 14%;">Rate</th>
                        <th class="right" style="width: 16%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $key => $product)
                        <tr>
                            <td class="center">{{ $key + 1 }}</td>
                            <td>{{ $product['name'] ?? '-' }}</td>
                            <td class="center">{{ $product['sku'] ?? '-' }}</td>
                            <td class="center">{{ $product['quantity'] ?? 0 }}</td>
                            <td class="center">{{ number_format((float) ($product['unit_price'] ?? 0), 2) }}</td>
                            <td class="right">{{ number_format((float) ($product['quantity'] ?? 0) * (float) ($product['unit_price'] ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="center" style="padding: 18px; color: #6b7280;">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mid-row">
                <div class="terms">
                    <h3 class="section-title">Terms and Conditions:</h3>
                    <ul>
                        <li>Payment is due upon receipt of this invoice.</li>
                        <li>Please make checks payable to Darzi Shop.</li>
                        <li>Goods once sold are subject to shop policy.</li>
                        <li>For any discrepancy, contact us within 7 days.</li>
                    </ul>
                    @if(!empty($sale->description))
                        <p style="margin-top:10px;font-size:12px;color:#6b7280;">
                            <strong style="color:#111827;">Note:</strong> {{ $sale->description }}
                        </p>
                    @endif
                </div>
                <div class="totals">
                    <div class="row">
                        <span>Subtotal</span>
                        <strong>{{ number_format((float) $sale->sub_total, 2) }}</strong>
                    </div>
                    <div class="row">
                        <span>Labour Cost</span>
                        <strong>{{ number_format((float) $sale->labour_cost, 2) }}</strong>
                    </div>
                    <div class="row">
                        <span>Discount</span>
                        <strong>{{ number_format((float) $sale->discount, 2) }}</strong>
                    </div>
                    <div class="divider"></div>
                    <div class="row grand">
                        <span>Total Amount Due</span>
                        <strong>{{ number_format((float) $sale->net_total, 2) }}</strong>
                    </div>
                    <div class="row" style="margin-top:8px;">
                        <span>Cash Received</span>
                        <strong>{{ number_format($cashReceived, 2) }}</strong>
                    </div>
                    <div class="row">
                        <span>Balance Due</span>
                        <strong>{{ number_format($balanceDue, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="pay-bar">Payment Information:</div>

            <div class="footer">
                <div>
                    <div class="pay-info">
                        <p><strong style="color:#111827;">Payment Method:</strong> Cash / Bank Transfer</p>
                        <p><strong style="color:#111827;">Invoice Status:</strong> {{ $sale->status }}</p>
                        <p><strong style="color:#111827;">Balance Due:</strong> {{ number_format($balanceDue, 2) }}</p>
                    </div>
                    <div class="questions">
                        <h4>Questions</h4>
                        <p>Please contact Darzi Shop for invoice support.</p>
                    </div>
                </div>
                <div class="sign-wrap">
                    <div class="date">
                        Date: {{ $sale->date ? \Carbon\Carbon::parse($sale->date)->format('F j, Y') : now()->format('F j, Y') }}
                    </div>
                    <div class="sign-line"></div>
                    <div class="name">Authorized Signature</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>

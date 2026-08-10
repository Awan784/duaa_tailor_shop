<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Report</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .toolbar {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        .toolbar button {
            border: 0;
            background: #2c2a26;
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        h1 {
            margin: 0 0 4px;
            font-size: 24px;
            color: #2c2a26;
        }
        .sub {
            color: #6b7280;
            margin-bottom: 18px;
            font-size: 13px;
        }
        .boxes {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }
        .box {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 12px;
        }
        .box span {
            display: block;
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .box strong {
            font-size: 20px;
            color: #111827;
        }
        .customer-block {
            margin-top: 24px;
            page-break-inside: avoid;
        }
        .customer-name {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
        }
        .customer-sr {
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .shipment-list {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-bottom: 0;
            padding: 8px 10px;
            font-size: 12px;
            line-height: 1.5;
        }
        .shipment-list div + div {
            margin-top: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
            text-align: left;
            vertical-align: top;
        }
        th { background: #f3f4f6; }
        .right { text-align: right; }
        .center { text-align: center; }
        tfoot th { background: #eceff3; }
        .section-title {
            background: #2c2a26 !important;
            color: #fff !important;
            font-weight: 700;
        }
        .name-cell {
            font-weight: 800;
            font-size: 16px;
            color: #111827;
        }
        .empty {
            text-align: center;
            color: #6b7280;
            padding: 24px 0;
        }
        .grand-total {
            margin-top: 24px;
        }
        .grand-total th {
            font-size: 14px;
            padding: 10px;
        }
        @media print {
            .toolbar { display: none !important; }
            body { padding: 0; }
            .box, th, .section-title, .shipment-list {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        @media screen {
            body { background: #e5e7eb; }
            .sheet {
                max-width: 1000px;
                margin: 0 auto;
                background: #fff;
                padding: 24px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
    @php
        $grandInvoiceTotal = 0;
        $grandInvoiceCount = 0;
        $grandCharges = (float) $totalCharges;
    @endphp

    <div class="toolbar">
        <button type="button" onclick="window.print()">Print Report</button>
    </div>

    <div class="sheet">
        <h1>Darzi Shop — Shipment Report</h1>
        <div class="sub">Period: {{ $rangeLabel }} | Generated: {{ now()->format('d M Y, h:i A') }}</div>

        <div class="boxes">
            <div class="box">
                <span>Shipments</span>
                <strong>{{ number_format($totalShipments) }}</strong>
            </div>
            <div class="box">
                <span>Customers</span>
                <strong>{{ number_format($customerCount) }}</strong>
            </div>
            <div class="box">
                <span>Invoices</span>
                <strong>{{ number_format($totalInvoices) }}</strong>
            </div>
            <div class="box">
                <span>Total Charges</span>
                <strong>{{ number_format($totalCharges, 2) }}</strong>
            </div>
        </div>

        @forelse ($groupedByCustomer as $customerIndex => $group)
            @php
                $customer = optional($group->first())->customer;
                $invoices = $group->flatMap(fn ($s) => $s->sales)->unique('id')->values();
                $customerInvoiceTotal = (float) $invoices->sum('net_total');
                $customerCharges = (float) $group->sum('charges');
                $customerCombined = $customerInvoiceTotal + $customerCharges;
                $grandInvoiceTotal += $customerInvoiceTotal;
                $grandInvoiceCount += $invoices->count();
            @endphp
            <div class="customer-block">
                <div class="customer-sr">Sr No. {{ $customerIndex + 1 }}</div>
                <div class="customer-name">{{ $customer->name ?? 'Unknown Customer' }}</div>

                <div class="shipment-list">
                    @foreach ($group as $shipKey => $shipment)
                        <div>
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

                <table>
                    <thead>
                        <tr>
                            <th class="section-title" style="width: 70px;">Sr No.</th>
                            <th class="section-title">Name</th>
                            <th class="section-title">bill no</th>
                            <th class="section-title right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invKey => $sale)
                            <tr>
                                <td class="center">{{ $invKey + 1 }}</td>
                                <td class="{{ $invKey === 0 ? 'name-cell' : '' }}">
                                    {{ $invKey === 0 ? ($customer->name ?? '-') : '' }}
                                </td>
                                <td>{{ $sale->bill_no }}</td>
                                <td class="right">{{ number_format((float) $sale->net_total, 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="center">1</td>
                                <td class="name-cell">{{ $customer->name ?? '-' }}</td>
                                <td colspan="2">No invoices</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="right">Total Bills Payment</th>
                            <th class="right">{{ number_format($customerInvoiceTotal, 0) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="right">Total Shipment Charges</th>
                            <th class="right">{{ number_format($customerCharges, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="right">Bills + Shipment</th>
                            <th class="right">{{ number_format($customerCombined, 0) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @empty
            <div class="empty">No shipments found for {{ $rangeLabel }}.</div>
        @endforelse

        @if($groupedByCustomer->isNotEmpty())
            <table class="grand-total">
                <tfoot>
                    <tr>
                        <th class="right">Grand Total Bills Payment ({{ $grandInvoiceCount }} invoices)</th>
                        <th class="right" style="width: 160px;">{{ number_format($grandInvoiceTotal, 0) }}</th>
                    </tr>
                    <tr>
                        <th class="right">Grand Total Shipment Charges</th>
                        <th class="right">{{ number_format($grandCharges, 2) }}</th>
                    </tr>
                    <tr>
                        <th class="right">Grand Total (Bills + Shipment)</th>
                        <th class="right">{{ number_format($grandInvoiceTotal + $grandCharges, 0) }}</th>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</body>
</html>

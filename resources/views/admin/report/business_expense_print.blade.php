<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Expense Report</title>
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
        h2 {
            font-size: 16px;
            margin: 18px 0 8px;
            color: #2c2a26;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
        }
        .right { text-align: right; }
        .center { text-align: center; }
        tfoot th {
            background: #eceff3;
        }
        @media print {
            .toolbar { display: none !important; }
            body { padding: 0; }
            .box, th {
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
    <div class="toolbar">
        <button type="button" onclick="window.print()">Print Report</button>
    </div>

    <div class="sheet">
        <h1>Darzi Shop — Business Expense Report</h1>
        <div class="sub">Period: {{ $rangeLabel }} | Generated: {{ now()->format('d M Y, h:i A') }}</div>

        <div class="boxes">
            <div class="box">
                <span>Total Expenses</span>
                <strong>{{ number_format($totalExpenses, 2) }}</strong>
            </div>
            <div class="box">
                <span>Cash Payments</span>
                <strong>{{ number_format($totalPayments, 2) }}</strong>
            </div>
            <div class="box">
                <span>Cash Receive</span>
                <strong>{{ number_format($totalReceives, 2) }}</strong>
            </div>
            <div class="box">
                <span>Net Cash</span>
                <strong>{{ number_format($netCash, 2) }}</strong>
            </div>
        </div>

        <h2>Business Expenses</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:8%;">Sr#</th>
                    <th>Expense</th>
                    <th style="width:14%;">Date</th>
                    <th>Description</th>
                    <th class="right" style="width:16%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $key => $item)
                    <tr>
                        <td class="center">{{ $key + 1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ optional($item->expense_date)->format('Y-m-d') }}</td>
                        <td>{{ $item->description ?: '-' }}</td>
                        <td class="right">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="center">No expenses found.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="right">Total Expenses</th>
                    <th class="right">{{ number_format($totalExpenses, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <h2>Cash Entries</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:8%;">Sr#</th>
                    <th style="width:18%;">Type</th>
                    <th style="width:14%;">Date</th>
                    <th>Description</th>
                    <th class="right" style="width:16%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cashes as $key => $item)
                    <tr>
                        <td class="center">{{ $key + 1 }}</td>
                        <td>{{ $item->type_label }}</td>
                        <td>{{ optional($item->cash_date)->format('Y-m-d') }}</td>
                        <td>{{ $item->description ?: '-' }}</td>
                        <td class="right">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="center">No cash entries found.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="right">Cash Payments</th>
                    <th class="right">{{ number_format($totalPayments, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="right">Cash Receive</th>
                    <th class="right">{{ number_format($totalReceives, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="right">Net Cash</th>
                    <th class="right">{{ number_format($netCash, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>

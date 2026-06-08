<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
        h1 { margin: 0 0 4px 0; font-size: 24px; }
        h2 { margin: 0 0 10px 0; font-size: 16px; color: #111827; }
        .meta { margin-bottom: 14px; font-size: 13px; color: #4b5563; }
        .controls { margin-bottom: 14px; display: flex; gap: 8px; }
        .btn { border: 1px solid #d1d5db; background: #f9fafb; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: 700; }
        .summary { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 16px; }
        .box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; background: #fff; }
        .label { font-size: 11px; text-transform: uppercase; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 18px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; table-layout: fixed; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; font-size: 12px; vertical-align: top; word-break: break-word; }
        th { background: #f3f4f6; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; }
        .section { margin-bottom: 14px; }

        @media print {
            @page { size: A4; margin: 12mm; }
            body { margin: 0; }
            .controls { display: none; }
        }
    </style>
</head>
<body>
    <div class="controls">
        <button class="btn" onclick="window.print()">Print</button>
        <button class="btn" onclick="window.close()">Close</button>
    </div>

    <h1>BuildWise Sales Report</h1>
    <div class="meta">
        Scope: {{ $scopeLabel }}<br>
        Period: Last {{ $period }} day(s)<br>
        Generated: {{ now()->format('M d, Y h:i A') }}
    </div>

    <section class="summary">
        <div class="box">
            <div class="label">Total Revenue</div>
            <div class="value">PHP {{ number_format($totalSales, 2) }}</div>
        </div>
        <div class="box">
            <div class="label">Items Sold</div>
            <div class="value">{{ number_format($totalItemsSold) }}</div>
        </div>
        <div class="box">
            <div class="label">Orders</div>
            <div class="value">{{ number_format($transactionCount) }}</div>
        </div>
        <div class="box">
            <div class="label">Average Order</div>
            <div class="value">PHP {{ number_format($averageTransaction, 2) }}</div>
        </div>
    </section>

    <section class="section">
        <h2>Orders by Staff</h2>
        <table>
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Orders</th>
                    <th>Items</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesByStaff as $staff)
                    <tr>
                        <td>{{ $staff->user_name }}</td>
                        <td>{{ $staff->orders_count }}</td>
                        <td>{{ $staff->total_items }}</td>
                        <td>PHP {{ number_format($staff->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">No staff sales data for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="section">
        <h2>Sales by Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Items</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesByCategory as $item)
                    <tr>
                        <td>{{ $item->category }}</td>
                        <td>{{ number_format($item->items) }}</td>
                        <td>PHP {{ number_format($item->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No category sales data for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="section">
        <h2>Top Selling Materials</h2>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ number_format($item->total_quantity) }}</td>
                        <td>PHP {{ number_format($item->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No sales data available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="section">
        <h2>Recent Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Staff</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->order_number ?? ('SALE-' . $sale->id) }}</td>
                        <td>{{ $sale->product->name ?? 'N/A' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>{{ $sale->user->name ?? 'N/A' }}</td>
                        <td>PHP {{ number_format($sale->total_price, 2) }}</td>
                        <td>{{ $sale->sale_date?->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No recent transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</body>
</html>

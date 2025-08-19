<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>請求書</title>
    <style>
        body { font-family: "ipaexg"; font-size: 12px; line-height: 1.4; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        .header { display: flex; flex-wrap: wrap; margin-bottom: 15px; }
        .box {
            background: #f7fafc;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            padding: 6px 8px;
            margin-right: 8px;
            margin-bottom: 6px;
            width: 180px;
        }
        .box strong { font-size: 11px; color: #555; }
        .totals { display: flex; flex-wrap: wrap; margin-bottom: 15px; }
        .total-item {
            background: #f7fafc;
            border: 1px solid #cbd5e0;
            padding: 6px 8px;
            margin-right: 10px;
            border-radius: 4px;
            font-size: 13px;
            min-width: 120px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #cbd5e0; padding: 4px; font-size: 11px; text-align: center; }
        th { background: #f7fafc; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>請求書</h1>
    <div style="position: absolute; top: 100px; right: 80px; text-align: right; font-size: 13px;">
        <strong>株式会社サンプル</strong><br>
        〒123-4567 東京都新宿区西新宿1-2-3<br>
        TEL: 03-1234-5678<br>
        Email: info@example.com<br>
        T-123456789012
    </div>

    <div class="header">
        <div class="box">
            <strong>請求日</strong><br>
            {{ now()->format('Y-m-d') }}
        </div>
        <div class="box">
            <strong>Shop</strong><br>
            {{ $order_h->shop_name }}
        </div>
        <div class="box">
            <strong>氏名：車</strong><br>
            {{ $order_h->customer_name }} 様　 {{ $order_h->car_name }}
        </div>
    </div>

    <div class="totals">
        <div class="total-item">税抜計: {{ number_format($order_total) }}円</div>
        <div class="total-item">消費税: {{ number_format($order_total * 0.1) }}円</div>
        <div class="total-item">請求額: {{ number_format(floor($order_total * 1.1)) }}円</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>品名</th>
                {{-- <th>マスタ単価</th> --}}
                <th>販売単価</th>
                <th>数量</th>
                <th>部品計</th>
                <th>工賃</th>
                <th>小計</th>
                <th>備考</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order_fs as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align:left;">{{ $item->item_name }}</td>
                {{-- <td class="right">{{ number_format($item->sales_price) }}</td> --}}
                <td class="right">{{ number_format($item->sales_price) }}</td>
                <td>{{ $item->item_pcs }}</td>
                <td class="right">{{ number_format($item->item_pcs * $item->sales_price) }}</td>
                <td class="right">{{ number_format($item->work_fee) }}</td>
                <td class="right">{{ number_format($item->item_pcs * $item->sales_price + $item->work_fee) }}</td>
                <td style="text-align:left;">{{ $item->detail_info ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

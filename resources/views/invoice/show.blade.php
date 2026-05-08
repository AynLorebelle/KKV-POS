<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 20px;
            font-family: 'Courier New', Courier, monospace;
        }
        .receipt {
            background-color: #fff;
            width: 320px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .logo {
            font-size: 44px;
            font-family: sans-serif;
            letter-spacing: -2px;
            margin-bottom: 5px;
            position: relative;
        }
        .logo-dot {
            font-size: 16px;
            position: absolute;
            top: 5px;
        }
        .header-text {
            font-size: 12px;
            line-height: 1.2;
        }
        hr {
            border: top 1px dashed #000;
            border-bottom: none;
            margin: 10px 0;
        }
        .items-table {
            width: 100%;
            font-size: 12px;
        }
        .items-table th, .items-table td {
            padding: 2px 0;
        }
        .items-table th {
            font-weight: normal;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .summary-table {
            width: 100%;
            font-size: 12px;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 1px 0;
        }
        .footer-text {
            font-size: 11px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="text-center">
            <div class="logo">K<span class="logo-dot">.</span>KV</div>
            <div class="header-text font-bold" style="font-size: 14px;">KKV Retail Corporation</div>
            <div class="header-text mt-2">Optd by: KKM Retail Corporation</div>
            <div class="header-text">2135-2145 SM CITY J MALL A.S. FORTUNA ST.</div>
            <div class="header-text">BAKILID CITY OF MANDAUE CEBU</div>
            <div class="header-text">VAT REG TIN 648-350-240-00006</div>
            <div class="header-text">M.I.N.#25111016312959851 SN:25V08H2010013</div>
        </div>

        <hr style="border-top: 1px dashed #000;">

        <div class="header-text text-center">SALES INVOICE {{ $transaction->invoice_no }}</div>
        <div class="header-text">Sold To: 01 WALK IN</div>
        <div class="header-text">Address: </div>
        <div class="header-text">TIN: 1</div>

        <hr style="border-top: 1px dashed #000;">

        <table class="items-table" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left" style="width: 15%;">Qty</th>
                    <th class="text-left" style="width: 45%;">Description</th>
                    <th class="text-right" style="width: 20%;">U-Price</th>
                    <th class="text-right" style="width: 20%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $totalItems = 0; @endphp
                @foreach($transaction->items as $item)
                @php $totalItems += $item->qty; @endphp
                <tr>
                    <td colspan="4" class="text-center" style="padding-top: 5px;">{{ $item->product->barcode }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="vertical-align: top;">{{ $item->qty }}</td>
                    <td class="text-left" style="vertical-align: top; padding-right: 5px;">{{ $item->product->name }}</td>
                    <td class="text-right" style="vertical-align: top;">{{ number_format($item->price, 2) }}</td>
                    <td class="text-right" style="vertical-align: top;">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr style="border-top: 1px dashed #000;">

        <table class="summary-table">
            <tr>
                <td>TOTAL</td>
                <td class="text-right">{{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>TOTAL AMOUNT DUE</td>
                <td class="text-right">P{{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Cash</td>
                <td class="text-right">{{ number_format($transaction->cash_tendered, 2) }}</td>
            </tr>
            <tr>
                <td>CHANGE</td>
                <td class="text-right">{{ number_format($transaction->change, 2) }}</td>
            </tr>
        </table>

        <div class="text-center header-text" style="margin-top: 10px;">
            ** {{ $totalItems }} Slice(s) item(s)**<br>
            ** {{ $transaction->items->count() }} item(s)**
        </div>

        <table class="summary-table">
            <tr>
                <td colspan="2">SALES DETAILS ----------------- AMOUNT</td>
            </tr>
            <tr>
                <td>VATable Sales</td>
                <td class="text-right">{{ number_format($transaction->vatable_sales, 2) }}</td>
            </tr>
            <tr>
                <td>VAT Amount</td>
                <td class="text-right">{{ number_format($transaction->vat_amount, 2) }}</td>
            </tr>
            <tr>
                <td>VAT Exempt</td>
                <td class="text-right">{{ number_format($transaction->vat_exempt, 2) }}</td>
            </tr>
            <tr>
                <td>Zero-Rated</td>
                <td class="text-right">{{ number_format($transaction->zero_rated, 2) }}</td>
            </tr>
        </table>

        <div class="header-text" style="margin-top: 5px;">
            CASHIER: {{ strtoupper($transaction->cashier_name) }}  TrxNo. {{ rand(10000, 99999) }}<br>
            Store ID:PHV              Terminal No:03<br>
            <div class="text-center" style="margin-top: 5px;">
                {{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i:s A : m/d/Y') }}
            </div>
        </div>

        <hr style="border-top: 1px dashed #000;">

        <div class="text-center footer-text">
            PTUN:FP112025-080-0562780-00006<br>
            Date Issued: 11/11/2025<br>
            <br>
            THIS SERVES AS OFFICIAL SALES INVOICE
        </div>
        
        <div class="text-center" style="margin-top: 20px;">
            <a href="{{ route('pos.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #3490dc; color: #fff; text-decoration: none; border-radius: 5px; font-family: sans-serif;">Back to POS</a>
        </div>
    </div>
</body>
</html>

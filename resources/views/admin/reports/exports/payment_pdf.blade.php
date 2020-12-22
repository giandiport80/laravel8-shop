<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Report</title>
    <style type="text/css">
        table {
            width: 100%;
        }

        table tr td,
        table tr th {
            font-size: 10pt;
            text-align: left;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table th,
        td {
            border-bottom: 1px solid #ddd;
        }

        table th {
            border-top: 1px solid #ddd;
            height: 40px;
        }

        table td {
            height: 25px;
        }
    </style>
</head>

<body>
    <h2>Product Report</h2>
    <hr>
    <p>Period : {{ \General::datetimeFormat($startDate, 'd M Y') }} - {{ \General::datetimeFormat($endDate, 'd M Y') }}
    </p>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Gateway</th>
                <th>Payment Type</th>
                <th>Ref</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->code }}</td>
                <td>{{ \General::datetimeFormat($payment->created_at) }}</td>
                <td>{{ $payment->status }}</td>
                <td>{{ \General::priceFormat($payment->amount) }}</td>
                <td>{{ $payment->method }}</td>
                <td>{{ $payment->payment_type }}</td>
                <td>{{ $payment->token }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

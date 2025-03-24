<!DOCTYPE html>
<html>
<head>
    <title>RT Financial Analysis</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>RT Financial Analysis</h2>
    <table>
        <thead>
            <tr>
                <th>RT</th>
                <th>Total Income</th>
                <th>Total Expense</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rtData as $data)
            <tr>
                <td>{{ $data['rt'] }}</td>
                <td>{{ number_format($data['total_income'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['total_expense'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

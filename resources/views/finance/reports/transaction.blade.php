<!DOCTYPE html>
<html>
<head>
    <title>Financial Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Financial Report</h2>

    <h3>Income</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>RT</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incomes as $key => $income)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $income->transaction_date }}</td>
                <td>{{ $income->rt->name }}</td>
                <td>{{ $income->category->name }}</td>
                <td>{{ $income->name }}</td>
                <td>{{ $income->description }}</td>
                <td>{{ number_format($income->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Expense</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>RT</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $key => $expense)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $expense->transaction_date }}</td>
                <td>{{ $expense->rt->name }}</td>
                <td>{{ $expense->category->name }}</td>
                <td>{{ $expense->name }}</td>
                <td>{{ $expense->description }}</td>
                <td>{{ number_format($expense->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

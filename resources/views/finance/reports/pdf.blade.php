<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Laporan Keuangan</h2>
    <p>Periode: {{ $startDate }} - {{ $endDate }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>RT</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomes as $income)
            <tr>
                <td>{{ $income->transaction_date }}</td>
                <td>Pemasukan</td>
                <td>{{ $income->category->name ?? 'N/A' }}</td>
                <td>{{ $income->rt->name ?? 'N/A' }}</td>
                <td style="color: green;">+Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->transaction_date }}</td>
                <td>Pengeluaran</td>
                <td>{{ $expense->category->name ?? 'N/A' }}</td>
                <td>{{ $expense->rt->name ?? 'N/A' }}</td>
                <td style="color: red;">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total:</h3>
    <p>Total Pemasukan: <span style="color: green;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span></p>
    <p>Total Pengeluaran: <span style="color: red;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span></p>
    <p>Total Saldo: <span style="color: blue;">Rp {{ number_format($totalBalance, 0, ',', '.') }}</span></p>

</body>
</html>

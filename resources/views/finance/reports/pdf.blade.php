<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Laporan Keuangan</h2>
    <p><strong>Periode:</strong> {{ $startDate }} - {{ $endDate }}</p>

    <h3>Pemasukan</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>RT</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incomes as $income)
                <tr>
                    <td>{{ $income->id }}</td>
                    <td>{{ $income->name }}</td>
                    <td>{{ optional($income->category)->name }}</td>
                    <td>Rp {{ number_format($income->amount, 2) }}</td>
                    <td>{{ $income->description }}</td>
                    <td>{{ optional($income->rt)->name }}</td>
                    <td>{{ $income->transaction_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>RT</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $expense->id }}</td>
                    <td>{{ $expense->name }}</td>
                    <td>{{ optional($expense->category)->name }}</td>
                    <td>Rp {{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ optional($expense->rt)->name }}</td>
                    <td>{{ $expense->transaction_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Ringkasan</h3>
    <p><strong>Total Pemasukan:</strong> Rp {{ number_format($totalIncome, 2) }}</p>
    <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($totalExpense, 2) }}</p>
    <p><strong>Saldo Akhir:</strong> Rp {{ number_format($totalBalance, 2) }}</p>

</body>
</html>

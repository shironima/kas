<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($incomes as $income)
            <tr>
                <td>{{ $income->transaction_date }}</td>
                <td>{{ $income->category->name }}</td>
                <td>{{ number_format($income->amount, 0, ',', '.') }}</td>
                <td>{{ $income->description }}</td>
            </tr>
        @endforeach
        @foreach ($expenses as $expense)
            <tr>
                <td>{{ $expense->transaction_date }}</td>
                <td>{{ $expense->category->name }}</td>
                <td>-{{ number_format($expense->amount, 0, ',', '.') }}</td>
                <td>{{ $expense->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

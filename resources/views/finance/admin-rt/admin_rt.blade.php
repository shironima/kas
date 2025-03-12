<div class="card mt-6">
    <div class="card-header bg-purple-500 text-white flex justify-between items-center p-4">
        <h4 class="text-lg font-bold">Data Keuangan RT {{ auth()->user()->rt->name }}</h4>
    </div>
    <div class="card-body">
        <table id="financeTableRT" class="table table-striped w-full">
            <thead class="bg-purple-100">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($finances as $finance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $finance->name }}</td>
                        <td>{{ $finance->category->name ?? 'Tidak Ada Kategori' }}</td>
                        <td>Rp{{ number_format($finance->amount, 0, ',', '.') }}</td>
                        <td>{{ $finance->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($finance->transaction_date)->format('d M Y') }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit-finance"
                                data-id="{{ $finance->id }}"
                                data-name="{{ $finance->name }}"
                                data-category="{{ $finance->category->id ?? '' }}"
                                data-amount="{{ $finance->amount }}"
                                data-description="{{ $finance->description }}"
                                data-transaction_date="{{ $finance->transaction_date }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editFinanceModal">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

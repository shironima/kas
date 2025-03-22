<!-- resources/views/finance/admin-rt/partials/income-form.blade.php -->
<form action="{{ route('incomes.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nama Pemasukan</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="category_id">Kategori</label>
        <select name="category_id" id="category_id" class="form-control" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="amount">Jumlah</label>
        <input type="number" name="amount" id="amount" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="transaction_date">Tanggal Transaksi</label>
        <input type="date" name="transaction_date" id="transaction_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Deskripsi</label>
        <textarea name="description" id="description" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

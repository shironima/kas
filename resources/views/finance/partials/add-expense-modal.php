<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="addExpenseModalLabel">Tambah Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('expense.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="expenseName" class="form-label">Nama Pengeluaran</label>
                        <input type="text" class="form-control" id="expenseName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="expenseCategory" class="form-label">Kategori</label>
                        <select class="form-control" id="expenseCategory" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="expenseAmount" class="form-label">Jumlah (Rp)</label>
                        <input type="number" class="form-control" id="expenseAmount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="expenseDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="expenseDescription" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="expenseDate" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="expenseDate" name="transaction_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

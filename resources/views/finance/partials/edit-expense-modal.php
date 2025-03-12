<div id="editExpenseModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-red-500 text-white">
                <h5 class="modal-title">Edit Pengeluaran</h5>
            </div>
            <div class="modal-body">
                <form id="editExpenseForm">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <input type="text" name="category_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="amount" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="transaction_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
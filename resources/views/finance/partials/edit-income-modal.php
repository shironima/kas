<div class="modal fade" id="editIncomeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green-500 text-white">
                <h5 class="modal-title">Edit Pemasukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editIncomeForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="income_id">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="income_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" id="income_category" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="amount" id="income_amount" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="income_description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="transaction_date" id="income_date" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

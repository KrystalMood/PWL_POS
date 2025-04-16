<div class="modal-header">
    <h5 class="modal-title">Detail Stok</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 200px">ID Stok</th>
            <td>{{ $stok->stok_id }}</td>
        </tr>
        <tr>
            <th>Barang</th>
            <td>{{ $stok->barang->barang_nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td>{{ $stok->supplier->supplier_nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>User Input</th>
            <td>{{ $stok->user->username ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $stok->stok_jumlah }}</td>
        </tr>
        <tr>
            <th>Tanggal Masuk</th>
            <td>{{ $stok->stok_tanggal }}</td>
        </tr>
        <tr>
            <th>Tanggal Dibuat</th>
            <td>{{ $stok->created_at }}</td>
        </tr>
        <tr>
            <th>Terakhir Diupdate</th>
            <td>{{ $stok->updated_at }}</td>
        </tr>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<div class="modal-header">
    <h5 class="modal-title">Detail Transaksi Penjualan</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered table-sm">
        <tr><th>Kode Penjualan</th><td>{{ $data->penjualan_kode }}</td></tr>
        <tr><th>Pembeli</th><td>{{ $data->pembeli }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $data->penjualan_tanggal }}</td></tr>
    </table>

    <h5 class="mt-3">Detail Barang</h5>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $d)
                <tr>
                    <td>{{ $d->barang_id }}</td>
                    <td>{{ $d->barang_nama }}</td>
                    <td>{{ number_format($d->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ $d->jumlah }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Tidak ada detail</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
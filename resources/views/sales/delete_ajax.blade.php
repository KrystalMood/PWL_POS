<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Hapus Transaksi Penjualan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-delete-penjualan" action="{{ url('sales/' . $data->penjualan_id . '/delete_ajax') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!</h5>
                Apakah Anda yakin akan menghapus transaksi berikut?
            </div>
            <table class="table table-sm table-bordered">
                <tr><th>Kode</th><td>{{ $data->penjualan_kode }}</td></tr>
                <tr><th>Pembeli</th><td>{{ $data->pembeli }}</td></tr>
                <tr><th>Tanggal</th><td>{{ $data->penjualan_tanggal }}</td></tr>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
    </div>
</div>
<script>
$(function() {
    $('#form-delete-penjualan').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'DELETE',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $('#myModal').modal('hide');
                    Swal.fire({icon:'success',title:'Berhasil',text:res.message});
                    $('#tbl-penjualan').DataTable().ajax.reload();
                } else {
                    Swal.fire({icon:'error',title:'Gagal',text:res.message});
                }
            },
            error: function() { Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan'}); }
        });
    });
});
</script>
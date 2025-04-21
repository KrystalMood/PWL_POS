<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Hapus Data Stok</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-delete" action="{{ url('stok/' . $stok->stok_id . '/delete_ajax') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda ingin menghapus data stok berikut?
            </div>
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">Tanggal :</th>
                    <td class="col-9">{{ $stok->stok_tanggal }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Barang :</th>
                    <td class="col-9">{{ $stok->barang->barang_nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Supplier :</th>
                    <td class="col-9">{{ $stok->supplier->supplier_nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jumlah :</th>
                    <td class="col-9">{{ $stok->stok_jumlah }}</td>
                </tr>
            </table>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            rules: {},
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('#tbl-stok').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>
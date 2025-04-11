<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-delete" action="{{ url('barang/' . $barang->barang_id . '/delete_ajax') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda ingin menghapus data seperti di bawah ini?
            </div>
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">Kode Barang :</th>
                    <td class="col-9">{{ $barang->barang_kode }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Barang :</th>
                    <td class="col-9">{{ $barang->barang_nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Kategori :</th>
                    <td class="col-9">{{ $barang->kategori->kategori_nama }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Harga :</th>
                    <td class="col-9">{{ number_format($barang->harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Stok :</th>
                    <td class="col-9">{{ $barang->stok }}</td>
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
                            $('#tbl-barang').DataTable().ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

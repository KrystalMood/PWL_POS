<form action="{{ url('/kategori/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Data Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Download Template</label>
            <a href="{{ asset('template_kategori.xlsx') }}" class="btn btn-info btn-sm" download>
                <i class="fa fa-file-excel"></i>Download
            </a>
            <small id="error-kategori_kode" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Pilih File</label>
            <input type="file" name="file_kategori" id="file_kategori" class="form-control" required>
            <small id="error-file_kategori" class="error-text form-text text-danger"></small>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
        <button type="submit" class="btn btn-primary">Upload</button>
    </div>
</form>
<script>
    $(document).ready(function () {
        $("#form-import").validate({
            rules: {
                file_kategori: { required: true, extension: "xlsx" },
            },
            messages: {
                file_kategori: {
                    required: "File harus diupload",
                    extension: "File harus berformat Excel (.xlsx)"
                }
            },
            submitHandler: function (form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            if (typeof $('#tbl-kategori').DataTable !== 'undefined') {
                                $('#tbl-kategori').DataTable().ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            if (response.msgField) {
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menghubungi server. Silahkan coba lagi.'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script> 
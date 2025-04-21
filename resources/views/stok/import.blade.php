<form action="{{ route('stok.import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Data Stok</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Download Template</label>
            <a href="{{ asset('template_stok.xlsx') }}" class="btn btn-info btn-sm" download>
                <i class="fa fa-file-excel"></i>Download
            </a>
            <small id="error-template" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Pilih File</label>
            <input type="file" name="file_stok" id="file_stok" class="form-control" required>
            <small id="error-file_stok" class="error-text form-text text-danger"></small>
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
                file_stok: { required: true, extension: "xlsx|xls|csv" },
            },
            messages: {
                file_stok: {
                    required: "File harus diupload",
                    extension: "File harus berformat Excel (.xlsx, .xls, .csv)"
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
                            if (typeof tableStok !== 'undefined') {
                                tableStok.ajax.reload();
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
                                text: response.message || 'Gagal mengimpor data.'
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
                if (element.prop("type") === "file") {
                    error.insertAfter(element.closest('.form-group').find('.error-text'));
                } else {
                    element.closest('.form-group').append(error);
                }
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

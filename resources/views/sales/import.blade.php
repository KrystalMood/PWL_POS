<form action="{{ route('sales.import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Data Transaksi</h5>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="file">Pilih file Excel/CSV</label>
            <input type="file" name="file" id="file" class="form-control" required>
            <span class="text-danger" id="error-file"></span>
        </div>
        <div class="alert alert-info">
            <p><strong>Petunjuk:</strong></p>
            <ul>
                <li>Format data harus sesuai dengan template</li>
                <li>Kolom yang dibutuhkan: Tanggal, ID Barang, Jumlah, Harga</li>
                <li>Pastikan ID Barang sudah terdaftar dalam sistem</li>
            </ul>
            <p>Anda dapat <a href="{{ asset('template_transaksi.xlsx') }}" class="text-bold">mengunduh template disini</a>.</p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Import</button>
    </div>
</form>
<script>
    $("#form-import").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status){
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    if(response.msgField){
                        $.each(response.msgField, function(index, value){
                            $('#error-' + index).text(value);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                }
            },
            error: function(xhr){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server'
                });
            }
        });
    });
</script>

<div class="modal-header">
    <h5 class="modal-title">Tambah Stok</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="form-stok">
        @csrf
        <div class="form-group">
            <label for="barang_id">Barang</label>
            <select class="form-control" id="barang_id" name="barang_id">
                <option value="">- Pilih Barang -</option>
                @foreach($barang as $b)
                    <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                @endforeach
            </select>
            <small class="text-danger" id="error-barang_id"></small>
        </div>
        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select class="form-control" id="supplier_id" name="supplier_id">
                <option value="">- Pilih Supplier -</option>
                @foreach($supplier as $s)
                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                @endforeach
            </select>
            <small class="text-danger" id="error-supplier_id"></small>
        </div>
        <div class="form-group">
            <label for="stok_tanggal">Tanggal Masuk</label>
            <input type="date" class="form-control" id="stok_tanggal" name="stok_tanggal">
            <small class="text-danger" id="error-stok_tanggal"></small>
        </div>
        <div class="form-group">
            <label for="stok_jumlah">Jumlah</label>
            <input type="number" class="form-control" id="stok_jumlah" name="stok_jumlah" min="1">
            <small class="text-danger" id="error-stok_jumlah"></small>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="btn-submit-stok" type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('#form-stok').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('stok/ajax') }}",
                method: "POST",
                data: $('#form-stok').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#myModal').modal('hide');
                            $('#tbl-stok').DataTable().ajax.reload();
                        });
                    } else {
                        resetErrorField();
                        if (response.msgField) {
                            $.each(response.msgField, function(key, msgs) {
                                $('#error-' + key).text(msgs[0]);
                            });
                        }
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan data',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        function resetErrorField() {
            $('#error-barang_id').text('');
            $('#error-supplier_id').text('');
            $('#error-stok_tanggal').text('');
            $('#error-stok_jumlah').text('');
        }
    });
</script>
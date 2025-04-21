<div class="modal-header">
    <h5 class="modal-title">Edit Transaksi Penjualan</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="form-penjualan-edit">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="pembeli">Pembeli</label>
            <input type="text" class="form-control" id="pembeli" name="pembeli" value="{{ $data->pembeli }}">
            <small class="text-danger" id="error-pembeli"></small>
        </div>
        <div class="form-group">
            <label for="penjualan_tanggal">Tanggal Penjualan</label>
            <input type="date" class="form-control" id="penjualan_tanggal" name="penjualan_tanggal" value="{{ date('Y-m-d', strtotime($data->penjualan_tanggal)) }}">
            <small class="text-danger" id="error-penjualan_tanggal"></small>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
<script>
$(function() {
    $('#form-penjualan-edit').on('submit', function(e) {
        e.preventDefault();
        var url = "{{ url('sales/'.$data->penjualan_id.'/update_ajax') }}";
        $.ajax({
            url: url,
            method: "PUT",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    Swal.fire({icon:'success',title:'Berhasil',text:res.message}).then(()=>{
                        $('#myModal').modal('hide');
                        $('#tbl-penjualan').DataTable().ajax.reload();
                    });
                } else {
                    $('.text-danger').text('');
                    if(res.msgField){ $.each(res.msgField,function(k,v){ $('#error-'+k).text(v[0]); }); }
                    Swal.fire({icon:'error',title:'Gagal',text:res.message});
                }
            },
            error: function() { Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan'}); }
        });
    });
});
</script>
<div class="col-sm-12">
    <table class="table table-sm table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Stok Lalu</th>
                <th>Stok Kini</th>
                <th>Selisih</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php $nomor = 0;
            foreach ($tampildata->result_array() as $row) : $nomor++; ?>
            <tr>
                <td><?= $nomor; ?></td>
                <td><?= $row['koreksikodebarcode']; ?></td>
                <td><?= $row['namaproduk']; ?></td>
                <td><?= $row['koreksistoklalu']; ?></td>
                <td><?= $row['koreksistoksekarang']; ?></td>
                <td><?= $row['koreksiselisih']; ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapus('<?= $row['koreksiid'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-info" onclick="edit('<?= $row['koreksiid'] ?>')">
                        <i class="fa fa-tags"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function edit(id) {
    $('#idkoreksi').val(id);
    editdatakoreksiid();
}

function hapus(id) {
    Swal.fire({
        title: 'Hapus Data',
        html: `Yakin menghapus data koreksi stok ini ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('koreksistok/hapuskoreksistok') ?>",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            html: response.sukses
                        }).then((result) => {
                            if (result.value) {
                                tampildata_koreksi_stok();
                            }
                        })
                    }
                    if (response.error) {
                        Swal.fire(
                            'Error',
                            response.error,
                            'error'
                        );
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}
</script>
<table class="table table-sm table-bordered table-striped" style="font-size: 10pt;">
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold;">
                Daftar Harga Produk
            </th>
        </tr>
        <tr style="text-align: center;">
            <th>No</th>
            <th>Satuan</th>
            <th>Hrg.Modal (Rp)</th>
            <th>Hrg.Jual (Rp)</th>
            <th>Margin (%)</th>
            <th>Qty</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 0;
        foreach ($tampildata as $r) : $nomor++;
        ?>
        <tr>
            <td><?= $nomor; ?></td>
            <td><?= $r->satnama; ?></td>
            <td style="text-align: right;"><?= number_format($r->hargamodal, 2, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($r->hargajual, 2, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($r->margin, 2, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($r->jml_default, 2, ",", "."); ?></td>
            <td>
                <div class="btn-group mb-2 dropleft">
                    <button type="button" class="btn btn-pinterest btn-sm waves-effect waves-light dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" x-placement="left-start"
                        style="position: absolute; transform: translate3d(-2px, 0px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="#" onclick="edithargaproduk('<?= $r->id ?>')">
                            <i class="fa fa-tags"></i> Edit
                        </a>
                        <a class="dropdown-item" href="#" onclick="hapushargaproduk('<?= $r->id ?>')">
                            <i class="fa fa-trash-alt"></i> Hapus
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
function edithargaproduk(id) {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/produk/formedithargaproduk') ?>",
        data: {
            id: id
        },
        cache: false,
        success: function(response) {
            $('.viewmodal').show();
            $('.viewmodal').html(response);
            $('#viewmodaledit').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function hapushargaproduk(id) {
    Swal.fire({
        title: 'Hapus Harga Produk',
        text: `Yakin ini di hapus ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/produk/hapushargaproduk') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            icon: 'error',
                            position: 'top-center',
                            loader: true,
                        });
                    }
                    if (response.sukses) {
                        tampilhargaproduk();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            position: 'top-center',
                            loader: true,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}

function setdefault(id) {
    Swal.fire({
        title: 'Setting Default Harga Untuk Kasir',
        text: `Yakin ?`,
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
                url: "<?= site_url('admin/produk/settingdefaultharga') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        tampilhargaproduk();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            position: 'top-center',
                            loader: true,
                        });
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
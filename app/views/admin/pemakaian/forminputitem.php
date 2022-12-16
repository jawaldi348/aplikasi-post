<input type="hidden" name="aksi" id="aksi" value="insert">
<div class="table-responsive">
    <table class="table table-striped table-sm" style="width: 100%;">
        <tr style="background-color: #bdbbb5;">
            <th style="width: 3%;">#</th>
            <th style="width: 10%;">Kode</th>
            <th style="width: 25%;">Nama Produk</th>
            <th style="width: 5%;">Jml</th>
            <th style="width: 10%;">Harga Beli(Rp)</th>
            <th style="width: 10%;">Sub.Total(Rp)</th>
            <th style="width: 3%;">Aksi</th>
        </tr>
        <tr>
            <td>
                <i class="fa fa-check"></i>
            </td>
            <td>
                <input type="text" name="kodebarcode" id="kodebarcode" class="form-control form-control-sm">
            </td>
            <td>
                <h5 class="namaproduk"></h5>
                <input type="hidden" name="namaproduk" id="namaproduk">
            </td>
            <td>
                <input type="number" name="jml" id="jml" class="form-control-sm form-control" value="1">
            </td>
        </tr>
        <?php
        $nomor = 1;
        $totalsubtotal = 0;
        foreach ($datatemp->result_array() as $row) :
            $totalsubtotal += $row['subtotal'];
        ?>
        <tr>
            <td><?= $nomor++; ?></td>
            <td><?= $row['kode']; ?></td>
            <td><?= $row['namaproduk']; ?></td>
            <td><?= number_format($row['jml'], 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($row['hargabeli'], 2, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format($row['subtotal'], 2, ",", "."); ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" title="Hapus Item"
                    onclick="hapusitem('<?= $row['id'] ?>')">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
        <tr>
            <th style="background-color: #bdbbb5; text-align: center;" colspan="5">Total</th>
            <td style="text-align: right; font-weight: bold; font-size:16pt;">
                <?= number_format($totalsubtotal, 2, ",", "."); ?>
                <input type="hidden" name="totalsubtotal" id="totalsubtotal" value="<?= $totalsubtotal; ?>">
            </td>
        </tr>
    </table>
</div>
<script>
function hapusitem(id) {
    Swal.fire({
        title: 'Hapus',
        text: `Yakin hapus item ini ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, <i class="fa fa-trash"></i> Hapus !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('pemakaian/hapusitemtemp') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                            position: 'mid-center',
                            hideAfter: 2000,
                            stack: false
                        });
                        tampilforminputproduk();
                    }
                    if (response.error) {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            icon: 'error',
                            loader: true,
                            position: 'mid-center',
                            hideAfter: 2000,
                            stack: false
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        }
    })
}

function temppemakaian() {
    let kodebarcode = $('#kodebarcode').val();
    let namaproduk = $('#namaproduk').val();

    if (kodebarcode.length == 0) {
        cariproduk();
    } else {
        $.ajax({
            type: "post",
            url: "<?= site_url('pemakaian/simpantemp') ?>",
            data: {
                kodebarcode: kodebarcode,
                namaproduk: namaproduk,
                faktur: $('#faktur').val(),
                jml: $('#jml').val(),
                aksi: $('#aksi').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    tampilforminputproduk();
                    $('#tgl').prop('readonly', true);
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        loader: true,
                        position: 'mid-center',
                        hideAfter: 1000,
                        stack: false
                    });
                }
                if (response.error) {
                    $.toast({
                        heading: 'Error',
                        text: response.error,
                        icon: 'error',
                        loader: true,
                        position: 'mid-center',
                        hideAfter: 2000,
                        stack: false
                    });
                }

                if (response.banyakdata) {
                    $('.viewmodalcariproduk').html(response.banyakdata).show();
                    $('#modaldatacariproduk').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    }
}

function cariproduk() {
    $.ajax({
        url: "<?= site_url('pemakaian/cariproduk') ?>",
        type: 'post',
        data: {
            aksi: $('#aksi').val()
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodalcariproduk').html(response.data).show();
                $('#modalcariproduk').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

$(document).ready(function() {
    $('#kodebarcode').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            temppemakaian();
            // tampilforminputproduk();
        }
    });
});
</script>
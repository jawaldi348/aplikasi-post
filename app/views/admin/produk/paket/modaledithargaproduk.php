<div class="modal fade" id="modaledithargaprodukpaket" tabindex="-1" role="dialog"
    aria-labelledby="modaledithargaprodukpaketLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaledithargaprodukpaketLabel">Edit Harga & Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('admin/produk/paketupdatehargastok', ['class' => 'formupdatehargastok']); ?>
            <input type="hidden" name="idproduk" id="idproduk" value="<?= $idproduk; ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Kode Produk</label>
                    <input type="text" class="form-control-sm form-control" name="kodebarcode"
                        value="<?= $kodebarcode; ?>" id="kodebarcode" readonly>
                </div>
                <div class="form-group">
                    <label for="">Nama Paket</label>
                    <input type="text" class="form-control-sm form-control" name="namapaket" id="namapaket"
                        value="<?= $namaproduk; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="">Stok Tersedia</label>
                    <input type="text" class="form-control-sm form-control" name="stoktersedia" id="stoktersedia"
                        value="<?= $stok; ?>">
                </div>
                <div class="form-group">
                    <label for="">Harga Beli (Rp)</label>
                    <input type="text" class="form-control-sm form-control" name="txthargabeli" id="txthargabeli"
                        value="<?= $hargabeli; ?>">
                </div>
                <div class="form-group">
                    <label for="">Margin</label>
                    <input type="text" class="form-control-sm form-control" name="margin" id="margin"
                        value="<?= $margin; ?>">
                </div>
                <div class="form-group">
                    <label for="">Harga Jual (Rp)</label>
                    <input type="text" class="form-control-sm form-control" name="txthargajual" id="txthargajual"
                        value="<?= $hargajual; ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.formupdatehargastok').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Update Harga dan Stok',
            text: "Yakin data ini di update ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0aa81f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.btnsimpan').prop('disabled', true);
                        $('.btnsimpan').html(
                            `<i class="fa fa-spin fa-spinner"></i>`);
                    },
                    complete: function() {
                        $('.btnsimpan').prop('disabled', false);
                        $('.btnsimpan').html(`Simpan`);
                    },
                    success: function(response) {
                        if (response.sukses == 'Berhasil') {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        })

        return false;
    });
    //setting currency
    $('#stoktersedia').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });
    //setting currency
    $('#txthargabeli').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '2'
    });
    //setting currency
    $('#txthargajual').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '2'
    });
    //setting currency
    $('#margin').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '2'
    });
});

$(document).on('keyup', '#margin', function(e) {
    let margin = $(this).val();
    // let hargabeli = $('#txthargabeli').val();
    let konversi_hargabeli = $('#txthargabeli').autoNumeric('get');

    hitung_hargajual = parseFloat(konversi_hargabeli) + ((parseFloat(konversi_hargabeli) *
        parseFloat(margin)) / 100);

    $('#txthargajual').autoNumeric('set', hitung_hargajual);
});

$(document).on('keyup', '#txthargajual', function(e) {
    let hargajual = $(this).autoNumeric('get');
    let hargabeli = $('#txthargabeli').autoNumeric('get');

    let hitunglaba;
    hitunglaba = parseFloat(hargajual) - parseFloat(hargabeli);

    let margin;
    margin = (hitunglaba / hargabeli) * 100;

    $('#margin').autoNumeric('set', margin);
});
</script>
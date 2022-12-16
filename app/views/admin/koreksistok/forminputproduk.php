<?= form_open('koreksistok/simpankoreksi', ['class' => 'formsimpan']) ?>
<input type="hidden" name="idkoreksi" id="idkoreksi">
<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-2">
            <input type="hidden" name="koreksino" id="koreksino" value="<?= $koreksino; ?>">
            <input type="hidden" name="tgl" id="tgl" value="<?= $tgl; ?>">
            <input type="hidden" name="idpemasok" id="idpemasok" value="<?= $idpemasok; ?>">
            <label for="kode">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control form-control-sm" autocomplete="off">
            <div class="invalid-feedback errorkode">
            </div>
        </div>
        <div class="col-sm-2">
            <label for="namaproduk">Nama Produk</label>
            <input type="text" name="namaproduk" id="namaproduk" class="form-control form-control-sm" readonly>
        </div>
        <div class="col-sm-1">
            <label for="">Satuan</label>
            <input type="text" name="satuan" id="satuan" class="form-control form-control-sm" readonly>
        </div>
        <div class="col-sm-1">
            <label for="">Stok Lalu</label>
            <input type="text" name="stoklalu" id="stoklalu" class="form-control form-control-sm" readonly>
        </div>
        <div class="col-sm-1">
            <label for="">Stok Kini</label>
            <input type="text" name="stoksekarang" id="stoksekarang" class="form-control form-control-sm">
            <div class="invalid-feedback errorstoksekarang">
            </div>
        </div>
        <div class="col-sm-3">
            <label for="">Alasan</label>
            <input type="text" name="alasan" id="alasan" class="form-control form-control-sm">
        </div>
        <div class="col-sm-1">
            <label for="">Selisih</label>
            <input type="text" name="selisih" id="selisih" class="form-control form-control-sm" readonly>
        </div>

        <div class="col-sm-1">
            <label for="">#</label>
            <div class="input-group">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-sm btn-success btnsimpan">
                        <i class="fa fa-plus-square"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-sm btn-info btnreload" title="Reload Form">
                        <i class="fa fa-redo-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" name="hargabeli" id="hargabeli">
        <input type="hidden" name="hargajual" id="hargajual">
    </div>
</div>
<?= form_close(); ?>

<script>
function editdatakoreksiid() {
    let id = $('#idkoreksi').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('koreksistok/ambildatakoreksi') ?>",
        data: {
            idkoreksi: id
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                $('#kode').val(response.sukses.kode);
                $('#namaproduk').val(response.sukses.namaproduk);
                $('#satuan').val(response.sukses.satuan);
                $('#stoklalu').val(response.sukses.stoklalu);
                $('#stoksekarang').val(response.sukses.stoksekarang);
                $('#alasan').val(response.sukses.alasan);
                $('#selisih').val(response.sukses.selisih);
                $('#hargabeli').val(response.sukses.hargabeli);
                $('#hargajual').val(response.sukses.hargajual);
                $('#stoksekarang').focus();
            } else {
                tampilforminputproduk();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
$(document).ready(function() {
    $('#kode').focus();

    $('.btnreload').click(function(e) {
        e.preventDefault();
        tampilforminputproduk();
    });

    $('#stoksekarang').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            let stoksekarang = $(this).val();
            let stoklalu = $('#stoklalu').val();

            let selisih;
            selisih = parseInt(stoksekarang) - parseInt(stoklalu);
            $('#selisih').val(selisih);
        }
    });

    $('#stoksekarang').keyup(function(e) {
        let stoksekarang = $(this).val();
        let stoklalu = $('#stoklalu').val();

        let selisih;
        selisih = parseInt(stoksekarang) - parseInt(stoklalu);
        $('#selisih').val(selisih);
    });

    $('#kode').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            let kode = $('#kode').val();
            let namaproduk = $('#namaproduk').val();

            if (kode.length == 0) {
                alert('Silahkan Input Keyword Produk Yang di-Cari');
            } else {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('koreksistok/ambilproduk') ?>",
                    data: {
                        kode: kode,
                        namaproduk: namaproduk,
                        koreksino: $('#koreksino').val(),
                        tgl: $('#tgl').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {
                            $.toast({
                                heading: 'Error',
                                text: `${response.error}`,
                                icon: 'error',
                                loader: true,
                                loaderBg: '#9EC600',
                                position: 'top-center'
                            });
                        }

                        if (response.banyakdata) {
                            $('.viewmodal').html(response.banyakdata).show();
                            $('#modaldatacariproduk').modal('show');
                        }

                        if (response.sukses) {
                            let data = response.sukses;
                            $('#namaproduk').val(data.namaproduk);
                            $('#kode').val(data.kode);
                            $('#satuan').val(data.namasatuan);
                            $('#stoklalu').val(data.stoktersedia);
                            $('#hargabeli').val(data.hargabeli);
                            $('#hargajual').val(data.hargajual);

                            $('#stoksekarang').focus();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        }
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function(e) {
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                $('.btnsimpan').prop('disabled', true);
            },
            complete: function(e) {
                $('.btnsimpan').html('<i class="fa fa-plus-square"></i>');
                $('.btnsimpan').prop('disabled', false);
            },
            success: function(response) {
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: `${response.sukses}`,
                        icon: 'success',
                        loader: true,
                        loaderBg: '#9EC600',
                        position: 'top-center'
                    });
                    tampilforminputproduk();
                    tampildata_koreksi_stok();
                }

                if (response.error) {
                    if (response.error.kode) {
                        $('#kode').addClass('is-invalid');
                        $('.errorkode').html(response.error.kode)
                    } else {
                        $('#kode').removeClass('is-invalid');
                        $('.errorkode').html('');
                    }

                    if (response.error.stoksekarang) {
                        $('#stoksekarang').addClass('is-invalid');
                        $('.errorstoksekarang').html(response.error.stoksekarang)
                    } else {
                        $('#stoksekarang').removeClass('is-invalid');
                        $('.errorstoksekarang').html('');
                    }
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });
});
</script>
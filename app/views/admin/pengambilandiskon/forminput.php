<div class="col-lg-12">
    <div class="card border-light mb-1">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-primary"
                onclick="window.location='<?= site_url('pengambilandiskon/data') ?>'">
                <i class="fa fa-tasks"></i> Data Pengambilan
            </button>
        </div>
        <div class="card-body">
            <?= form_open('pengambilandiskon/simpandata', ['class' => 'formsimpan']) ?>
            <div class="pesan" style="display: none;"></div>
            <div class="form-group row">
                <label for="kodepengambilan" class="col-sm-4 col-form-label">Kode Pengambilan</label>
                <div class="col-sm-4">
                    <input type="text" autofocus autocomplete="off" class="form-control form-control-sm"
                        id="kodepengambilan" name="kodepengambilan" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="tglpengambilan" class="col-sm-4 col-form-label">Tgl. Pengambilan</label>
                <div class="col-sm-4">
                    <input type="date" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm"
                        id="tglpengambilan" name="tglpengambilan">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Pilih Jenis Pengambilan</label>
                <div class="col-sm-6">
                    <input type="radio" name="jenispengambilan" value="0" id="jenispengambilan0">
                    Perorangan&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="jenispengambilan" value="1" id="jenispengambilan1"> Keseluruhan
                    <div class="invalid-feedback errorjenispengambilan">
                    </div>
                </div>
            </div>
            <div class="form-group row tampilmember" style="display: none;">
                <label for="" class="col-sm-4 col-form-label">Cari Member</label>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="kodemember" id="kodemember"
                            readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" id="btncarimember">Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="namamember" id="namamember" readonly class="form-control-sm form-control">
                </div>
            </div>
            <div class="form-group row tampilmember" style="display: none;">
                <label for="" class="col-sm-4 col-form-label">Total Tabungan</label>
                <div class="col-sm-4">
                    <input type="text" name="totaltabungan" id="totaltabungan" class="form-control form-control-sm"
                        readonly>
                </div>
            </div>
            <div class="form-group row tampilmemberseluruh" style="display: none;">
                <label for="" class="col-sm-4 col-form-label">Total Tabungan</label>
                <div class="col-sm-4">
                    <input type="text" name="totaltabunganseluruh" id="totaltabunganseluruh"
                        class="form-control form-control-sm" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-sm btn-success btnsimpan">Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger btnbatal"><i class="fa fa-ban"></i>
                        Batal</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="modalcarimember" style="display: none;"></div>
<div class="modalcarimemberseluruh" style="display: none;"></div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function buatkodepengambilan() {
    let tgl = $('#tglpengambilan').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('pengambilandiskon/buatkodepengambilan') ?>",
        data: {
            tgl: tgl
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                $('#kodepengambilan').val(response.sukses);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function hapustemppengambilandiskon() {
    let kodepengambilan = $('#kodepengambilan').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('pengambilandiskon/batalinput') ?>",
        data: {
            kodepengambilan: kodepengambilan
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                return true;
            }
        },
        // error: function(xhr, ajaxOptions, thrownError) {
        //     alert(xhr.status + "\n" + xhr.responseText + "\n" +
        //         thrownError);
        // }
    });
}
$(document).ready(function() {
    buatkodepengambilan();

    $('#jenispengambilan0').click(function(e) {
        $('.tampilmember').fadeIn();
        $('.tampilmemberseluruh').fadeOut();
    });
    $('#jenispengambilan1').click(function(e) {
        $.ajax({
            type: "post",
            url: "<?= site_url('pengambilandiskon/tampilmemberdiskon') ?>",
            data: {
                kodepengambilan: $('#kodepengambilan').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    hapustemppengambilandiskon();
                    $('.tampilmember').fadeOut();
                    $('.tampilmemberseluruh').fadeIn();
                    $('.modalcarimemberseluruh').html(response.sukses).show();
                    $('#modalcarimemberseluruh').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });

    $('#sisatabungan').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#totaltabungan').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#totaltabunganseluruh').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#jumlahambil').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('#tglpengambilan').change(function(e) {
        e.preventDefault();
        buatkodepengambilan();
    });

    // Tombol Cari Member
    $('#btncarimember').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?= site_url('pengambilandiskon/carimemberdiskon') ?>",
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('.modalcarimember').html(response.sukses).show();
                    $('#modalcarimember').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });

    $('#jumlahambil').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            let totaltabungan = $('#totaltabungan').autoNumeric('get');
            let jumlahambil = $('#jumlahambil').autoNumeric('get');

            if (jumlahambil > totaltabungan) {
                alert('Maaf jumlah yang diambil tidak boleh melebih dari total tabungan yang ada !');
                $('#sisatabungan').val(0);
                $('#jumlahambil').val(0);
            } else {
                let sisatabungan = parseFloat(totaltabungan) - parseFloat(jumlahambil);
                $('#sisatabungan').autoNumeric('set', sisatabungan);
            }

        }
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        // let totaltabungan = $('#totaltabungan').autoNumeric('get');
        // let totaltabunganseluruh = $('#totaltabunganseluruh').autoNumeric('get');

        // if (totaltabungan == 0 || totaltabunganseluruh == 0) {
        //     $.toast({
        //         heading: 'Maaf',
        //         icon: 'error',
        //         text: 'Tabungan Bernilai 0, tidak bisa disimpan',
        //         position: 'bottom-right'
        //     });
        // } else {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                $('.btnsimpan').prop('disabled', true);
            },
            complete: function() {
                $('.btnsimpan').html('Simpan');
                $('.btnsimpan').prop('disabled', false);
            },
            success: function(response) {
                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        html: `${response.sukses}`
                    }).then((result) => {
                        if (result.value) {
                            window.location.reload();
                        }
                    })
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });

        // }
        return false;
    });

    $('.btnbatal').click(function(e) {
        e.preventDefault();
        hapustemppengambilandiskon();
        window.location.reload();
    });
});
</script>
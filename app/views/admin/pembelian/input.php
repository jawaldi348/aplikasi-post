<!-- DatePicker -->
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<!-- JQuery DatePicker -->
<script src="<?php echo base_url() ?>assets/plugins/timepicker/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>

<div class="row mt-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background-color: #851414; color:white; font-weight: bold;">

                <div class="d-flex justify-content-between">
                    <div>
                        Transaksi Pembelian
                    </div>
                    <div>
                        <?php
                        if ($this->session->userdata('idgrup') == 1) {
                        ?>
                        <button type="button" class="btn btn-sm btn-warning"
                            onclick="document.location='<?= site_url('beli/index') ?>'">
                            <i class="fa fa-backward"></i> Kembali
                        </button>
                        <?php
                        } else {
                        ?>
                        <button type="button" class="btn btn-sm btn-warning"
                            onclick="document.location='<?= site_url('k/home/index') ?>'">
                            <i class="fa fa-backward"></i> Kembali
                        </button>
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <?= form_open('beli/simpanfaktur', ['class' => 'formsimpanfaktur']) ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="faktur">No.Faktur Pembelian</label>
                            <input type="text" autofocus="autofocus" name="faktur" id="faktur"
                                class="form-control form-control-sm" placeholder="Faktur Pembelian">
                            <div class="invalid-feedback errorfaktur">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tglfaktur">Tgl.Faktur</label>
                            <input type="date" name="tglfaktur" id="tglfaktur" class="form-control form-control-sm"
                                value="<?= date('Y-m-d') ?>">
                            <div class="invalid-feedback errortglfaktur">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pemasok">Pemasok</label>
                            <div class="input-group">
                                <input type="text" name="namapemasok" id="namapemasok"
                                    class="form-control form-control-sm" placeholder="Pilih Pemasok" value="-" disabled>
                                <input type="hidden" name="idpemasok" id="idpemasok" class="form-control" value="0">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary btn-sm tombolcaripemasok" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm tomboltambahpemasok" type="button">
                                        <i class="fa fa-plus-square"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback errorpemasok">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">Aksi</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm btnsimpan" type="submit" data-toggle="tooltip"
                                        data-placement="top" title="Simpan Faktur">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-danger btn-sm btnhapustransaksi" type="button"
                                        data-toggle="tooltip" data-placement="top" title="Batalkan Transaksi">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?= form_close(); ?>

                <div class="row tampilforminput" style="display: none;">

                </div>
                <div class="row tampildatadetail" style="display: none;">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalforminputproduk" style="display: none;"></div>
<div class="viewmodalcarisatuan" style="display: none;"></div>
<script>
function buatfakturotomatis() {
    let tgl = $('#tglfaktur').val();
    $.ajax({
        type: "post",
        url: "<?= site_url('beli/buatfakturotomatis') ?>",
        data: {
            tgl: tgl
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                $('#faktur').val(response.sukses);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function tampilforminput() {
    let faktur = $('#faktur').val();
    let tglfaktur = $('#tglfaktur').val();
    $.ajax({
        type: 'post',
        url: "<?= site_url('beli/tampilforminput') ?>",
        data: {
            faktur: faktur,
            tglfaktur: tglfaktur
        },
        dataType: "json",
        beforeSend: function() {
            $('.tampilforminput').html(`<i class="fa fa-spin fa-spinner"></i>`).show();
        },
        success: function(response) {
            $('.tampilforminput').html(response.data).show();
            $('#kode').focus();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function datadetailpembelian() {
    let faktur = $('#faktur').val();
    let tglfaktur = $('#tglfaktur').val();
    $.ajax({
        type: 'post',
        url: "<?= site_url('beli/datadetailpembelian') ?>",
        data: {
            faktur: faktur,
            tglfaktur: tglfaktur
        },
        dataType: "json",
        beforeSend: function() {
            $('.tampildatadetail').html(`<i class="fa fa-spin fa-spinner"></i>`).show();
        },
        success: function(response) {
            $('.tampildatadetail').html(response.data).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function(e) {
    $('[data-toggle="tooltip"]').tooltip();
    buatfakturotomatis();
    datadetailpembelian();

    $('#tglfaktur').change(function(e) {
        e.preventDefault();
        buatfakturotomatis();
    });
    // Simpan Faktur
    $('.formsimpanfaktur').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            processData: false,
            beforeSend: function(e) {
                $('.btnsimpan').prop('disabled', 'disabled');
                $('.btnsimpan').html(`<i class="fa fa-spin fa-spinner"></i>`);
            },
            success: function(response) {

                if (response.error) {
                    if (response.error.faktur) {
                        $('#faktur').addClass('is-invalid');
                        $('.errorfaktur').html(response.error.faktur);
                    } else {
                        $('#faktur').removeClass('is-invalid');
                        $('#faktur').addClass('is-valid');
                        $('.errorfaktur').html('');
                    }

                    if (response.error.tglfaktur) {
                        $('#tglfaktur').addClass('is-invalid');
                        $('.errortglfaktur').html(response.error.tglfaktur);
                    } else {
                        $('#tglfaktur').removeClass('is-invalid');
                        $('#tglfaktur').addClass('is-valid');
                        $('.errortglfaktur').html('');
                    }




                }

                if (response.sukses) {
                    $('#faktur').removeClass('is-invalid');
                    $('#faktur').addClass('is-valid');
                    $('#tglfaktur').removeClass('is-invalid');
                    $('#tglfaktur').addClass('is-valid');
                    $('#namapemasok').removeClass('is-invalid');
                    $('#namapemasok').addClass('is-valid');

                    $('#faktur').prop('disabled', 'disabled');
                    $('#tglfaktur').prop('disabled', 'disabled');

                    $.toast({
                        heading: 'Information',
                        text: `${response.sukses}`,
                        icon: 'success',
                        loader: true,
                        loaderBg: '#9EC600',
                        position: 'bottom-center'
                    });

                    $('#kode').focus();
                    tampilforminput();
                }

            },
            complete: function() {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html(`<i class="fa fa-save"></i>`);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });

    // Cari Pemasok
    $('.tombolcaripemasok').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?= site_url('beli/caripemasok') ?>",
            dataType: "json",
            success: function(response) {
                $('.viewmodal').html(response.data).show();
                $('#modalcaripemasok').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    // Tombol Tambah Pemasok
    $('.tomboltambahpemasok').click(function(e) {
        e.preventDefault();

        // var top = window.screen.height - 600;
        // top = top > 0 ? top / 2 : 0;

        // var left = window.screen.width - 800;
        // left = left > 0 ? left / 2 : 0;

        // var url = '.././pemasok/index';
        // var uploadWin = window.open("<?= site_url('pemasok/index') ?>",
        //     "Struk Kasir",
        //     "width=800,height=600" + ",top=" + top +
        //     ",left=" + left);
        // uploadWin.moveTo(left, top);
        // uploadWin.focus();
        $.ajax({
            url: "<?= site_url('beli/tambahpemasok') ?>",
            dataType: "json",
            success: function(response) {
                $('.viewmodal').html(response.data).show();
                $('#modaltambah').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    // Batalkan Transaksi
    $('.btnhapustransaksi').click(function(e) {
        e.preventDefault();

        let faktur = $('input[name="faktur"]').val();

        if (faktur.length === 0) {
            $.toast({
                heading: 'Information',
                text: `Faktur belum terisi, tidak ada yang bisa dihapus !`,
                icon: 'info',
                loader: true,
                loaderBg: '#9EC600',
                position: 'top-center'
            });
        } else {

            Swal.fire({
                title: 'Batal Transaksi',
                html: `Yakin membatalkan transaksi dengan no.faktur <strong>${faktur}</strong> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: "<?= site_url('beli/bataltransaksi') ?>",
                        data: {
                            faktur: faktur
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
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: `${response.sukses}`,
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
                }
            })

        }
    });
});
</script>
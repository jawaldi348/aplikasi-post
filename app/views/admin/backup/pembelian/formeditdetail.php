<!-- DatePicker -->
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<!-- end -->
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="d-flex justify-content-between bg-success text-white">
            <div>
                <h4 class="card-header mt-0">Tambah Item Produk</h4>
            </div>
            <div>
                <button class="btn btn-sm btn-danger btntutup" type="button">
                    <i class="fa fa-window-close"></i> Tutup
                </button>
            </div>
        </div>
        <div class="card-body">
            <?= form_open('admin/pembelian/simpanitem', ['class' => 'forminputitem']) ?>
            <input type="hidden" name="faktur" id="faktur" value="<?= $faktur; ?>">
            <div class="msgdetail" style="display: none;"></div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="kode">Kode Barcode</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="kode" id="kode">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-info btncariproduk">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="kode">Nama Produk</label>
                    <input type="text" class="form-control" name="namaproduk" id="namaproduk" readonly="readonly"
                        data-container="body" data-toggle="popover" data-placement="bottom">
                    <input type="hidden" class="form-control form-control-sm" name="stoktersedia" id="stoktersedia">
                </div>
                <div class="col-sm-2">
                    <label for="kode">Satuan</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="namasatuan" id="namasatuan"
                            readonly>
                        <input type="hidden" class="form-control form-control-sm" name="idsatuan" id="idsatuan">
                        <input type="hidden" class="form-control form-control-sm" name="jmleceran" id="jmleceran">
                        <input type="hidden" class="form-control form-control-sm" name="idprodukharga"
                            id="idprodukharga">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-instagram btncarisatuan">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="kode">Hrg.Beli (Rp)</label>
                    <input type="text" class="form-control" name="hargabeli" id="hargabeli" style="cursor: pointer;"
                        readonly="readonly"
                        title="Klik 2x, jika ingin dirubah. Enter untuk Update. Tekan 'Esc' untuk membatalkan"
                        data-toggle="tooltip" data-placement="bottom">
                </div>
                <div class="col-sm-2">
                    <label for="kode">Hrg.Jual (Rp)</label>
                    <input type="text" class="form-control" name="hargajual" id="hargajual" readonly="readonly"
                        style="cursor: pointer;"
                        title="Klik 2x, jika ingin dirubah. Enter untuk Update. Tekan 'Esc' untuk membatalkan"
                        data-toggle="tooltip" data-placement="bottom">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <label for="kode">Expired Date</label>
                    <input type="text" class="form-control" name="ed" id="ed" placeholder="Isi, Jika Ada"
                        readonly="readonly">
                </div>
                <div class="col-sm-3">
                    <label for="kode">Jumlah Beli</label>
                    <input type="number" class="form-control" name="jmlbeli" id="jmlbeli" value="1">
                </div>
                <div class="col-sm-2">
                    <label for="kode">Aksi</label>
                    <div class="input-group">
                        <button type="submit" class="btn btn-success waves-effect waves-light btnadditem">
                            <i class="fa fa-plus-circle"></i> Add
                        </button>
                    </div>
                </div>

            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script>
function tampilformeditdetail() {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/pembelian/formeditdetail') ?>",
        data: {
            faktur: $('#faktur').val()
        },
        beforeSend: function() {
            $('.viewformeditdetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            $('.viewformeditdetail').html(response).show();
            $('#kode').focus();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampildatadetail() {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/pembelian/editdatadetail') ?>",
        data: {
            faktur: $('#faktur').val()
        },
        beforeSend: function() {
            $('.viewtampildatadetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            $('.viewtampildatadetail').html(response).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    $('#ed').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        month: true
    });
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $('.btntutup').click(function(e) {
        e.preventDefault();
        $('.viewformeditdetail').fadeOut();
    });
    $('.btncariproduk').click(function(e) {
        $.ajax({
            url: ".././cariproduk",
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modalcariproduk').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });


    $('#kode').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            let kode = $(this).val();
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/pembelian/detailproduk') ?>",
                data: {
                    kode: kode
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $('#namaproduk').val(response.sukses.namaproduk);
                        $('#namaproduk').attr('data-content', response.sukses.namaproduk);
                        $('#stoktersedia').val(response.sukses.stoktersedia);
                        $('#idsatuan').val(response.sukses.idsatuan);
                        $('#namasatuan').val(response.sukses.namasatuan);
                        $('#jmleceran').val(response.sukses.jmleceran);
                        $('#hargabeli').val(response.sukses.hargabeli);
                        $('#hargajual').val(response.sukses.hargajual);
                    } else {
                        $.toast({
                            heading: 'Maaf',
                            text: response.error,
                            showHideTransition: 'slide',
                            icon: 'warning',
                            position: 'bottom-center'
                        });
                        tampilforminputdetail();
                    }

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    // cari satuan
    $('.btncarisatuan').click(function(e) {
        e.preventDefault();

        $.ajax({
            type: 'post',
            data: {
                kode: $('#kode').val()
            },
            url: "<?= site_url('admin/pembelian/carisatuan') ?>",
            cache: false,
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modalcarisatuan').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    // Untuk mengupdate harga
    $("#hargabeli").dblclick(function() {
        $(this).removeAttr('readonly', 'readonly');
    });
    $("#hargajual").dblclick(function() {
        $(this).removeAttr('readonly', 'readonly');
    });


    $('#hargabeli').keydown(function(e) {
        if (e.keyCode == 13) {
            let hargabeli = $(this).val();
            let kode = $('#kode').val();
            let idsatuan = $('#idsatuan').val();

            if (kode.length == 0) {
                $.toast({
                    heading: 'Information',
                    text: 'Kode Barcode masih kosong',
                    showHideTransition: 'slide',
                    icon: 'warning'
                });
            } else {
                Swal.fire({
                    title: 'Update Harga Beli',
                    text: "Yakin mengubah harga beli dari produk ini ?",
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
                            url: "<?= site_url('admin/pembelian/updatehargabeliproduk') ?>",
                            data: {
                                hargabeli: hargabeli,
                                kode: kode,
                                idsatuan: idsatuan,
                                idprodukharga: $('#idprodukharga').val()
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.error) {
                                    $.toast({
                                        heading: 'Information',
                                        text: response.error,
                                        showHideTransition: 'slide',
                                        icon: 'error',
                                        position: 'top-center'
                                    });
                                    $('#hargabeli').attr('readonly', 'readonly');
                                    $('#hargabeli').val(response.hargabeli);
                                }
                                if (response.sukses) {
                                    $.toast({
                                        heading: 'Berhasil',
                                        text: response.sukses,
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        position: 'top-center'
                                    });
                                    $('#hargabeli').attr('readonly', 'readonly');
                                    $('#hargabeli').val(response.hargabeli);
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
            return false;
        }
    });

    $('#hargajual').keydown(function(e) {
        if (e.keyCode == 13) {
            let hargajual = $(this).val();
            let kode = $('#kode').val();
            let idsatuan = $('#idsatuan').val();

            if (kode.length == 0) {
                $.toast({
                    heading: 'Information',
                    text: 'Kode Barcode masih kosong',
                    showHideTransition: 'slide',
                    icon: 'warning'
                });
            } else {
                Swal.fire({
                    title: 'Update Harga Jual',
                    text: "Yakin mengubah harga jual dari produk ini ?",
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
                            url: "<?= site_url('admin/pembelian/updatehargajualproduk') ?>",
                            data: {
                                hargajual: hargajual,
                                kode: kode,
                                idsatuan: idsatuan,
                                idprodukharga: $('#idprodukharga').val()
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.error) {
                                    $.toast({
                                        heading: 'Information',
                                        text: response.error,
                                        showHideTransition: 'slide',
                                        icon: 'error',
                                        position: 'top-center'
                                    });
                                    $('#hargajual').attr('readonly', 'readonly');
                                    $('#hargajual').val(response.hargajual);
                                }
                                if (response.sukses) {
                                    $.toast({
                                        heading: 'Berhasil',
                                        text: response.sukses,
                                        showHideTransition: 'slide',
                                        icon: 'success',
                                        position: 'top-center'
                                    });
                                    $('#hargajual').attr('readonly', 'readonly');
                                    $('#hargajual').val(response.hargajual);
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
            return false;
        }
    });

    $('#hargabeli').keydown(function(e) {
        if (e.keyCode == 27) {
            $(this).attr('readonly', 'readonly');
        }
    });
    $('#hargajual').keydown(function(e) {
        if (e.keyCode == 27) {
            $(this).attr('readonly', 'readonly');
        }
    });


    // Simpan Item 
    $('.forminputitem').submit(function(e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function(e) {
                $('.btnadditem').attr('disabled', 'disabled');
                $('.btnadditem').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function(e) {
                $('.btnadditem').removeAttr('disabled');
                $('.btnadditem').html('<i class="fa fa-plus-circle"></i> Add');
            },
            success: function(response) {
                if (response.error) {
                    $('.msgdetail').html(response.error).show();
                } else {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        showHideTransition: 'slide',
                        icon: 'success',
                        position: 'top-center'
                    });
                    tampilformeditdetail();
                    tampildatadetail();
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
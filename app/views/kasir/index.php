<div class="col-md-12 col-lg-12 col-xl-2">
    <div class="card bg-light mb-3">
        <div class="card-header bg-primary text-white">Barcode Produk</div>
        <div class="card-body">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="kode" id="kode" autofocus
                    placeholder="Kode Barcode" autocomplete="off">
                <input type="hidden" name="jualfaktur" id="jualfaktur" value="<?= $jualfaktur; ?>">
                <button type="button" class="btn btn-info btn-sm btn-block btncariproduk" data-toggle="tooltip"
                    data-placement="top" title="F2 (Cari Produk)">
                    <i class="fa fa-search"></i> Cari
                </button>
            </div>
            <div class="form-group">
                <label for="">Qty</label>
                <input type="number" value="1" name="jml" id="jml" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Diskon(%)</label>
                <input type="text" value="0" name="dispersen" id="dispersen" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Diskon(Rp)</label>
                <input type="text" value="0" name="disuang" id="disuang" class="form-control">
            </div>
        </div>
    </div>
</div>
<div class="viewmodalgantisatuan" style="display: none;"></div>
<div class="col-md-12 col-lg-12 col-xl-10">
    <div class="card bg-light mb-3">
        <div class="card-body">
            <div class="viewtampildetailtemp" style="display:none;"></div>
        </div>
    </div>
    <div class="card bg-light mb-3">
        <div class="card-header bg-success text-white"">Proses</div>
        <div class=" card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card bg-light mb-3">
                        <div class="card-header bg-info text-white"">Add Member</div>
                        <div class=" card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="kodemember"
                                        id="kodemember" placeholder="Kode Member" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info btncarimember">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="text" name="namamember" id="namamember" placeholder="Nama Member"
                                    class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <p>
                        <button type="button" class="btn btn-sm btn-danger waves-effect waves-light"
                            id="btnbataltransaksi">
                            Batalkan Transaksi
                        </button>
                    </p>
                    <p>
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"
                            id="btnselesaitransaksi">
                            Selesai Transaksi & Pembayaran (F4)
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalpembayaran" style="display: none;"></div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function cariproduk() {
    $.ajax({
        url: "<?= site_url('kasir/cariproduk') ?>",
        success: function(response) {
            $('.viewmodal').html(response).show();
            $('#modalcariproduk').on('shown.bs.modal', function(e) {
                $('[type="search"]').focus();
            });
            $('#modalcariproduk').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    $(this).keydown(function(e) {
        if (e.keyCode == 112) {
            e.preventDefault();
            $('#kode').focus();
        }
    });

    tampildatatemppenjualan();
    $('#disuang').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#dispersen').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('[data-toggle="tooltip"]').tooltip();

    $('#kode').keydown(function(e) {
        if (e.keyCode == 9) {
            e.preventDefault();
            $('#jml').focus();
        }
        if (e.keyCode == 113) {
            e.preventDefault();
            cariproduk();
        }
    });

    $('#jml').keydown(function(e) {
        if (e.keyCode == 112) {
            e.preventDefault();
            $('#kode').focus();
        }
    });

    $('#btnbataltransaksi').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: `Batal Transaksi`,
            text: `Yakin membatalkan transaksi ?`,
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
                    url: "<?= site_url('kasir/bataltransaksi') ?>",
                    data: {
                        faktur: $('#jualfaktur').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {
                            $.toast({
                                heading: 'Maaf',
                                text: response.error,
                                showHideTransition: 'slide',
                                icon: 'error',
                                position: 'top-right'
                            });
                        }
                        if (response.sukses) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: response.sukses,
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                window.location.reload();
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
    });

    $('#btnselesaitransaksi').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?= site_url('kasir/pembayaran') ?>",
            data: {
                faktur: $('#jualfaktur').val(),
                kodemember: $('#kodemember').val(),
                namamember: $('#namamember').val(),
                total_subtotal: $('#total_subtotal').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('.viewmodalpembayaran').html(response.sukses).show();
                    $('#modalpembayaran').on('shown.bs.modal', function(e) {
                        $('#jumlahuang').focus();
                    });
                    $('#modalpembayaran').modal('show');
                } else {
                    $.toast({
                        heading: 'Maaf',
                        text: response.error,
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'top-right'
                    });
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

    });

    //Cari Produk berdasarkan Kode Barcode
    $('#kode').keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            let kode = $(this).val();
            let diskonuang = $('#disuang').val();
            $.ajax({
                type: "post",
                url: "<?= site_url('kasir/detailproduk') ?>",
                data: {
                    kode: kode,
                    faktur: $('#jualfaktur').val(),
                    jml: $('#jml').val(),
                    dispersen: $('#dispersen').val(),
                    disuang: diskonuang.replace(",", "")
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Sukses',
                            text: response.sukses,
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'bottom-right'
                        });
                        tampildatatemppenjualan();
                        $('#kode').val('');
                        $('#kode').focus();
                        $('#jml').val('1');
                        $('#dispersen').val('0.00');
                        $('#disuang').val('0.00');
                    } else {
                        $.toast({
                            heading: 'Maaf',
                            text: response.error,
                            showHideTransition: 'slide',
                            icon: 'warning',
                            position: 'bottom-center'
                        });
                        $('#kode').val('');
                        $('#kode').focus();
                        $('#jml').val('1');
                        $('#dispersen').val('0.00');
                        $('#disuang').val('0.00');
                        // tampilforminputdetail();
                    }

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

        if (e.keyCode == 115) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "<?= site_url('kasir/pembayaran') ?>",
                data: {
                    faktur: $('#jualfaktur').val(),
                    kodemember: $('#kodemember').val(),
                    namamember: $('#namamember').val(),
                    total_subtotal: $('#total_subtotal').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $('.viewmodalpembayaran').html(response.sukses).show();
                        $('#modalpembayaran').on('shown.bs.modal', function(e) {
                            $('#jumlahuang').focus();
                        })
                        $('#modalpembayaran').modal('show');
                    } else {
                        $.toast({
                            heading: 'Maaf',
                            text: response.error,
                            showHideTransition: 'slide',
                            icon: 'error',
                            position: 'top-right'
                        });
                    }

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    //Cari Produk
    $('.btncariproduk').click(function(e) {
        e.preventDefault();
        cariproduk();
    });

    // Cari Member
    $('.btncarimember').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('kasir/carimember') ?>",
            success: function(response) {
                $('.viewmodal').html(response).show();
                const element = document.querySelector('#modalcarimember');
                element.classList.add('animated', 'zoomIn');
                $('#modalcarimember').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});

function tampildatatemppenjualan() {
    $.ajax({
        type: "post",
        url: "<?= site_url('kasir/tampildatatemp') ?>",
        data: {
            jualfaktur: $('#jualfaktur').val()
        },
        beforeSend: function() {
            $('.viewtampildetailtemp').html('<i class="fa fa-spin fa-spinner"></i> Tunggu').show();
        },
        success: function(response) {
            $('.viewtampildetailtemp').html(response).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
</script>
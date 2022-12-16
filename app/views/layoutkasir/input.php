<div class="row mt-1">
    <div class="col-lg-12">
        <div class="card border-primary mb-1">
            <div class="card-header bg-primary text-white">
                <?php
                if ($this->session->userdata('idgrup') == 1) {
                    echo '<button type="button" class="btn btn-sm btn-warning" onclick="kembali();">
                                    <i class="fa fa-fast-backward"></i> Kembali
                                </button>';
                } else {
                    echo '<button type="button" class="btn btn-sm btn-warning" onclick="kembalikasir();">
                                    <i class="fa fa-fast-backward"></i> Kembali
                                </button>';
                }
                ?>
                Kasir Penjualan &nbsp;&nbsp;&nbsp;&nbsp;
                <?= date('D, d-m-Y') ?>&nbsp;&nbsp;&nbsp;(<span id="jam"></span>&nbsp;<span
                    id="menit"></span>&nbsp;<span id="detik"></span>)
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Faktur :</label>
                            <input type="text" name="faktur" id="faktur" class="form-control form-control-sm"
                                value="<?= $jualfaktur; ?>" readonly>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Kode Pel :</label>
                                    <input type="text" name="kodemember" id="kodemember" readonly="readonly"
                                        class="form-control form-control-sm" placeholder="Kode Member">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Nama Pelanggan :</label>
                                    <input type="text" name="namamember" id="namamember" readonly="readonly"
                                        class="form-control form-control-sm" placeholder="Nama Member">
                                    <input type="hidden" name="diskonmember" id="diskonmember">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-5" style="text-align: center;">
                        <img src="<? //= base_url('assets\images\toko-ucu.png') 
                                    ?>" style="width: 75%;">
                    </div> -->
                    <div class="col-sm-8" style="font-size: 11pt;">
                        <div class="alert alert-info">
                            Keterangan :
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul>
                                        <li><strong>F2 :</strong>&nbsp;Ambil Data Terakhir</li>
                                        <li><strong>F3 :</strong>&nbsp;Cari Member</li>
                                        <li><strong>F4 :</strong>&nbsp;Hapus Transaksi</li>
                                        <li><Strong>F11 :</Strong>&nbsp;Full Screen</li>
                                        <li><strong>alt+R :</strong>&nbsp;Reload Data</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul>
                                        <li><strong>F8 :</strong>&nbsp;Pembayaran</li>
                                        <li><strong>F9 :</strong>&nbsp;Tahan Transaksi</li>
                                        <li><strong>F10 :</strong>&nbsp;Lihat Transaksi diTahan</li>
                                        <li><strong>CTRL+F7 :</strong>&nbsp;Pembayaran Tabungan Member</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <!-- Data Item Detail Temp Penjualan -->
                        <!-- <div class="tampilsisauang" style="text-align: center; color:blue; font-weight: bold;"></div> -->
                        <div class="viewtampildetailtemp" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalgantisatuan" style="display: none;"></div>
<div class="viewmodalpembayaran" style="display: none;"></div>
<div class="viewmodaltransaksiditahan" style="display: none;"></div>
<div class="viewModalGantiHarga" style="display:none;"></div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function kembali() {
    window.location.href = ("<?= site_url('admin/penjualan/index') ?>");
}

function kembalikasir() {
    window.location.href = ("<?= site_url('k/home/index') ?>");
}

function tampildatatemppenjualan() {
    $.ajax({
        type: "post",
        url: "<?= site_url('kasir/tampildatatemp') ?>",
        data: {
            jualfaktur: $('#faktur').val(),
            diskonmember: $('#diskonmember').val()
        },
        beforeSend: function() {
            $('.viewtampildetailtemp').html('<i class="fa fa-spin fa-spinner"></i> Tunggu').show();
        },
        success: function(response) {
            $('.viewtampildetailtemp').html(response).show();
            $('#kode').focus();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
$(document).ready(function() {
    tampildatatemppenjualan();

    $('#kodemember').click(function(e) {
        e.preventDefault();
        $(this).prop('readonly', false);
    });
    $('#kodemember').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            let kodemember = $(this).val();

            $.ajax({
                type: "post",
                url: "<?= site_url('kasir/detaildatamember') ?>",
                data: {
                    kodemember: kodemember
                },
                dataType: "json",
                cache: false,
                success: function(response) {
                    if (response.sukses) {
                        $('#kodemember').prop('readonly', true);
                        $('#namamember').val(response.sukses.namamember);
                        $('#diskonmember').val(response.sukses.diskonmember);
                        $('#tabunganmember').val(response.sukses.tabunganmember);
                        tampildatatemppenjualan();
                    }
                    if (response.error) {
                        $.toast({
                            heading: 'Maaf',
                            text: response.error,
                            showHideTransition: 'slide',
                            icon: 'error',
                            position: 'top-right'
                        });
                        $('#kodemember').val('');
                        $('#namamember').val('');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    $(this).keydown(function(e) {
        if (e.keyCode == 114) { //Press F3 cari Member
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
        }
    });

    $(this).keydown(function(e) {
        if (e.keyCode == 119) { //Press F8
            e.preventDefault();

            transaksipembayaran();
        }
    });

    $(this).keydown(function(e) {
        if (e.keyCode == 115) { // Press F4
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
                            faktur: $('#faktur').val()
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses) {
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'success',
                                    title: response.sukses,
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: true,
                                }).then((result) => {
                                    window.location.reload();
                                })
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
                            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                thrownError);
                        }
                    });
                }
            })
        }
    });

    //Holding Transaksi Press F9
    $(this).keydown(function(e) {
        if (e.keyCode == 120) {
            e.preventDefault();
            holdingtransaksi();
        }
    });

    // Menampilkan Transaksi di Tanan F10
    $(this).keydown(function(e) {
        if (e.keyCode == 121) {
            e.preventDefault();
            $.ajax({
                url: "<?= site_url('kasir/data-transaksi-ditahan') ?>",
                dataType: "json",
                success: function(response) {
                    $('.viewmodaltransaksiditahan').html(response.data).show();
                    $('#modaltransaksiditahan').modal('show');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    // Pembayaran dengan tabungan member CTRL+F7
    $(this).keydown(function(e) {
        if (e.ctrlKey && e.keyCode == 118) {
            e.preventDefault();
            let kodemember = $('#kodemember').val();
            let pembulatan = $('#pembulatan').autoNumeric('get');
            if (kodemember.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sorry !',
                    html: 'Maaf, silahkan pilih member terlebih dahulu'
                });
            } else {
                Swal.fire({
                    title: 'Pembayaran Menggunakan Tabungan Point',
                    text: "Yakin dilanjutkan ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "post",
                            url: "<?= site_url('kasir/pembayaranmember') ?>",
                            data: {
                                kodemember: kodemember,
                                pembulatan: pembulatan,
                                faktur: $('#faktur').val(),
                                kodemember: $('#kodemember').val(),
                                total_kotor: $('#total_kotor').val(),
                                total_bersih_semua: $('#total_bersih_semua')
                                    .autoNumeric('get'),
                                dispersensemua: $('#dispersensemua').autoNumeric('get'),
                                disuangsemua: $('#disuangsemua').autoNumeric('get'),
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.sukses) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        html: response.sukses
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Maaf',
                                        html: response.error
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
        }
    });

    $(this).keydown(function(e) {
        if (e.ctrlKey && e.keyCode == 68) {
            e.preventDefault();
            $('#dispersensemua').focus();
        }
    });
});

// Transaksi Pembayaran 
function transaksipembayaran() {
    $.ajax({
        type: "post",
        url: "<?= site_url('kasir/pembayaran') ?>",
        data: {
            faktur: $('#faktur').val(),
            kodemember: $('#kodemember').val(),
            namamember: $('#namamember').val(),
            total_kotor: $('#total_kotor').val(),
            total_bersih_semua: $('#total_bersih_semua').autoNumeric('get'),
            pembulatan: $('#pembulatan').autoNumeric('get'),
            dispersensemua: $('#dispersensemua').autoNumeric('get'),
            disuangsemua: $('#disuangsemua').autoNumeric('get'),
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
}

// Holding Transaksi
function holdingtransaksi() {
    let faktur = $('#faktur').val();
    let kodemember = $('#kodemember').val();
    Swal.fire({
        title: 'Tahan Transaksi',
        text: `Yakin transaksi faktur ${faktur} di tahan ?`,
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
                url: "<?= site_url('kasir/holdingtransaksi') ?>",
                data: {
                    faktur: faktur,
                    kodemember: kodemember,
                    total_subtotal: $('#pembulatan').autoNumeric('get')
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi berhasil ditahan',
                            // text: 'Something went wrong!',
                            // footer: '<a href>Why do I have this issue?</a>'
                        }).then((result) => {
                            window.location.reload();
                        });
                    } else {
                        $.toast({
                            heading: 'Maaf',
                            text: response.error,
                            showHideTransition: 'slide',
                            icon: 'error',
                            position: 'top-center'
                        });
                    }
                }
            });
        }
    })
}

window.setTimeout("waktu()", 1000);

function waktu() {
    var waktu = new Date();
    setTimeout("waktu()", 1000);
    document.getElementById("jam").innerHTML = waktu.getHours() +
        ` : `;
    document.getElementById("menit").innerHTML = waktu.getMinutes() +
        ` : `;;
    document.getElementById("detik").innerHTML = waktu.getSeconds();
}
</script>
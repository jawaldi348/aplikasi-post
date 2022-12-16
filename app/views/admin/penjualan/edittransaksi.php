<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card border-info mb-3">

            <div class="card-header bg-info text-white"> Edit Transaksi &nbsp;&nbsp;&nbsp;&nbsp;
                <?= date('D, d-m-Y') ?>&nbsp;&nbsp;&nbsp;(<span id="jam"></span>&nbsp;<span
                    id="menit"></span>&nbsp;<span id="detik"></span>)
                <button type="button" class="btn btn-warning"
                    onclick="window.location.href=('<?= site_url('admin/penjualan/all-data') ?>')">
                    &laquo; Kembali
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2" style="font-size: 9pt;">
                        <div class="form-group">
                            <label for="">Faktur :</label>
                            <input type="text" name="faktur" id="faktur" class="form-control form-control-sm"
                                value="<?= $jualfaktur; ?>" readonly>
                            <input type="hidden" name="jualtgl" id="jualtgl" class="form-control form-control-sm"
                                value="<?= $jualtgl; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Member :</label>
                            <input type="text" name="kodemember" id="kodemember" readonly="readonly"
                                class="form-control form-control-sm" placeholder="Kode Member"
                                value="<?= $jualmemberkode; ?>">
                        </div>
                    </div>

                    <div class="col-sm-2" style="font-size: 9pt;">
                        <div class="form-group">
                            <label for="">Nama Member :</label>
                            <input type="text" name="namamember" id="namamember" value="<?= $membernama; ?>"
                                readonly="readonly" class="form-control form-control-sm" placeholder="Nama Member">
                            <input type="hidden" name="diskonmember" id="diskonmember" value="<?= $diskonmember; ?>">
                        </div>
                    </div>

                    <div class="col-sm-8" style="font-size: 9pt;">
                        <span>Tgl.Faktur : <?= "<strong>$tanggaljual</strong>"; ?></span>
                        <?php if ($statusbayar == 'M') : ?>
                        <div class="alert alert-danger">
                            <h4 class="alert-heading"><i class="fa fa-ban"></i> Maaf !</h4>
                            <p>
                                Transaksi ini di bayar dengan pembayaran yang menggunakan tabungan member. Jadi tidak
                                bisa dilakukan <strong>edit, penambahan item ataupun pengurangan item</strong>
                            </p>
                        </div>
                        <?php else : ?>
                        <div class="alert alert-info">
                            Keterangan :
                            <div class="row">
                                <div class="col-sm-2">
                                    <ul>
                                        <li><strong>F2 :</strong>&nbsp;Ambil Data Terakhir</li>
                                        <li><strong>F8 :</strong>&nbsp;Pembayaran</li>
                                    </ul>
                                </div>
                                <div class="col-sm-2">
                                    <ul>
                                        <li><Strong>F11 :</Strong>&nbsp;Full Screen</li>
                                        <li><strong>alt+R :</strong>&nbsp;Reload Data</li>
                                    </ul>
                                </div>
                                <div class="col-sm-8">
                                    <ul>
                                        <li><Strong>F9 :</Strong>&nbsp;Tahan Transaksi</li>
                                        <?php
                                            //if ($statusbayar == 'H') :
                                            ?>
                                        <li>
                                            <div class="alert alert-warning"><strong>Ini Adalah Faktur Transaksi Di-Edit
                                                    /
                                                    Di-Tahan.
                                                    Jadi Pastikan Sudah Melakukan Pembayaran (Press F8).</strong></div>
                                        </li>
                                        <?php //endif; 
                                            ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-12">
                        <!-- Data Item Detail Penjualan -->
                        <div class="viewtampildetailpenjualan" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalgantisatuan" style="display: none;"></div>
<div class="viewmodalpembayaran" style="display: none;"></div>
<div class="viewModalGantiHarga" style="display:none;"></div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function tampildatatemppenjualan() {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/tampildatatemp') ?>",
        data: {
            jualfaktur: $('#faktur').val(),
            jualtgl: $('#jualtgl').val(),
            diskonmember: $('#diskonmember').val()
        },
        beforeSend: function() {
            $('.viewtampildetailpenjualan').html('<i class="fa fa-spin fa-spinner"></i> Tunggu').show();
        },
        success: function(response) {
            $('.viewtampildetailpenjualan').html(response).show();
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
    // $(this).keydown(function(e) {

    // });

    //Holding Transaksi Press F9
    $(this).keydown(function(e) {
        if (e.keyCode == 120) {
            e.preventDefault();
            holdingtransaksi();
        }
    });

    $(this).keydown(function(e) {
        if (e.keyCode == 119) { //Press F8
            e.preventDefault();

            transaksipembayaran();
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
        url: "<?= site_url('admin/penjualan/pembayaran') ?>",
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
    let napel = $('#napel').val();
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
                url: "<?= site_url('admin/penjualan/holdingtransaksi') ?>",
                data: {
                    faktur: faktur,
                    kodemember: kodemember,
                    napel: napel,
                    total_subtotal: $('#total_subtotal').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            html: `${response.sukses}`,
                        }).then((result) => {
                            window.location.href = (
                                "<?= site_url('admin/penjualan/all-data') ?>");
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
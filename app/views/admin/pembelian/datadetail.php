<div class="col-sm-12">
    <?= form_open('beli/selesaitransaksi', ['class' => 'formselesaitransaksi']) ?>
    <input type="hidden" name="fakturdatadetail" id="fakturdatadetail" value="<?= $faktur; ?>">
    <input type="hidden" name="tglfaktur" id="tglfaktur" value="<?= $tglfaktur; ?>">
    <div class="card m-b-30">
        <div class="card-header" style="background-color: #fff291; font-weight: bold; color:#000">
            <div class="d-flex justify-content-center">
                <div>
                    Data Detail Pembelian
                </div>
            </div>

        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-sm" style="font-size: 11pt;width:100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th style="width:5%;">Jml.Beli</th>
                        <th style="width:5%;">Satuan</th>
                        <th style="width:10%;">Tgl.<br>Kadaluarsa</th>
                        <th style="width:8%;">Jml.Return<i class="fa fa-undo-alt" style="font-size:10px"></i></th>
                        <th style="width:10%;">Harga Beli(Rp)</th>
                        <th style="width:10%;">Harga Jual(Rp)</th>
                        <th style="width:15%;">Sub.Total(Rp)</th>
                        <th style="width:10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $nomor = 0;
                    $total_subtotal = 0;
                    if (count($datadetail) > 0) :
                        foreach ($datadetail as $d) : $nomor++;
                            $total_subtotal = $total_subtotal + $d->detsubtotal; ?>
                    <tr>
                        <td><?= $nomor; ?></td>
                        <td><?= $d->detkodebarcode; ?></td>
                        <td><?= $d->namaproduk; ?></td>
                        <td><?= $d->detjml; ?></td>
                        <td><?= $d->satnama; ?></td>
                        <td>
                            <?php
                                    if ($d->dettglexpired == NULL || $d->dettglexpired == '0000-00-00') {
                                        echo '-';
                                    } else {
                                        echo date('d-m-Y', strtotime($d->dettglexpired));
                                    }
                                    ?>
                        </td>
                        <td><?= $d->detjmlreturn; ?></td>
                        <td style="text-align: right;"><?= number_format($d->dethrgbeli, 2, ".", ","); ?></td>
                        <td style="text-align: right;"><?= number_format($d->dethrgjual, 2, ".", ","); ?></td>
                        <td style="text-align: right;"><?= number_format($d->detsubtotal, 2, ".", ","); ?></td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="hapusitem('<?= $d->detid ?>','<?= $d->detkodebarcode ?>','<?= $d->namaproduk ?>')">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info"
                                onclick="edititem('<?= $d->detid ?>','<?= $d->detkodebarcode ?>','<?= $d->namaproduk ?>')">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="9" style="background-color: #f5f4f0; text-align: center; font-weight: bold;">
                            Total
                        </th>
                        <th style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            <?= number_format($total_subtotal, 2, ".", ","); ?>
                            <input type="hidden" name="totalkotor" id="totalkotor" value="<?= $total_subtotal; ?>">
                        </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            PPN(%)
                        </th>
                        <th style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            <?php if ($pembelian['pph'] == 0 || $pembelian['pph'] == NULL) : ?>
                            <input type="text" name="pph" id="pph" class="form-control form-control-sm"
                                style="text-align: right;" value="0">
                            <?php else : ?>
                            <input type="text" name="pph" id="pph" class="form-control form-control-sm"
                                style="text-align: right;" value="<?= $pembelian['pph']; ?>">
                            <?php endif; ?>
                        </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            Diskon(%)
                        </th>
                        <th style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            <input type="text" name="diskonpersen" id="diskonpersen"
                                class="form-control form-control-sm" style="text-align: right;"
                                value="<?= $pembelian['diskonpersen'] ?>">
                        </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            Diskon(Rp)
                        </th>
                        <th style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            <input type="text" name="diskonuang" id="diskonuang" class="form-control form-control-sm"
                                style="text-align: right;" value="<?= $pembelian['diskonuang']; ?>">
                        </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            Total Bersih(Rp)
                        </th>
                        <th style="background-color: #f5f4f0; text-align: right; font-weight: bold;">
                            <?php if ($pembelian['totalbersih'] == 0 || $pembelian['totalbersih'] == NULL) : ?>
                            <input type="text" readonly name="totalbersih" id="totalbersih"
                                class="form-control form-control-sm"
                                style="text-align: right; font-weight: bold; font-size:14pt;"
                                value="<?= $total_subtotal; ?>">

                            <?php else : ?>

                            <input type="text" readonly name="totalbersih" id="totalbersih"
                                class="form-control form-control-sm"
                                style="text-align: right; font-weight: bold; font-size:14pt;"
                                value="<?= $pembelian['totalbersih']; ?>">

                            <?php endif; ?>
                        </th>
                        <td></td>
                    </tr>
                    <?php else : ?>
                    <tr>
                        <th colspan="9">Data Belum ada...</th>
                        </>
                        <?php endif; ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="jenispembayaran">Jenis Pembayaran</label>
                        <?php if ($pembelian['jenisbayar'] == '' || $pembelian['jenisbayar'] == NULL) : ?>
                        <select name="jenispembayaran" id="jenispembayaran" class="form-control form-control-sm">
                            <option value="">-Silahkan Pilih-</option>
                            <option value="T">Tunai</option>
                            <option value="K">Kredit</option>
                        </select>
                        <?php else : ?>
                        <select name="jenispembayaran" id="jenispembayaran" class="form-control form-control-sm">
                            <option value="T" <?php if ($pembelian['jenisbayar'] == 'T') echo 'selected'; ?>>Tunai
                            </option>
                            <option value="K" <?php if ($pembelian['jenisbayar'] == 'K') echo 'selected'; ?>>Kredit
                            </option>
                        </select>
                        <?php endif; ?>
                        <div class="invalid-feedback errorJenisPembayaran">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Jatuh Tempo</label>
                        <select name="jatuhtempo" id="jatuhtempo" class="form-control form-control-sm"
                            disabled="disabled">
                            <option value="" selected>-Pilih-</option>
                            <option value="1">1 Minggu</option>
                            <option value="2">2 Minggu</option>
                            <option value="3">3 Minggu</option>
                            <option value="4">4 Minggu</option>
                            <option value="5">Custom</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Input Tanggal</label>
                        <?php if ($pembelian['jenisbayar'] == 'K') : ?>
                        <input type="date" name="tgljatuhtempo" id="tgljatuhtempo" class="form-control form-control-sm"
                            value="<?= $pembelian['tgljatuhtempo'] ?>">

                        <?php else : ?>
                        <input type="date" name="tgljatuhtempo" id="tgljatuhtempo" class="form-control form-control-sm"
                            placeholder="Tgl.Jatuh Tempo" disabled>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="">Aksi</label>
                        <div class="input-group">
                            <div class="input-group-append" id="button-addon4">
                                <button type="submit" class="btn btn-sm btn-success btnselesaitransaksi">Selesai
                                    Transaksi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function edititem(id, kode, nama) {
    $.ajax({
        type: "post",
        url: "<?= site_url('beli/ambilitem') ?>",
        data: {
            id: id,
            kodebarcode: kode,
            namaproduk: nama
        },
        dataType: "json",
        success: function(response) {
            if (response.sukses) {
                $('#iddetail').val(id);
                $('#kode').val(kode);
                $('#namaproduk').val(nama);
                $('#hargabeli').autoNumeric('set', response.sukses.dethrgbelikotor);
                $('#margin').autoNumeric('set', response.sukses.detmargin);
                $('#hargajual').autoNumeric('set', response.sukses.dethrgjual);
                $('#jml').autoNumeric('set', response.sukses.detjml);
                $('#namasatuan').val(response.sukses.namasatuan);
                $('#idsatuan').val(response.sukses.idsatuan);
                $('#qtysatuan').val(response.sukses.qtysatuan);
                $('#idprodukharga').val(response.sukses.idprodukharga);
                $('#dispersenitem').autoNumeric('set', response.sukses.detdispersen);
                $('#disuangitem').autoNumeric('set', response.sukses.detdisuang);
                $('#subtotalitem').autoNumeric('set', response.sukses.detsubtotal);
                $('#tgled').val(response.sukses.dettglexpired);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function hapusitem(id, kode, nama) {
    Swal.fire({
        title: 'Hapus Item',
        html: `Yakin menghapus item <strong>${kode} / ${nama}</strong> ?`,
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
                url: "<?= site_url('beli/hapusitem') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: `${response.sukses}`,
                            icon: 'success',
                            loader: true,
                            loaderBg: '#9EC600',
                            position: 'bottom-center'
                        });
                        datadetailpembelian();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });
}
$(document).ready(function() {
    // let totalkotor = $('#totalkotor').val();
    // $('#totalbersih').val(totalkotor);



    //setting currency
    $('#pph').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#diskonpersen').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#diskonuang').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#totalbersih').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    // Selesai Transaksi
    $('.formselesaitransaksi').submit(function(e) {
        e.preventDefault();
        let faktur = $('#fakturdatadetail').val();

        if (faktur.length === 0) {
            $.toast({
                heading: 'Maaf',
                icon: 'warning',
                text: 'Silahkan Tambahkan Faktur Terlebih dahulu',
                position: 'bottom-left',
                stack: false
            });
            return false;
        } else {
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        if (response.error.jenispembayaran) {
                            $('#jenispembayaran').addClass('is-invalid');
                            $('.errorJenisPembayaran').html(
                                `${response.error.jenispembayaran}`);
                        } else {
                            $('#jenispembayaran').removeClass('is-invalid');
                            $('.errorJenisPembayaran').html('');
                        }
                    }

                    if (response.sukses) {
                        $('#jenispembayaran').removeClass('is-invalid');
                        $('.errorJenisPembayaran').html('');

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            html: `Transaksi dengan Faktur ${response.faktur} berhasil disimpan, klik OK untuk reload.`,
                        }).then((result) => {
                            if (result.value) {
                                if (response.jenispembayaran == 'T') {
                                    var top = window.screen.height - 800;
                                    top = top > 0 ? top / 2 : 0;

                                    var left = window.screen.width - 600;
                                    left = left > 0 ? left / 2 : 0;

                                    // var url = '.././pemasok/index';
                                    var uploadWin = window.open(response
                                        .cetakpengeluarankas,
                                        "Pengeluaran Kas",
                                        "width=600,height=800" + ",top=" + top +
                                        ",left=" + left);
                                    uploadWin.moveTo(left, top);
                                    uploadWin.focus();

                                    window.location.reload();
                                } else {
                                    window.location.reload();
                                }

                            }
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
        return false;
    });
});
//Pilih jenis pembayaran
$(document).on('change', '#jenispembayaran', function(e) {
    e.preventDefault();
    let jp = $(this).val();
    if (jp == "T") {
        $('#jatuhtempo').prop('disabled', true);
        $('#tgljatuhtempo').prop('disabled', true);
    } else if (jp == "K") {
        $('#jatuhtempo').removeAttr('disabled');
    } else {
        $('#jatuhtempo').prop('disabled', true);
        $('#tgljatuhtempo').prop('disabled', true);
    }
});
// Pilih jatuh tempo
$(document).on('change', '#jatuhtempo', function(e) {
    e.preventDefault();
    let jt = $(this).val();

    if (jt == "1" || jt == "2" || jt == "3" || jt == "4") {
        $('#tgljatuhtempo').prop('disabled', true);
    } else if (jt == "5") {
        $('#tgljatuhtempo').removeAttr('disabled');
    } else {
        $('#tgljatuhtempo').prop('disabled', true);
    }
});
// Perhitungan PPH dan Diskon
function hitungtotalbersih() {
    let pph = $('#pph').val().replace(",", "");
    let diskonuang = $('#diskonuang').val().replace(",", "");
    let diskonpersen = $('#diskonpersen').val().replace(",", "");

    let totalkotor = $('#totalkotor').val();

    let hitungpph = parseFloat(totalkotor) + (parseFloat(totalkotor) * parseFloat(pph) / 100);

    let totalbersih = hitungpph - (hitungpph * parseFloat(diskonpersen) / 100) - parseFloat(diskonuang);

    $('#totalbersih').autoNumeric('set', totalbersih);
}
$(document).on('keyup', '#pph', function(e) {
    hitungtotalbersih();
});
$(document).on('keyup', '#diskonpersen', function(e) {
    hitungtotalbersih();
});
$(document).on('keyup', '#diskonuang', function(e) {
    hitungtotalbersih();
});

$(document).on('click', '#totalbersih', function(e) {
    hitungtotalbersih();
});
</script>
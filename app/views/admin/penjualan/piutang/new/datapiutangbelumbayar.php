<div class="col-lg-12">
    <div class="alert alert-info">
        <strong>Daftar Faktur Yang Belum Bayar</strong>
    </div>
</div>

<div class="col-lg-12">
    <?= form_open('admin/penjualan/bayarPiutangPelanggan', ['class' => 'formbayarpiutang']); ?>
    <table class="table table-striped table-sm" style="text-align: right;">
        <tr>
            <td style="width: 60%;" align="left">
                <button type="submit" class="btn btn-sm btn-success btnbayar">
                    Bayar Piutang Pelanggan
                </button>
            </td>
            <td style="width: 40%;" align="right">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-4 col-form-label">Total Bayar</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control-lg form-control" name="totalseluruhbayar"
                            id="totalseluruhbayar" value="0"
                            style="text-align: right; font-weight: bold; font-size:14pt; color:#280ecf;" readonly>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
        id="detailpiutangpelanggan" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Faktur</th>
                <th>Tanggal</th>
                <th>Jml.Item</th>
                <th>Total Piutang (Rp)</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $nomor = 1;
            foreach ($detailpiutang->result_array() as $row) :
            ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['jualfaktur']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['jualtgl'])) ?></td>
                <td><?= $row['jmlitem']; ?></td>
                <td style="text-align: right;">
                    <?= number_format($row['jualpembulatan'], 0, ",", "."); ?>
                </td>
                <td>
                    <input type="checkbox" class="cekbayar" name="faktur[]" value="<?= $row['jualfaktur'] ?>">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= form_close(); ?>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#totalseluruhbayar').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });

    $('.cekbayar').change(function(e) {
        let totalseluruh = $('#totalseluruhbayar').autoNumeric('get');

        if ($(this).is(":checked")) {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/penjualan/ambilJumlahPenjualan') ?>",
                data: {
                    faktur: $(this).val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        let nilaitotal = response.sukses;
                        totalseluruh = parseFloat(totalseluruh) + parseFloat(nilaitotal);
                        $('#totalseluruhbayar').autoNumeric('set', totalseluruh);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/penjualan/ambilJumlahPenjualan') ?>",
                data: {
                    faktur: $(this).val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        let nilaitotal = response.sukses;
                        totalseluruh = parseFloat(totalseluruh) - parseFloat(nilaitotal);
                        $('#totalseluruhbayar').autoNumeric('set', totalseluruh);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

        // let totalseluruh = $('#totalseluruhbayar').val();
        // if ($(this).is(":checked")) {
        //     let nilaitotal = $(this).val();
        //     totalseluruh = parseFloat(totalseluruh) + parseFloat(nilaitotal);
        // } else {
        //     let nilaitotal = $(this).val();
        //     totalseluruh = parseFloat(totalseluruh) - parseFloat(nilaitotal);
        // }

        // $('#totalseluruhbayar').val(totalseluruh);
    });

    $('.formbayarpiutang').submit(function(e) {
        e.preventDefault();

        let pilih = $('.cekbayar:checked');

        if (pilih.length === 0) {
            Swal.fire('Perhatian',
                'Faktur tidak ada yang dicentang, silahkan centang terlebih dahulu !', 'warning');
        } else {
            Swal.fire({
                title: 'Bayar Piutang',
                html: `Ada ${pilih.length} faktur yang dibayarkan, Yakin lanjut pembayaran piutang ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjut !',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json",
                        beforeSend: function() {
                            $('.btnbayar').prop('disabled', true);
                            $('.btnbayar').html(
                                '<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(response) {
                            if (response.sukses) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.sukses,
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.reload();
                                    }
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
        return false;

    });
});
</script>
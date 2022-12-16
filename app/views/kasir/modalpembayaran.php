<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<div class="modal fade bd-example-modal-md" id="modalpembayaran" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exampleModalCenterTitle">Bayar | <?= $faktur; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('kasir/simpantransaksi', ['class' => 'formsimpan']) ?>
            <div class="modal-body">
                <input type="hidden" value="<?= $faktur; ?>" name="faktur" id="faktur">
                <input type="hidden" value="<?= $kodemember; ?>" name="kodemember" id="kodemember">
                <input type="hidden" value="<?= $namamember; ?>" name="namamember" id="namamember">
                <input type="hidden" value="<?= $total_kotor; ?>" name="total_kotor" id="total_kotor">
                <input type="hidden" value="<?= $total_bersih_semua; ?>" name="total_bersih_semua"
                    id="total_bersih_semua">
                <input type="hidden" value="<?= $pembulatan; ?>" name="pembulatan" id="pembulatan">
                <input type="hidden" value="<?= $dispersensemua; ?>" name="dispersensemua" id="dispersensemua">
                <input type="hidden" value="<?= $disuangsemua; ?>" name="disuangsemua" id="disuangsemua">
                <div class="form-group row">
                    <label for="" class="col-sm-4 col-form-label">Member</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" disabled="disabled"
                            value="<?= $kodemember . ' - ' . $namamember ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Pembayaran</label>
                    <div class="col-sm-8">
                        <select name="jenispembayaran" id="jenispembayaran" class="form-control">
                            <option value="T" selected>Cash</option>
                            <option value="K">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row inputtglpembayaran" style="display: none;">
                    <label class="col-sm-4 col-form-label">Tgl.Pembayaran</label>
                    <div class="col-sm-8">
                        <input type="date" name="tglpembayaran" id="tglpembayaran" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Total Pembayaran (Rp.)</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control"
                            style="text-align: right; font-weight: bold; font-size:16pt;" name="totalpembayaran"
                            id="totalpembayaran" readonly="readonly">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Jumlah Uang (Rp)</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" autocomplete="off" name="jumlahuang" id="jumlahuang"
                            style="text-align: right; font-weight: bold; font-size:16pt; color:blue;">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Sisa(Rp)</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="sisa" id="sisa"
                            style="text-align: right; font-weight: bold; font-size:16pt; color:red;"
                            readonly="readonly">
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btnsimpan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {

    $('#sisa').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#jumlahuang').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });
    $('#totalpembayaran').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    let ambilnilaipembulatan = $('#pembulatan').autoNumeric('get');
    let totalbersihsemua = $('#total_bersih_semua').autoNumeric('get');
    if (ambilnilaipembulatan == 0) {
        $('#totalpembayaran').autoNumeric('set', totalbersihsemua);

    } else {
        $('#totalpembayaran').autoNumeric('set', ambilnilaipembulatan);
    }
    // $('#pembulatan').autoNumeric('init', {
    //     aSep: ',',
    //     aDec: '.',
    //     mDec: '0'
    // });
    // $('#diskonuang').autoNumeric('init', {
    //     aSep: ',',
    //     aDec: '.',
    //     mDec: '2'
    // });
    // $('#totalbersih').autoNumeric('init', {
    //     aSep: ',',
    //     aDec: '.',
    //     mDec: '0'
    // });
    $('#sisa').click(function(e) {
        $(this).val(0);
    });

    // Simpan transaksi
    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        let sisauang = $('#sisa').val();
        // sisauangx = replace.replace(",", "");
        if (sisauang < 0) {
            $.toast({
                heading: 'Error',
                text: 'Maaf Jumlah uang tidak mencukupi',
                icon: 'error',
                loader: true,
                loaderBg: '#9EC600',
                position: 'bottom-center'
            });
        } else {
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                cache: false,
                beforeSend: function() {
                    $('.btnsimpan').attr('disabled', 'disabled');
                    $('.btnsimpan').html(
                        '<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                    if (response.error) {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            position: 'mid-center',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: 'error',
                        })
                    }
                    if (response.sukses) {
                        $('#modalpembayaran').modal('hide');
                        $('#kode').prop('disabled', true);
                        $('.tampilsisauang').html(
                            `<h3>Kembali : ${response.sisauang}</h3>`
                        );
                        let faktur = $('#faktur').val();
                        Swal.fire({
                            title: `Kembali : ${response.sisauang}`,
                            html: 'Cetak Faktur ini ?',
                            icon: 'warning',
                            focusConfirm: true,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Cetak !',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.value) {
                                // window.location.reload();
                                // var top = window.screen.height - 400;
                                // top = top > 0 ? top / 2 : 0;

                                // var left = window.screen.width - 200;
                                // left = left > 0 ? left / 2 : 0;

                                // var uploadWin = window.open(response.cetakfaktur,
                                //     "Struk Kasir",
                                //     "width=200,height=400" + ",top=" + top +
                                //     ",left=" + left);
                                // uploadWin.moveTo(left, top);
                                // uploadWin.focus();

                                // Perintah untuk cetak thermal
                                $.ajax({
                                    type: "post",
                                    url: "<?= site_url('kasir/printDirect') ?>",
                                    data: {
                                        faktur: response.nofaktur
                                    },
                                    success: function(response) {
                                        alert(response);
                                    }
                                });

                                // End Perintah untuk cetak thermal
                            } else {
                                window.location.reload();
                            }
                        })

                    }
                },
                complete: function() {
                    $('.btnsimpan').removeAttr('disabled');
                    $('.btnsimpan').html('Simpan');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
        return false;
    });

    $('#jumlahuang').keydown(function(e) {
        if (e.keyCode == 32) {
            // jika menekan tombol spasi
            e.preventDefault();
            $('#jumlahuang').val($('#totalpembayaran').val());
            $('#sisa').val(0);
        }
    });

});
$(document).on('keyup', '#jumlahuang', function(e) {
    let jumlahuang = $(this).autoNumeric('get');
    let totalpembayaran = $('#totalpembayaran').autoNumeric('get');

    let sisauang = parseFloat(jumlahuang) - parseFloat(totalpembayaran);
    if (sisauang == isNaN) {
        $('#sisa').val(0);
    } else {
        $('#sisa').val(sisauang);
        $('#sisa').autoNumeric('set', sisauang);
    }
});
$(document).on('change', '#jenispembayaran', function(e) {
    let jenispembayaran = $(this).val();
    let totalpembayaran = $('#totalpembayaran').autoNumeric('get');
    if (jenispembayaran == 'K') {
        $('.inputtglpembayaran').fadeIn();
        $('#jumlahuang').autoNumeric('set', totalpembayaran);

        let jumlahuang = $('#jumlahuang').autoNumeric('get');
        let sisauang = parseFloat(jumlahuang) - parseFloat(totalpembayaran);
        if (sisauang == isNaN) {
            $('#sisa').val(0);
        } else {
            $('#sisa').val(sisauang);
            $('#sisa').autoNumeric('set', sisauang);
        }
    } else {
        $('.inputtglpembayaran').fadeOut();
        $('#jumlahuang').autoNumeric('set', 0);

        let jumlahuang = $('#jumlahuang').autoNumeric('get');
        let sisauang = parseFloat(jumlahuang) - parseFloat(totalpembayaran);
        if (sisauang == isNaN) {
            $('#sisa').val(0);
        } else {
            $('#sisa').val(sisauang);
            $('#sisa').autoNumeric('set', sisauang);
        }
    }
});

// $(document).on('keyup', '#diskonuang', function(e) {
//     let diskonpersen = $('#diskonpersen').val();
//     let diskonuang = $('#diskonuang').val();
//     let diskonuangx = diskonuang.replace(",", "");


//     let total_subtotal = $('#total_subtotal_bersih').val();
//     hitung_diskon = parseFloat(total_subtotal) - (parseFloat(total_subtotal) * parseFloat(diskonpersen) /
//             100) -
//         parseFloat(diskonuangx);

//     $('#totalbersih').val(hitung_diskon);
//     $('#totalbersih').autoNumeric('set', hitung_diskon);
// });
// $(document).on('keyup', '#diskonpersen', function(e) {
//     let diskonpersen = $('#diskonpersen').val();
//     let diskonuang = $('#diskonuang').val();
//     let diskonuangx = diskonuang.replace(",", "");


//     let total_subtotal = $('#total_subtotal_bersih').val();
//     hitung_diskon = parseFloat(total_subtotal) - (parseFloat(total_subtotal) * parseFloat(diskonpersen) /
//             100) -
//       
  parseFloat(diskonuangx);

//     let ambilratusan = hitung_diskon.substr(2);
//     console.log(ambilratusan);

//     $('#totalbersih').val(hitung_diskon);
//     $('#totalbersih').autoNumeric('set', hitung_diskon);
// });
</script>
$(document).ready(function () {
    $('.btnbataltransaksi').hide();
    tampildetailpembelian();
    //Tooltip
    $('[data-toggle="tooltip"]').tooltip();
    // end
    $('.addDataPemasok').click(function (e) {
        $.ajax({
            url: "./tambahpemasok",
            success: function (response) {
                $('.viewmodal').show();
                $('.viewmodal').html(response);
                const element = document.querySelector('#modaltambah');
                element.classList.add('animated', 'flipInX');
                $('#modaltambah').on('shown.bs.modal', function (e) {
                    $('input[name="nama"]').focus();
                })
                $('#modaltambah').modal('show');
            }
        });
    });

    $('.cariData').click(function (e) {
        caridatapemasok();
    });

    //Pencari Data Produk berdasarkan Kode Barcode
    $('#kodebarcode').keydown(function (e) {

        if (e.keyCode == 13) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "./detailproduk",
                data: {
                    kode: $(this).val()
                },
                dataType: "json",
                success: function (response) {
                    if (response.error) {
                        $.toast({
                            heading: 'Error',
                            text: response.error,
                            icon: 'error',
                            loader: true,
                            position: 'top-center'
                        });
                        $('.namaproduk').html('');
                        $('.viewnamaproduk').hide();

                        $('#namasatuan').val('');
                        $('#idsatuan').val('');
                        $('#qtydefault').val('');
                    } else {
                        $('.viewnamaproduk').show();
                        $('.namaproduk').html(`<span style="font-style:italic;color:blue;font-weight:bold;">${response.namaproduk}</span>`);

                        //menampilkan satuan
                        $('#namasatuan').val(response.namasatuan);
                        $('#idsatuan').val(response.idsatuan);
                        $('#qtydefault').val(response.qtydefault);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    //Tambah Produk
    $('.cariDataProduk').click(function (e) {
        $.ajax({
            url: "./viewdataproduk",
            success: function (response) {
                $('.viewmodal').show();
                $('.viewmodal').html(response);
                $('#modalcariproduk').modal('show');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    //Tambah Data Produk
    $('.tambahProduk').click(function (e) {
        newwindow = window.open(".././produk/add", 'name',
            'height=520,width=800,scrollbars=yes,location=no,top=100,channelmode=1');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    });

    //Cari Satuan Produk
    $('.carisatuanproduk').click(function (e) {
        let kode = $('#kodebarcode').val();
        $.ajax({
            url: "./viewdatasatuanhargaproduk",
            type: 'POST',
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    Swal.fire(response.error);
                }

                if (response.sukses) {
                    $('.viewmodal').show();
                    $('.viewmodal').html(response.sukses);
                    $('#modalsatuanharga').modal('show');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    //Simpan Faktur
    $('.formfaktur').submit(function (e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function (response) {
                if (response.error) {
                    $('.msg').fadeIn();
                    $('.msg').html(response.error);
                }
                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.sukses,
                    });

                    $('#nofaktur').attr('readonly', 'readonly');
                    $('#tglbeli').attr('disabled', 'disabled');
                    $('.btncaripemasok').attr('disabled', 'disabled');

                    $('.btnbataltransaksi').fadeIn();

                    $('.forminputitempembelian').toggle();
                }
            },
            complete: function () {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });

    // Batal Transaksi

    $('.btnbataltransaksi').click(function (e) {
        let nofakturpembelian = $('#nofaktur').val();
        Swal.fire({
            title: 'Batalkan Transaksi',
            text: `Yakin membatalkan transaksi dengan faktur ${nofakturpembelian} ini ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan !',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "./bataltransaksi",
                    data: {
                        nofaktur: nofakturpembelian
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.sukses) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.sukses,
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    }
                });
            }
        });

    });

    // Simpan Item Pembelian
    $('.btnsimpanitem').click(function (e) {
        let nofaktur = $('#nofaktur').val();
        let kodebarcode = $('#kodebarcode').val();
        let idsatuan = $('#idsatuan').val();
        let qtydefault = $('#qtydefault').val();
        let jml = $('#jml').val();
        let tgled = $('#tgled').val();
        let hrgbeli = $('#hrgbeli').val();

        if (kodebarcode == '') {
            Swal.fire('Error', 'Setidaknya kode tidak boleh kosong', 'error');
        }
        else {
            $.ajax({
                type: "post",
                url: "./simpanitem",
                data: {
                    nofaktur: nofaktur,
                    kodebarcode: kodebarcode,
                    idsatuan: idsatuan,
                    qtydefault: qtydefault,
                    jml: jml,
                    tgled: tgled,
                    hrgbeli: hrgbeli
                },
                dataType: "json",
                success: function (response) {
                    if (response.sukses) {
                        kosongkanDetail();
                        tampildetailpembelian();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                            position: 'bottom-right'
                        });
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

});

function tampildetailpembelian() {
    $.ajax({
        type: "post",
        url: "./tampildetailpembelian",
        data: {
            nofaktur: $('#nofaktur').val()
        },
        beforeSend: function () {
            $('.tampildetailpembelian').html('<i class="fa fa-spin fa-spinner"></i> Tunggu sesaat...').show();
        },
        success: function (response) {
            $('.tampildetailpembelian').html(response).show();
        }
    });
}

function caridatapemasok() {
    $.ajax({
        url: "./viewdatapemasok",
        success: function (response) {
            $('.viewmodal').show();
            $('.viewmodal').html(response);
            $('#modalcaripemasok').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function kosongkanDetail() {
    $('#kodebarcode').val('');
    $('#namasatuan').val('');
    $('#idsatuan').val('');
    $('#qtydefault').val('');
    $('#jml').val('');
    $('#tgled').val('');
    $('#hrgbeli').val('');

    $('.viewnamaproduk').hide();

    $('#kodebarcode').focus();
}

//Ubah ke Format Rupiah
let jml = document.getElementById('jml');
jml.addEventListener('keyup', function (e) {
    jml.value = formatRupiah(this.value, 'Rp. ');
});

let hrgbeli = document.getElementById('hrgbeli');
hrgbeli.addEventListener('keyup', function (e) {
    hrgbeli.value = formatRupiah(this.value, 'Rp. ');
});

//Format Rupiah
function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
}


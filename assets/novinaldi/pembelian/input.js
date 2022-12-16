function nonAktifFaktur() {
    $('#faktur').attr('readonly', 'readonly');
    $('#tgl').attr('readonly', 'readonly');
}

function tampilforminputdetail() {
    $.ajax({
        type: "post",
        url: "./forminputdetail",
        data: {
            faktur: $('#faktur').val()
        },
        beforeSend: function () {
            $('.viewforminputdetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function (response) {
            $('.viewforminputdetail').html(response).show();
            $('#kode').focus();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampildatadetail() {
    $.ajax({
        type: "post",
        url: "./datadetail",
        data: {
            faktur: $('#faktur').val()
        },
        beforeSend: function () {
            $('.viewtampildatadetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function (response) {
            $('.viewtampildatadetail').html(response).show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).ready(function (e) {
    // tampilforminputdetail();
    tampildatadetail();
    $('[data-toggle="tooltip"]').tooltip();
    $('.tambahdatapemasok').click(function (e) {
        e.preventDefault();
        var top = window.screen.height - 500;
        top = top > 0 ? top / 2 : 0;

        var left = window.screen.width - 800;
        left = left > 0 ? left / 2 : 0;

        var url = '.././pemasok/index';
        var uploadWin = window.open(url, "Tambah Data Pemasok", "width=800,height=500" + ",top=" + top + ",left=" + left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();
    });


    $('.caridatapemasok').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "./caridatapemasok",
            success: function (response) {
                $('.viewmodal').html(response).show();
                $('#modalcaripemasok').modal('show');
            }
        });
    });

    // Simpan Faktur
    $('.formfaktur').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
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
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        loader: true,
                        position: 'top-center'
                    });
                    tampilforminputdetail();
                    nonAktifFaktur();
                }
            },
            complete: function () {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('<i class="fa fa-save"></i>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });

    // Batalkan Transaksi
    $('.btnbatalkantransaksi').click(function (e) {
        e.preventDefault();
        let faktur = $('#faktur').val();
        Swal.fire({
            title: 'Batalkan Transaksi',
            text: "Semua data yang pada faktur yang dihapus, yakin ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya !',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "./bataltransaksi",
                    data: {
                        faktur: faktur
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.error) {
                            Swal.fire('Error', response.error, 'error');
                        }

                        if (response.sukses) {
                            Swal.fire({
                                title: 'Berhasil !',
                                text: response.sukses,
                                icon: 'success',
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
        })
    });

    $('.btntampilforminput').click(function (e) {
        e.preventDefault();
        let faktur = $('#faktur').val();
        if (faktur.length == 0) {
            $.toast({
                heading: 'Maaf',
                text: 'Faktur harus anda simpan terlebih dahulu',
                showHideTransition: 'slide',
                icon: 'warning',
                position: 'bottom-center'
            });
        } else {
            tampilforminputdetail();
        }
    });

    $('.btndatatransaksi').click(function (e) {
        e.preventDefault();
        window.location = ('./data');
    });
});
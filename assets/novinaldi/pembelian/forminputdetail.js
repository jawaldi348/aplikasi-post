function tampilforminputdetail() {
    let faktur = $('#faktur').val();
    $.ajax({
        type: "post",
        url: "./forminputdetail",
        data: {
            faktur: faktur
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
$(document).ready(function () {
    $('#ed').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        month: true
    });

    $('.btntutup').click(function (e) {
        e.preventDefault();
        $('.viewforminputdetail').fadeOut();
    });
    $('.btnrefresh').click(function (e) {
        e.preventDefault();
        tampilforminputdetail();
    });

    $('.btncariproduk').click(function (e) {
        $.ajax({
            url: "./cariproduk",
            success: function (response) {
                $('.viewmodal').html(response).show();
                $('#modalcariproduk').modal('show');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    $('#kode').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            let kode = $(this).val();
            $.ajax({
                type: "post",
                url: "./detailproduk",
                data: {
                    kode: kode
                },
                dataType: "json",
                success: function (response) {
                    if (response.sukses) {
                        $('#namaproduk').val(response.sukses.namaproduk);
                        $('#namaproduk').attr('data-content', response.sukses.namaproduk);
                        $('#stoktersedia').val(response.sukses.stoktersedia);
                        $('#idsatuan').val(response.sukses.idsatuan);
                        $('#namasatuan').val(response.sukses.namasatuan);
                        $('#jmleceran').val(response.sukses.jmleceran);
                        $('#hargabeli').val(response.sukses.hargabeli);
                        $('#hargajual').val(response.sukses.hargajual);
                    }
                    else {
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
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });

    // cari satuan
    $('.btncarisatuan').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'post',
            data: {
                kode: $('#kode').val()
            },
            url: "./carisatuan",
            cache: false,
            success: function (response) {
                $('.viewmodal').html(response).show();
                $('#modalcarisatuan').modal('show');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    // Untuk mengupdate harga
    $("#hargabeli").dblclick(function () {
        $(this).removeAttr('readonly', 'readonly');
    });
    $("#hargajual").dblclick(function () {
        $(this).removeAttr('readonly', 'readonly');
    });


    $('#hargabeli').keydown(function (e) {
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
                            url: "./updatehargabeliproduk",
                            data: {
                                hargabeli: hargabeli,
                                kode: kode,
                                idsatuan: idsatuan,
                                idprodukharga: $('#idprodukharga').val()
                            },
                            dataType: "json",
                            success: function (response) {
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
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }
                        });
                    }
                })
            }
            return false;
        }
    });

    $('#hargajual').keydown(function (e) {
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
                            url: "./updatehargajualproduk",
                            data: {
                                hargajual: hargajual,
                                kode: kode,
                                idsatuan: idsatuan,
                                idprodukharga: $('#idprodukharga').val()
                            },
                            dataType: "json",
                            success: function (response) {
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
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }
                        });
                    }
                })
            }
            return false;
        }
    });

    $('#hargabeli').keydown(function (e) {
        if (e.keyCode == 27) {
            $(this).attr('readonly', 'readonly');
        }
    });
    $('#hargajual').keydown(function (e) {
        if (e.keyCode == 27) {
            $(this).attr('readonly', 'readonly');
        }
    });

    // Simpan Item 
    $('.forminputitem').submit(function (e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function (e) {
                $('.btnadditem').attr('disabled', 'disabled');
                $('.btnadditem').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function (e) {
                $('.btnadditem').removeAttr('disabled');
                $('.btnadditem').html('<i class="fa fa-plus-circle"></i> Add');
            },
            success: function (response) {
                if (response.error) {
                    $('.msgdetail').html(response.error).show();
                }
                else {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        showHideTransition: 'slide',
                        icon: 'success',
                        position: 'top-center'
                    });
                    tampilforminputdetail();
                    tampildatadetail();
                }

            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });

    // // Simpan item
    // $('.btnadditem').click(function (e) {
    //     e.preventDefault();
    //     // tampilforminputdetail();
    //     let kode = $('#kode').val();

    //     if (kode.length == 0 || kode == undefined || kode == null) {
    //         $.toast({
    //             heading: 'Error',
    //             text: 'Pastikan Kode barcode barang tidak boleh kosong',
    //             showHideTransition: 'slide',
    //             icon: 'error',
    //             position: 'bottom-center'
    //         });
    //     } else {
    //         $.ajax({
    //             type: "post",
    //             url: "./simpanitem",
    //             data: {
    //                 faktur: $('#faktur').val(),
    //                 kodebarcode: kode,
    //                 idsatuan: $('#idsatuan').val(),
    //                 jmlsatuan: $('#jmleceran').val(),
    //                 hargabeli: $('#hargabeli').val(),
    //                 jml: $('#jmlbeli').val(),
    //                 ed: $('#ed').val()
    //             },
    //             dataType: "json",
    //             success: function (response) {
    //                 if (response.sukses) {
    //                     tampilforminputdetail();
    //                     $.toast({
    //                         heading: 'Berhasil',
    //                         text: 'Item berhasil ditambahkan',
    //                         showHideTransition: 'slide',
    //                         icon: 'success',
    //                         position: 'top-center'
    //                     });
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //                 alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    //             }
    //         });
    //     }
    // });
});

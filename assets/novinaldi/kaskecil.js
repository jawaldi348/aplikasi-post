function ambiltotalkas() {
    $.ajax({
        url: "./totalkas",
        dataType: 'json',
        success: function (response) {
            $('.totalkas').html(response.totalkas).show();
        }
    });
}
function tampildatakaskecil() {
    table = $('#datakaskecil').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildata',
            "type": "POST"
        },


        "columnDefs": [
            {
                "targets": [1],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [0],
                "orderable": false,
                "width": 3
            }
        ],

    });
}
$(document).ready(function (e) {
    tampildatakaskecil();
    ambiltotalkas();


    //check
    $('#check-all').click(function (e) {
        if ($(this).is(":checked")) {
            $(".check-item").prop("checked", true);
        } else {
            $(".check-item").prop("checked", false);
        }
    });

    //Tombol Tambah
    $('.btnTambah').click(function (e) {
        $.ajax({
            url: "./tambah",
            success: function (response) {
                $('.viewmodal').show();
                $('.viewmodal').html(response);
                const element = document.querySelector('#modaltambah');
                element.classList.add('animated', 'flipInX');
                $('#modaltambah').on('shown.bs.modal', function (e) {
                    $('input[name="jml"]').focus();
                })
                $('#modaltambah').modal('show');
            }
        });
    });

});


//SImpan Data
$(document).on('submit', '.form', function (e) {
    $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        cache: false,
        beforeSend: function () {
            $('.btnsimpan').attr('disabled', 'disabled');
            $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
        },
        success: function (response) {
            // if (response.error) {
            //     $('.msg').fadeIn();
            //     $('.msg').html(response.error);
            // }
            if (response.sukses) {
                $.toast({
                    heading: 'Berhasil',
                    text: response.sukses,
                    icon: 'success',
                    position: 'top-center',
                    loader: true,
                });
                tampildatakaskecil();
                ambiltotalkas();
                $('#modaltambah').modal('hide');
                $('#modaledit').modal('hide');
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

//Hapus Data

function hapus(id) {
    Swal.fire({
        title: 'Hapus Data',
        text: "Yakin jumlah kas ini dihapus ?",
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
                url: "./hapus",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (response) {
                    if (response.sukses) {
                        tampildatakaskecil();
                        ambiltotalkas();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            position: 'top-center',
                            loader: true,
                        });
                    }
                }
            });
        }
    })
}

//FOrm edit 
function edit(id) {
    $.ajax({
        url: "./edit",
        type: 'POST',
        data: {
            id: id
        },
        cache: false,
        success: function (response) {
            $('.viewmodal').show();
            $('.viewmodal').html(response);
            const element = document.querySelector('#modaledit');
            element.classList.add('animated', 'flipInX');
            $('#modaledit').on('shown.bs.modal', function (e) {
                $('input[name="jml"]').focus();
            })
            $('#modaledit').modal('show');
        }
    });
}

// Hapus Multiple
$(document).on('submit', '.formhapusmutli', function (e) {
    let pilih = $('.check-item:checked');

    if (pilih.length === 0) {
        Swal.fire('Perhatian', 'Tidak Ada item yang dihapus ?', 'warning');
    } else {
        let pesan = confirm('Yakin ' + pilih.length + ' data dihapus ?');
        if (pesan) {
            return true;
        } else {
            return false;
        }
    }

    return false;
});
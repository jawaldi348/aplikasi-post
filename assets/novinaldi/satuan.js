$(document).ready(function (e) {
    tampildatasatuan();

    $('#check-all').click(function (e) {
        if ($(this).is(":checked")) {
            $(".check-item").prop("checked", true);
        } else {
            $(".check-item").prop("checked", false);
        }
    });
});

function tampildatasatuan() {
    table = $('#datasatuan').DataTable({
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

const btnTambah = document.getElementById('btnTambah');
const tombolTambah = () => {
    $.ajax({
        url: "./tambah",
        success: function (response) {
            $('.viewform').show();
            $('.viewform').html(response);
            const element = document.querySelector('#modaltambah');
            element.classList.add('animated', 'zoomIn');
            $('#modaltambah').modal('show');
        }
    });
}

btnTambah.addEventListener('click', tombolTambah);

$(document).on('submit', '.formtambah', function (e) {
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
                });
                tampildatasatuan();
                $('#modaltambah').modal('hide');
            }
        },
        complete: function () {
            $('.btnsimpan').removeAttr('disabled');
            $('.btnsimpan').html('Simpan Data');
        }
    });

    return false;
});


// Hapus Data
function hapus(id) {
    Swal.fire({
        title: 'Hapus Data Satuan',
        text: "Yakin diHapus ?",
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
                        tampildatasatuan();
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                        });
                    }
                }
            });
        }
    })
}

// Form edit data
function edit(id) {
    $.ajax({
        url: "./edit",
        type: 'post',
        data: {
            id: id
        },
        cache: false,
        success: function (response) {
            $('.viewform').show();
            $('.viewform').html(response);
            const modaledit = document.querySelector('#modaledit');
            modaledit.classList.add('animated', 'zoomIn');
            $('#modaledit').modal('show');
        }
    });
}

$(document).on('submit', '.formedit', function (e) {
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
            if (response.sukses) {
                $.toast({
                    heading: 'Berhasil',
                    text: response.sukses,
                    icon: 'success',
                    loader: true,
                });
                tampildatasatuan();
                $('#modaledit').modal('hide');
            }
        },
        complete: function () {
            $('.btnsimpan').removeAttr('disabled');
            $('.btnsimpan').html('Update Data');
        }
    });

    return false;
});


// Hapus Multiple
$(document).on('submit', '.formdelete', function (e) {
    let pilih = $('.check-item:checked');

    if (pilih.length === 0) {
        Swal.fire('Perhatian', 'Tidak Ada item yang dihapus ?', 'warning');
    } else {
        let pesan = confirm('Yakin ' + pilih.length + ' data satuan dihapus ?');
        if (pesan) {
            return true;
        } else {
            return false;
        }
    }

    return false;
});
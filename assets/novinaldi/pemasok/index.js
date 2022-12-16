function tampildatapemasok() {
    table = $('#datapemasok').DataTable({
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
        ]
    });
}

function kosongkan() {
    document.querySelector('#nama').value = "";
    document.querySelector('#alamat').value = "";
    document.querySelector('#telp').value = "";

    document.querySelector('#nama').focus();

}

$(document).ready(function (e) {
    tampildatapemasok();

    $('#check-all').click(function (e) {
        if ($(this).is(":checked")) {
            $(".check-item").prop("checked", true);
        } else {
            $(".check-item").prop("checked", false);
        }
    });
});

//Tambah Data dan simpan data
const btnTambah = document.getElementById('btnTambah');
const tombolTambah = () => {
    $.ajax({
        url: "./tambah",
        success: function (response) {
            $('.viewform').show();
            $('.viewform').html(response);
            const element = document.querySelector('#modaltambah');
            element.classList.add('animated', 'flipInX');
            $('#modaltambah').on('shown.bs.modal', function (e) {
                $('input[name="nama"]').focus();
            })
            $('#modaltambah').modal('show');
        }
    });
}

btnTambah.addEventListener('click', tombolTambah);

// Hapus Data
function hapus(id) {
    Swal.fire({
        title: 'Hapus Data Pemasok',
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
                        tampildatapemasok();
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


// Form Edit Data
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
            const element = document.querySelector('#modaledit');
            element.classList.add('animated', 'flipInX');
            $('#modaledit').on('shown.bs.modal', function (e) {
                $('input[name="nama"]').focus();
            })
            $('#modaledit').modal('show');
        }
    });
}

// Update data
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
                tampildatapemasok();
                $('#modaledit').modal('hide');
            }
        },
        complete: function () {
            $('.btnsimpan').removeAttr('disabled');
            $('.btnsimpan').html('Simpan Data');
        }
    });

    return false;
});

// Hapus Multiple
$(document).on('submit', '.formdelete', function (e) {
    let pilih = $('.check-item:checked');

    if (pilih.length === 0) {
        Swal.fire('Perhatian', 'Tidak Ada item pemasok yang dihapus ?', 'warning');
    } else {
        let pesan = confirm('Yakin ' + pilih.length + ' data pemasok dihapus ?');
        if (pesan) {
            return true;
        } else {
            return false;
        }
    }

    return false;
});
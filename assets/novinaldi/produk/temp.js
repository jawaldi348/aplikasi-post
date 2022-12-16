function tampildataproduktemp() {
    table = $('#dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildataproduktemp',
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
                "width": 5
            }
        ],

    });
}
$(document).ready(function (e) {
    tampildataproduktemp();

    $('#check-all').click(function (e) {
        if ($(this).is(":checked")) {
            $(".check-item").prop("checked", true);
        } else {
            $(".check-item").prop("checked", false);
        }
    });
});

function hapuspermanen(id, nama) {
    Swal.fire({
        title: 'Hapus',
        text: `Yakin Menghapus Produk ${nama} secara Permanen ?`,
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
                url: "./hapusprodukpermanen",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (response) {
                    if (response.sukses) {
                        tampildataproduktemp();
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
function recovery(id, nama) {
    Swal.fire({
        title: 'Recovery Data',
        text: `Yakin Produk ${nama} di-kembalikan ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kembalikan !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "./kembalikanproduk",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (response) {
                    if (response.sukses) {
                        tampildataproduktemp();
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


//Hapus multiple
$(document).on('submit', '.formdelete', function (e) {
    let pilih = $('.check-item:checked');

    if (pilih.length === 0) {
        Swal.fire('Perhatian', 'Tidak Ada item Produk yang di pilih untuk dihapus !', 'warning');
    } else {
        Swal.fire({
            title: 'Hapus Produk',
            text: `Yakin Menghapus ${pilih.length} Produk yang dipilih ?, Produk yang terhapus akan dihapus secara permanen !`,
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
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    // dataType: "json",
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        })
    }

    return false;
});

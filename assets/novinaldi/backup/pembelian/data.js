$(document).ready(function () {
    tampildatapembelian();
});
function tampildatapembelian() {
    table = $('#datatransaksipembelian').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildatatransaksi',
            "type": "POST"
        },


        "columnDefs": [
            {
                "targets": [0],
                "orderable": false,
                "width": 3
            }
        ],

    });
}

//Hapus Transaksi
function hapus(no) {
    Swal.fire({
        title: 'Hapus Transaksi',
        text: `Yakin menghapus transaksi dengan faktur ${no} ini ?, semua detail juga ikut terhapus`,
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
                url: "./bataltransaksi",
                data: {
                    nofaktur: no
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
                                tampildatapembelian();
                            }
                        })
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    });
}

//edit transaksi
function edit(no) {
    Swal.fire('Masih tahap percobaan');
}
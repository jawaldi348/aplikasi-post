$(document).ready(function (e) {
    tampildatapembelian();
});
function tampildatapembelian() {
    table = $('#datapembelian').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildatapembelian',
            "type": "POST"
        },


        "columnDefs": [
            {
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [5],
                "orderable": false,
                "className": 'text-right'
            },
            {
                "targets": [6],
                "orderable": false,
                "className": 'text-right'
            }
        ],

    });
}

function hapusfaktur(faktur) {
    Swal.fire({
        title: 'Hapus Transaksi',
        text: "Yakin di hapus ?, semua data detail item pembelian juga akan terhapus !",
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
                            tampildatapembelian();
                        })
                    }
                }
            });
        }
    })
}

function editfaktur(faktur) {
    window.location = './edit/' + faktur;
}
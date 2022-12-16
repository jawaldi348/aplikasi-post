<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>

<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('admin/produk/index') ?>'">
                &laquo; Kembali
            </button>
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('admin/produk/home') ?>'">
                &laquo; Kembali ke Home
            </button>
            <button type="button" class="btn btn-primary btn-sm btnadd">
                <i class="fa fa-fw fa-plus-circle"></i> Tambah Paket
            </button>

        </div>
        <div class="card-body">
            <div class="card-text">
                <table style="font-size: 12px;" class="table table-sm table-striped table-bordered display nowrap"
                    id="dataproduk" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Paket</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Harga Jual (Rp)</th>
                            <th>Stok Tersedia</th>
                            <th>#</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
function detailpaket(kodebarcode) {
    let url = "<?= site_url('admin/produk/detail-paket/') ?>" + kodebarcode;
    let win = window.open(url, '_blank');
    win.focus();
}

function hapusproduk(id, nama) {
    Swal.fire({
        title: 'Hapus Paket',
        html: `Yakin menghapus paket <strong>${nama}</strong> ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#29a329',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/produk/hapuspaket') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: `${response.sukses}`,
                            showHideTransition: 'plain',
                            icon: 'success'
                        })
                        tampildataprodukpaket();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        }
    })

}

function tampildataprodukpaket() {
    table = $('#dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/produk/ambildataprodukpaket') ?>",
            "type": "POST"
        },


        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            "width": 5
        }],

    });
}
$(document).ready(function() {
    tampildataprodukpaket();

    $('.btnadd').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/produk/pakettambahdata') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modaltambahdata').on('shown.bs.modal', function(e) {
                        $('#kodeproduk').focus();
                    })
                    $('#modaltambahdata').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });

});
</script>
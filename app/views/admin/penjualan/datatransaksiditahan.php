<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card border-light mb-1 animated fadeInLeft">
        <div class="card-header">
            <?php
            $idgrup = $this->session->userdata('idgrup');
            if ($idgrup == 1) {
            ?>

            <button type="button" class="btn btn-sm btn-pinterest"
                onclick="window.location='<?= site_url('admin/penjualan/index') ?>'">
                <i class="fa fa-fast-backward" aria-hidden="true"></i> Kembali
            </button>
            <?php
            } else {
            ?>
            <button type="button" class="btn btn-sm btn-pinterest"
                onclick="window.location='<?= site_url('k/home/index') ?>'">
                <i class="fa fa-fast-backward" aria-hidden="true"></i> Kembali
            </button>
            <?php
            }
            ?>
        </div>
        <div class="card-body">
            <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                id="datatransaksi" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Faktur</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Jml.Item</th>
                        <th>Total (Rp)</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="viewmodalitem" style="display: none;"></div>
<script>
$(document).ready(function() {
    table = $('#datatransaksi').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildatatransaksiditahan',
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 3
            },
            {
                "targets": [4],
                "className": "text-center",
            }, {
                "targets": [5],
                "className": "text-right",
            }, {
                "targets": [6],
                "width": 5
            }
        ]
    });
});

// Hapus transaksi holding
function hapus(faktur) {
    Swal.fire({
        title: `Hapus Transaksi di Tahan`,
        html: `Yakin transaksi <strong>${faktur}</strong> dihapus ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya,Hapus',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/penjualan/hapustransaksiditahan') ?>",
                data: {
                    faktur: faktur
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: response.sukses,
                            showConfirmButton: true,
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
                        })
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

// edit transaksi
function edit(faktur) {
    window.location.href = ("<?= site_url('admin/penjualan/edittransaksiditahan/') ?>") + faktur;
}

function itempenjualan(faktur) {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/detail_itempenjualan') ?>",
        data: {
            faktur: faktur
        },
        dataType: "json",
        cache: false,
        success: function(response) {
            if (response.data) {
                $('.viewmodalitem').html(response.data).show();
                $('#modaldataitem').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}
</script>
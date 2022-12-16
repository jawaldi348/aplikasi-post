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
            <div class="col-lg-12">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-1 col-form-label">Filter</label>
                    <div class="col-sm-3">
                        <input type="date" name="tglawal" id="tglawal" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="date" name="tglakhir" id="tglakhir" class="form-control form-control-sm">
                    </div>
                    <?php
					if ($this->session->userdata('idgrup') == 1) :
					?>
                    <div class="col-sm-3">
                        <select name="users" id="users" class="form-control-sm form-control">
                            <option value="" selected>-Semua-</option>
                            <?php foreach ($datauser->result_array() as $d) : ?>
                            <option value="<?= $d['userid']; ?>"><?= $d['usernama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php
					endif;
					?>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-success btn-sm btnfilter">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                    id="datatransaksi" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faktur</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Stt.Bayar</th>
                            <th>User Input</th>
                            <th>Jml.Item</th>
                            <th>Total Bersih (Rp)</th>
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
<div class="viewmodalitem" style="display: none;"></div>
<script>
// function cetakfaktur(faktur) {
//     var top = window.screen.height - 400;
//     top = top > 0 ? top / 2 : 0;

//     var left = window.screen.width - 200;
//     left = left > 0 ? left / 2 : 0;

//     // var url = '.././pemasok/index';
//     var uploadWin = window.open('<?= site_url('admin/penjualan/cetakfaktur/') ?>' + faktur,
//         "Struk Kasir",
//         "width=200,height=400" + ",top=" + top +
//         ",left=" + left);
//     uploadWin.moveTo(left, top);
//     uploadWin.focus();
// }

function cetakfaktur(faktur) {
    pesan = confirm('Cetak Faktur ?');
    if (pesan) {
        // $.ajax({
        //     type: "post",
        //     url: "admin/penjualan/printDirect')",
        //     data: {
        //         faktur: faktur
        //     },
        //     success: function(response) {
        //         alert(response);
        //     }
        // });

        var top = window.screen.height - 400;
        top = top > 0 ? top / 2 : 0;

        var left = window.screen.width - 200;
        left = left > 0 ? left / 2 : 0;

        var url = "<?= site_url('admin/penjualan/cetakfaktur/') ?>" + faktur;
        var uploadWin = window.open(url,
            "Struk Kasir",
            "width=200,height=400" + ",top=" + top +
            ",left=" + left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();

    } else {
        return false;
    }
}

function tampildatapenjualan() {
    let tglawal = $('#tglawal').val();
    let tglakhir = $('#tglakhir').val();
    let users = $('#users').val();
    table = $('#datatransaksi').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/penjualan/ambilsemuadata') ?>",
            "type": "POST",
            "data": {
                tglawal: tglawal,
                tglakhir: tglakhir,
                users: users,
            }
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 3
            },
            {
                "targets": [6],
                "className": "text-center",
            }, {
                "targets": [7],
                "className": "text-right",
            }, {
                "targets": [8],
                "width": 5
            }
        ]
    });
}
$(document).ready(function() {
    tampildatapenjualan();

    $('.btnfilter').click(function(e) {
        e.preventDefault();
        tampildatapenjualan();
    });
});

// Hapus transaksi holding
function hapus(faktur) {
    Swal.fire({
        title: `Hapus Transaksi`,
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
    window.location.href = ("<?= site_url('admin/penjualan/edit/') ?>") + faktur;
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
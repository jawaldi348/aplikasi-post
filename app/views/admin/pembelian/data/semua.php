<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<link href="<?= base_url(); ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="form-group row">
        <label for="staticEmail" class="col-sm-1 col-form-label">Filter</label>
        <div class="col-sm-3">
            <input type="date" name="tglawal" id="tglawal" class="form-control form-control-sm">
        </div>
        <div class="col-sm-3">
            <input type="date" name="tglakhir" id="tglakhir" class="form-control form-control-sm">
        </div>
        <div class="col-sm-1">
            <button type="button" class="btn btn-success btn-sm btnfilter">
                Tampilkan
            </button>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <table class="table table-sm table-striped display nowrap" id="datasemua" style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Faktur</th>
                <th>Tgl.Faktur</th>
                <th>Pemasok</th>
                <th>Jenis Pembayaran</th>
                <th>Jml.Item</th>
                <th>Total Kotor(Rp.)</th>
                <th>Total Bersih(Rp.)</th>
                <th>Stt.Bayar</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="viewmodaldetail" style="display: none;"></div>
<div class="viewmodal" style="display: none;"></div>
<script>
function tampil() {
    let tglawal = $('#tglawal').val();
    let tglakhir = $('#tglakhir').val();
    table = $('#datasemua').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('beli/ambilsemuadata') ?>",
            "type": "POST",
            "data": {
                tglawal: tglawal,
                tglakhir: tglakhir,
            }
        },
        "columnDefs": [{
                "targets": [0, 9],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [6, 7, 8],
                "orderable": false,
            }
        ],

    });
}

function detailitem(faktur) {
    $.ajax({
        type: "post",
        url: "<?= site_url('beli/detailitempembelian') ?>",
        data: {
            faktur: faktur
        },
        dataType: "json",
        cache: false,
        success: function(response) {
            $('.viewmodaldetail').html(response.data).show();
            $('#modaldetailitem').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function hapus(no) {
    Swal.fire({
        title: 'Hapus Transaksi',
        html: `Yakin menghapus Faktur <strong>${no}</strong> ? <i>Semua item juga ikut terhapus</i>`,
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
                url: "<?= site_url('beli/bataltransaksi') ?>",
                data: {
                    faktur: no,
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.sukses,
                        });
                        tampil();
                    }

                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}

function edit(no) {
    window.location.href = ("<?= site_url('beli/edit-faktur/') ?>") + no;
}
$(document).ready(function() {
    tampil();

    $('.btnfilter').click(function(e) {
        e.preventDefault();
        tampil();
    });
});

function cetakpengeluarankas(faktur) {
    var win = window.open("<?= site_url('beli/cetakpengeluarankas/') ?>" + faktur, '_blank');
    win.focus();
    // $.ajax({
    //     type: 'post',
    //     data: {
    //         faktur: faktur
    //     },
    //     url: "beli/pilihyangmenerima",
    //     dataType: "json",
    //     success: function(response) {
    //         $('.viewmodal').html(response.data).show();
    //         $('#modalpilih').modal('show');
    //     }
    // });
}
</script>
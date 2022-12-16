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
                onclick="window.location='<?= site_url('admin/produk/home') ?>'">
                &laquo; Kembali
            </button>
            <button type="button" class="btn btn-primary btn-sm"
                onclick="window.location='<?= site_url('admin/produk/add') ?>'">
                <i class="fa fa-fw fa-plus-circle"></i> Tambah Produk
            </button>
            <button type="button" class="btn btn-primary btn-sm"
                onclick="window.location='<?= site_url('admin/produk/form-import') ?>'">
                <i class="fa fa-fw fa-file-import"></i> Import Excel
            </button>
            <button type="button" class="btn btn-success btn-sm"
                onclick="window.location='<?= site_url('admin/produk/export-produk') ?>'">
                <i class="fa fa-fw fa-file-export"></i> Export Produk Ke-Excel
            </button>
            <button type="button" class="btn btn-info btn-sm"
                onclick="window.location='<?= site_url('admin/produk/paket') ?>'">
                <i class="fa fa-fw fa-tasks"></i> Produk Paket
            </button>
            <button type="button" class="btn btn-info btn-warning"
                onclick="window.location='<?= site_url('admin/produk/recovery') ?>'">
                <i class="fa fa-fw fa-tasks"></i> Tampilkan Produk Yang Dihapus
            </button>
        </div>
        <div class="card-body">
            <?= form_open('admin/produk/hapus_multiple', ['class' => 'formdelete d-inline']) ?>
            <div class="card-text">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa fa-fw fa-trash-alt"></i> Hapus Produk Yang di Pilih
                </button>
                <br><br>
                <div class="form-group row">
                    <label for="sortir" class="col-sm-2 col-form-label">Sortir Produk</label>
                    <div class="col-sm-4">
                        <select class="form-control-sm form-control" name="sortir" id="sortir">
                            <option value="">-Semua-</option>
                            <option value="1">Stok Ada</option>
                            <option value="2">Stok Kosong</option>
                            <option value="3">Stok Minus</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control-sm form-control" name="kategori" id="kategori">
                            <option value="">-Semua Kategori-</option>
                            <?php foreach ($datakategori->result_array() as $k) : ?>
                            <option value="<?= $k['katid'] ?>"><?= $k['katnama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-primary btnsortir">
                            Tampilkan
                        </button>
                    </div>
                </div>
                <?= $this->session->flashdata('msg'); ?>
                <table class="table table-sm table-striped table-bordered display nowrap" id="dataproduk" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="check-all">
                            </th>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Satuan</th>
                            <th>Harga Beli (Rp)</th>
                            <th>Harga Jual<br>Eceran (Rp)</th>
                            <th>Harga Jual<br>Reseller (Rp)</th>
                            <th>Kategori</>
                            <th>Stok Tersedia</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="viewshowdetail" style="display: none;"></div>
<div class="viewmodalcetaklabel" style="display: none;"></div>
<script>
function cetaklabel(kodebarcode, namaproduk) {
    $.ajax({
        type: 'post',
        url: "<?= site_url('admin/produk/modalCetakLabel') ?>",
        dataType: "json",
        data: {
            kodebarcode: kodebarcode,
            nama: namaproduk
        },
        success: function(response) {
            if (response.data) {
                $('.viewmodalcetaklabel').html(response.data).show();
                $('#modalCetakLabel').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampildataproduk() {
    table = $('#dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('admin/produk/ambildataproduk') ?>",
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [5],
                "className": 'text-right'
            },
            {
                "targets": [6],
                "className": 'text-right'
            },
            {
                "targets": [7],
                "className": 'text-right'
            },
            {
                "targets": [9],
                "className": 'text-right'
            }
        ],
    });
}
$(document).ready(function(e) {
    tampildataproduk();
    $('#check-all').click(function(e) {
        if ($(this).is(":checked")) {
            $(".check-item").prop("checked", true);
        } else {
            $(".check-item").prop("checked", false);
        }
    });

    $('.btnsortir').click(function(e) {
        e.preventDefault();
        table = $('#dataproduk').DataTable({
            responsive: true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": "<?= site_url('admin/produk/ambildataproduk') ?>",
                "type": "POST",
                "data": {
                    sortir: $('#sortir').val(),
                    kategori: $('#kategori').val()
                }
            },


            "columnDefs": [{
                    "targets": [1],
                    "orderable": false,
                    "width": 5
                },
                {
                    "targets": [0],
                    "orderable": false,
                    "width": 5
                },
                {
                    "targets": [5],
                    "className": 'text-right'
                },
                {
                    "targets": [6],
                    "className": 'text-right'
                },
                {
                    "targets": [8],
                    "className": 'text-right'
                }
            ],
        });
    });
});

// Hapus Produk
function hapusproduk(id, nama) {
    Swal.fire({
        title: 'Hapus',
        text: `Yakin Menghapus Produk ${nama} ?`,
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
                url: "<?= site_url('admin/produk/hapusproduk') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        tampildataproduk();
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
$(document).on('submit', '.formdelete', function(e) {
    let pilih = $('.check-item:checked');

    if (pilih.length === 0) {
        Swal.fire('Perhatian', 'Tidak Ada item Produk yang di pilih untuk dihapus !', 'warning');
    } else {
        Swal.fire({
            title: 'Hapus Produk',
            text: `Yakin Menghapus ${pilih.length} Produk yang dipilih ?, Produk yang terhapus tidak permanen !`,
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
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
        })
    }

    return false;
});

// Form edit
function formedit(id) {
    window.location.href = ("<?= site_url('admin/produk/edit/') ?>") + id;
}

// Add Harga
function tambahharga(id) {
    window.location.href = ("<?= site_url('admin/produk/addharga/') ?>") + id;
}

// Menampilkan Detail Produk
function showDetail(kode) {
    $.ajax({
        type: "post",
        url: "<?= site_url('admin/produk/showdetail') ?>",
        data: {
            kode: kode
        },
        cache: false,
        success: function(response) {
            $('.viewshowdetail').html(response).show();
            $('#modalshowdetail').modal('show');
        }
    });
}
</script>
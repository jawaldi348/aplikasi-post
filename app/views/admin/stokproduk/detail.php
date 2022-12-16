<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning"
                onclick="window.location='<?= site_url('stokproduk/index') ?>'">
                <i class="fa fa-fw fa-backward"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <?php $kode = $row['kodebarcode']; ?>
            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-sm table-striped">
                        <tr>
                            <td style="width: 25%;">Kode</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $row['kodebarcode']; ?></td>
                            <input type="hidden" name="kode" id="kode" value="<?= $kode; ?>">
                        </tr>
                        <tr>
                            <td>Produk</td>
                            <td>:</td>
                            <td><?= $row['namaproduk']; ?></td>
                        </tr>
                        <tr>
                            <td>Satuan</td>
                            <td>:</td>
                            <td><?= $row['satnama']; ?></td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>:</td>
                            <td><?= $row['katnama']; ?></td>
                        </tr>
                        <tr>
                            <td>Harga Modal (Rp.)</td>
                            <td>:</td>
                            <td style="text-align: left;"><?= number_format($row['harga_beli_eceran'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Harga Jual (Rp.)</td>
                            <td>:</td>
                            <td style="text-align: left;"><?= number_format($row['harga_jual_eceran'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Stok Tersedia</td>
                            <td>:</td>
                            <td style="font-weight: bold; font-size:14pt;">
                                <?= number_format($row['stok_tersedia'], 0, ",", "."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Stok Keluar s.d Saat Ini</td>
                            <td>:</td>
                            <td style="font-weight: bold; font-size:14pt;">
                                <?php
                                $query_penjualan_detail = $this->db->query("SELECT SUM(detjualsatqty * detjualjml - detjualjmlreturn) AS totaljual FROM penjualan_detail WHERE detjualkodebarcode = '$kode'")->row_array();

                                echo number_format($query_penjualan_detail['totaljual'], 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Stok Masuk s.d Saat Ini</td>
                            <td>:</td>
                            <td style="font-weight: bold; font-size:14pt;">
                                <?php
                                $query_pembelian_detail = $this->db->query("SELECT SUM(detqtysat * detjml - detjmlreturn) AS totalmasuk FROM pembelian_detail WHERE detkodebarcode = '$kode'")->row_array();

                                echo number_format($query_pembelian_detail['totalmasuk'], 0, ",", ".");
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-sm-6">
                    <div class="card m-b-30">
                        <div class="card-header">
                            Daftar Stok Per-Tanggal Kadaluarsa <button type="button"
                                class="btn btn-primary btn-sm tomboltambah">Tambah Produk Kadaluarsa</button>
                        </div>
                        <?= form_open('stokproduk/simpanbatchjml', ['class' => 'formsimpan']) ?>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-sm btn-info btnedit">Edit</button>
                                    <button type="submit" class="btn btn-sm btn-success btnsimpan"
                                        style="display: none;">Simpan</button>
                                    <button type="button" class="btn btn-sm btn-danger btntutup"
                                        style="display: none;">Tutup</button>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-lg-12">
                                    <table class="table table-sm table-striped table-bordered display nowrap"
                                        id="dataproduk_tglkadaluarsa" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tgl.Kadaluarsa</th>
                                                <th>Jml</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $nomor = 0;
                                            $total_jml = 0;
                                            foreach ($datakadaluarsa->result_array() as $row) :
                                                $nomor++;
                                                // $tglkadaluarsa = date_create($row['tglkadaluarsa']);
                                                // $tglhariini = date_create();
                                                // $hitung = date_diff($tglkadaluarsa, $tglhariini);
                                                // $sisabulan = $hitung->m;
                                                // $sisahari = $hitung->d;
                                                $query_cekkadaluarsa = $this->db->query("SELECT TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) AS selisih_bulan FROM produk_tglkadaluarsa WHERE kodebarcode='$kode'");

                                                if ($query_cekkadaluarsa->num_rows() > 0) {
                                                    $row_kadaluarsa = $query_cekkadaluarsa->row_array();
                                                    $selisih_bulan = $row_kadaluarsa['selisih_bulan'];
                                                    if ($selisih_bulan <= 6 && $selisih_bulan > 3) {
                                                        $warna = "style='background-color:green;'";
                                                    }
                                                    if ($selisih_bulan <= 3 && $selisih_bulan > 0) {
                                                        $warna = "style='background-color:yellow;'";
                                                    }
                                                    if ($selisih_bulan <= 0) {
                                                        $warna = "style='background-color:red;'";
                                                    }
                                                } else {
                                                    $warna = '';
                                                }

                                                $total_jml = $total_jml + $row['jml'];
                                                // echo $sisabulan;
                                            ?>
                                            <tr <?= $warna; ?>>
                                                <td style="color: #000;"><?= $nomor; ?></td>
                                                <td><?= $row['tglkadaluarsa']; ?></td>
                                                <td><span
                                                        class="datajml"><?= number_format($row['jml'], 0, ",", "."); ?></span>
                                                    <input type="text" class="form-control form-control-sm inputjml"
                                                        name="jml[]" style="display: none;"
                                                        value="<?= number_format($row['jml'], 0, ",", "."); ?>">

                                                    <input type="hidden" name="id[]" value="<?= $row['id']; ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        title="Hapus Data"
                                                        onclick="hapusstokkadaluarsa('<?= $row['id']; ?>','<?= $row['tglkadaluarsa']; ?>')">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <table class="table table-striped display nowrap">
                                        <tr>
                                            <th>Total Jumlah Stok</th>
                                            <td>:</td>
                                            <th><?= $total_jml; ?></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <p>
                            <blockquote class="card-bodyquote">
                                <footer>
                                    <div class="alert alert-info">
                                        <ol>
                                            <li>Stok Produk Per-tanggal kadaluarsa, harus dicek setiap saat</li>
                                            <li>Edit Stok yang ada, jika ada yg salah !!!</li>
                                            <li>Hapus jika stoknya kosong</li>
                                        </ol>
                                    </div>
                                </footer>
                            </blockquote>
                            </p>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
function hapusstokkadaluarsa(id, tgl) {
    Swal.fire({
        title: 'Hapus',
        html: `Yakin menghapus stok produk dengan tgl.kadaluarsa <strong>${tgl}</strong>`,
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
                url: "<?= site_url('stokproduk/hapusstok') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        window.location.reload();
                    }
                }
            });
        }
    })
}
$(document).ready(function() {
    var table = $('#dataproduk_tglkadaluarsa').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,

    });

    $('.btnedit').click(function(e) {
        e.preventDefault();
        $('.datajml').hide();
        $('.inputjml').fadeIn();
        $('.btnsimpan').fadeIn();
        $('.btntutup').fadeIn();
    });

    $('.btntutup').click(function(e) {
        e.preventDefault();
        $('.inputjml').hide();
        $('.datajml').fadeIn();
        $('.btnsimpan').hide();
        $(this).hide();
    });

    // Simpan data
    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Validasi',
            html: `Yakin menyimpan data Jumlah stok yang telah di edit ?`,
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
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function(e) {
                        $('.btnsimpan').html(
                            '<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.btnsimpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                html: response.sukses
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
        return false;
    });

    $('.tomboltambah').click(function(e) {
        e.preventDefault();
        let kode = $('#kode').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('stokproduk/tambahstok') ?>",
            data: {
                kode: kode
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modaltambah').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });
});

function hapus(id, jml) {
    Swal.fire({
        title: 'Hapus Data',
        html: `Yakin menghapus data ini ?`,
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
                url: "<?= site_url('stokproduk/hapusdatatglkadaluarsa') ?>",
                data: {
                    id: id,
                    jml: jml
                },
                dataType: 'json',
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            html: response.sukses
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
                        })
                    }
                    if (response.error) {
                        Swal.fire(
                            'Error',
                            response.error,
                            'error'
                        );
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}
</script>
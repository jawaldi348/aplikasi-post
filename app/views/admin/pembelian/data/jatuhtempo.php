<!-- DataTables -->
<link href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<link href="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="<?= base_url() ?>assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />

<!-- Required datatable js -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('beli/index') ?>'">
                <i class="fa fa-backward"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm" style="width: 100%;" id="datahutang">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Faktur</th>
                        <th>Tgl.Faktur</th>
                        <th>Supplier</th>
                        <th>Tgl.Jatuh Tempo</th>
                        <th style="width: 15%;">Total Bersih (Rp)</th>
                        <th>
                            #
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $nomor = 0;
                    foreach ($data->result_array() as $d) : $nomor++;
                    ?>
                    <tr>
                        <td><?= $nomor; ?></td>
                        <td><?= $d['nofaktur']; ?></td>
                        <td><?= date('d-m-Y', strtotime($d['tglbeli'])); ?></td>
                        <td><?= $d['namapemasok']; ?></td>
                        <td><?= date('d-m-Y', strtotime($d['tgljatuhtempo'])); ?></td>
                        <td style="text-align: right;"><?= number_format($d['totalbersih'], 2, ".", ","); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-info dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Aksi
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item"
                                        href="<?= site_url('beli/bayar-hutang/' . sha1($d['nofaktur'])) ?>">Bayar</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
$(document).ready(function() {
    $('#datahutang').DataTable();
});
</script>
<!-- Modal -->
<div class="modal fade" id="modaltransaksiditahan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Transaksi Di Tahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table style="font-size: 10pt;" class="table table-sm table-striped table-bordered display nowrap"
                    id="transaksiditahan" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Faktur</th>
                            <th>Tgl</th>
                            <th>Nama Pelanggan</th>
                            <th>Jml.Item</th>
                            <th>Total (Rp)</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        foreach ($tampildata->result_array() as $row) : $nomor++;
                            $detail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $row['jualfaktur']])->result();
                            $jmlitem = count($detail);
                        ?>

                        <tr>
                            <td><?= $nomor ?></td>
                            <td><?= $row['jualfaktur']; ?></td>
                            <td><?= date('d-m-Y', strtotime($row['jualtgl'])); ?></td>
                            <td><?= $row['jualnapel']; ?></td>
                            <td><?= $jmlitem; ?></td>
                            <td><?= number_format($row['jualtotalkotor'], 0, ".", ","); ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="edittransaksi('<?= sha1($row['jualfaktur']) ?>')">
                                    Pilih
                                </button>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function edittransaksi(faktur) {
    window.location.href = ("<?= site_url('kasir/edittransaksiditahan/') ?>") + faktur;
}
</script>
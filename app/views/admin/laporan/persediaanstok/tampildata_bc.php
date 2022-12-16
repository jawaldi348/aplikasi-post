<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-body">
            <table id="dataproduk" class="table table-sm table-striped display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode Barcode</th>
                        <th>Nama Produk</th>
                        <th>Stok <br> Sekarang</th>
                        <th>Harga Modal<br>(Rp)</th>
                        <th>Saldo<br>(Rp)</th>
                        <th>Stok <br> Masuk</th>
                        <th>Stok <br> Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $nomor = 0;
                    $totalstoksekarang = 0;
                    $totalhargabeli = 0;
                    $totalpersediaan = 0;
                    $totalseluruhmasuk = 0;
                    $totalseluruhkeluar = 0;
                    foreach ($dataproduk->result_array() as $row) : $nomor++;
                        $totalstoksekarang += $row['stoksekarang'];
                        $totalhargabeli += $row['hargabeli'];
                        $totalpersediaan += $row['subtotalpersediaan'];
                        $totalseluruhmasuk += $row['totalmasuk'];
                        $totalseluruhkeluar += $row['totalkeluar'];
                    ?>
                    <tr>
                        <td><?= $row['kodebarcode']; ?></td>
                        <td><?= $row['namaproduk']; ?></td>
                        <td><?= number_format($row['stoksekarang'], 0, ",", "."); ?></td>
                        <td><?= number_format($row['hargabeli'], 2, ",", "."); ?></td>
                        <td><?= number_format($row['subtotalpersediaan'], 2, ",", "."); ?></td>
                        <td><?= number_format($row['totalmasuk'], 0, ",", "."); ?></td>
                        <td><?= number_format($row['totalkeluar'], 0, ",", "."); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <!-- <tfoot>
                    <tr>
                        <th style="text-align: center;" colspan="2">Total</th>
                        <td><?//= number_format($totalstoksekarang, 0, ",", "."); ?></td>
                        <td><?//= number_format($totalhargabeli, 2, ",", "."); ?></td>
                        <td><?//= number_format($totalpersediaan, 2, ",", "."); ?></td>
                        <td><?//= number_format($totalseluruhmasuk, 0, ",", "."); ?></td>
                        <td><?//= number_format($totalseluruhkeluar, 0, ",", "."); ?></td>
                    </tr>
                </tfoot> -->
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="tgl" id="tgl"
    value="<?= date('d M Y', strtotime($tglawal)) . '-' . date('d M Y', strtotime($tglakhir)); ?>">
<script>
function tampildata() {
    let tanggal = $('#tgl').val();
    $('#dataproduk').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy',
            {
                extend: 'excelHtml5',
                title: 'Persediaan Stok Produk ' + tanggal
            },
            {
                extend: 'pdfHtml5',
                title: 'Persediaan Stok Produk ' + tanggal
            },
            'print'
        ]
    });
}
$(document).ready(function() {
    tampildata();
});
</script>
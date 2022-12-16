<?php
if ($itemproduk->num_rows() > 0) :
    $jml = 0;
    $totalhargabeli = 0;
    $totalhargajual = 0;
    foreach ($itemproduk->result_array() as $r) :
        $jml = $jml + $r['paketjml'];
        $totalhargabeli = $totalhargabeli + $r['pakethargabeli'];
        $totalhargajual = $totalhargajual + $r['pakethargajual'];
?>
<tr>
    <td><?= $r['paketkodebarcode']; ?></td>
    <td><?= $r['paketnamaproduk']; ?></td>
    <td><?= number_format($r['paketjml'], 0, ",", "."); ?></td>
    <td style="text-align: right;"><?= number_format($r['pakethargabeli'], 2, ",", "."); ?></td>
    <td style="text-align: right;"><?= number_format($r['pakethargajual'], 2, ",", "."); ?></td>
    <td>
        <button type="button" class="btn btn-sm btn-danger" title="Hapus Item"
            onclick="hapusitem('<?= $r['paketid'] ?>','<?= $r['paketkodebarcode'] ?>','<?= $r['paketnamaproduk'] ?>')">
            <i class="fa fa-trash-alt"></i>
        </button>
    </td>
</tr>
<?php endforeach; ?>
<tr style="font-weight: bold;">
    <th colspan="2">Total</th>
    <td><?= $jml; ?></td>
    <td style="text-align: right;"><?= number_format($totalhargabeli, 2, ",", "."); ?></td>
    <td style="text-align: right;"><?= number_format($totalhargajual, 2, ",", "."); ?></td>
    <td></td>
</tr>
<?php else : ?>
<tr>
    <td colspan="5">Data Belum ada...</td>
</tr>

<?php endif ?>
<script>
function hapusitem(id, kode, nama) {
    Swal.fire({
        title: 'Hapus Item',
        html: `Yakin menghapus paket item <strong>${kode} / ${nama}</strong> ?`,
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
                url: "<?= site_url('admin/produk/hapuspaketitem') ?>",
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
                        tampildataitemproduk();
                        kosonginput();
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
</script>
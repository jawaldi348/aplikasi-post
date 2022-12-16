<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>No.Akun</th>
            <th>Nama Akun</th>
            <th>Tgl.Input</th>
            <th>Jumlah (Rp)</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tampildata->result_array() as $row) : ?>
        <tr>
            <td><?= $row['noakun']; ?></td>
            <td>
                <?php
                    if ($row['kat'] == '0') {
                        echo "<strong>$row[namaakun]</strong>";
                    } else {
                        echo "$row[namaakun]";
                    }
                    ?>
            </td>
            <td>
                <?php
                    if ($row['kat'] == '0') {
                        echo "-";
                    } else {
                        if ($row['tglsetdef'] == '' || $row['tglsetdef'] == NULL || $row['tglsetdef'] == "0000-00-00") {
                            echo "";
                        } else {
                            echo date('d-m-Y', strtotime($row['tglsetdef']));
                        }
                    }
                    ?>
            </td>
            <td style="text-align: right;">
                <?php
                    if ($row['kat'] == '0') {
                        echo "-";
                    } else {
                        echo number_format($row['jmlsetdef'], 0, ",", ".");
                    }
                    ?>
            </td>
            <td>
                <?php
                    if ($row['kat'] == '0') {
                        echo "-";
                    } else {
                    ?>
                <button type="button" title="Add Jumlah" class="btn btn-sm btn-primary"
                    onclick="editdata('<?= $row['noakun'] ?>')">
                    <i class="fa fa-plus-circle"></i>
                </button>
                <?php
                    }
                    ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
function editdata(no) {
    $.ajax({
        type: "post",
        url: "<?= site_url('neraca/editdata') ?>",
        data: {
            noakun: no
        },
        dataType: "json",
        success: function(response) {
            if (response.data) {
                $('.viewmodal').html(response.data).show();
                $('#modalakunneraca').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
</script>
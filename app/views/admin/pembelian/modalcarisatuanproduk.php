<!-- Modal -->
<div class="modal fade" id="modalcarisatuanproduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Satuan Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Pilih Satuan</label>
                    <select name="satuan" id="satuan" class="form-control">
                        <option value="" selected>-Pilih-</option>
                        <?php foreach ($datasatuanproduk->result_array() as $s) : ?>
                        <option value="<?= $s['idsat']; ?>"><?= $s['satnama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-primary btnpilih">
                        Pilih
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.btnpilih').click(function(e) {
        e.preventDefault();

        let satuan = $('#satuan').val();
        let kode = $('#kode').val();

        if (satuan == '') {
            $.toast({
                heading: 'Information',
                text: `Silahkan Pilih satuan`,
                icon: 'warning',
                loader: true,
                loaderBg: '#9EC600',
                position: 'top-center'
            });
        } else {
            $('#idsatuan').val(satuan);
            $('#modalcarisatuanproduk').on('hidden.bs.modal', function(e) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('beli/ambildataprodukharga') ?>",
                    data: {
                        kode: kode,
                        satuan: satuan
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            let data = response.sukses;
                            $('#namasatuan').val(data.namasatuan);
                            $('#hargabeli').val(data.hargabeli);
                            $('#hargajual').val(data.hargajual);
                            $('#margin').val(data.margin);
                            $('#qtysatuan').val(data.jmldefault);
                            $('#idprodukharga').val(data.idprodukharga);
                        }
                    }
                });
            })
            $('#modalcarisatuanproduk').modal('hide');
        }
    });
});
</script>
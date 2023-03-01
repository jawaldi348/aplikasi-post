<input type="hidden" name="id" value="<?= $data['kode_member'] ?>">
<div class="form-group">
    <label>Nama Member</label>
    <input type="text" name="nama" id="nama" class="form-control form-control-sm" value="<?= $data['nama_member'] ?>">
</div>
<div class="form-group">
    <label>Tempat Lahir</label>
    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control form-control-sm" value="<?= $data['tempat_lahir'] ?>">
</div>
<div class="form-group">
    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm" value="<?= $data['tanggal_lahir'] ?>">
</div>
<div class="form-group">
    <label>Jenis Kelamin</label>
    <select name="jenkel" id="jenkel" class="form-control-sm form-control" value="<?= $data[''] ?>">
        <option value="">-Pilih-</option>
        <option value="L" <?= $data['jenkel_member'] == 'L' ? 'selected' : '' ?>>Laki-Laki</option>
        <option value="P" <?= $data['jenkel_member'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>
</div>
<div class="form-group">
    <label>Alamat</label>
    <input type="text" name="alamat" id="alamat" class="form-control form-control-sm" value="<?= $data['alamat_member'] ?>">
</div>
<div class="form-group">
    <label>Telp/HP</label>
    <input type="text" name="telp" id="telp" class="form-control form-control-sm" value="<?= $data['telp_member'] ?>">
</div>
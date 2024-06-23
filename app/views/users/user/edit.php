<input type="hidden" name="id" value="<?= $data['id_user'] ?>">
<div class="form-group">
    <label for="nama">Nama Lengkap</label>
    <input type="text" name="nama" id="nama" class="form-control" value="<?= $data['nama_user'] ?>">
</div>
<div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" class="form-control" value="<?= $data['username'] ?>">
</div>
<div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" class="form-control">
    <div class="text-muted">Kosongkan jika tidak rubah password</div>
</div>
<div class="form-group">
    <label for="idgroup">Grup User</label>
    <select name="group" id="idgroup" class="form-control">
        <option value="<?= $data['id_group'] ?>"><?= $data['nama_group'] ?></option>
    </select>
    <div id="group"></div>
</div>
<script>
    $(document).ready(function(e) {
        $('#idgroup').select2({
            placeholder: 'Pilih Grup User',
            width: '100%',
            ajax: {
                url: BASE_URL + 'group-user/autocomplete',
                type: 'get',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    })
</script>
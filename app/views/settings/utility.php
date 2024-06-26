@extends(layouts/index)
@section(content)
<div class="col-lg-6">
    <div class="card m-b-30">
        <div class="card-header bg-primary text-white">
            Backup Database
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                Klik Tombol Berikut, Untuk membackup database
            </div>
            <?= $this->session->flashdata('pesan'); ?>
            <br>
            <button type="button" class="btn btn-sm btn-success" onclick="window.location='<?= site_url('utility/backup') ?>'">BackUp Database</button>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card m-b-30">
        <div class="card-header bg-success text-white">
            Restore Database
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('pesanrestore'); ?>
            <?= form_open_multipart('utility/restore') ?>
            <div class="form-group">
                <label for="">Upload File .sql</label>
                <input type="file" class="form-control" name="uploadfile" accept=".sql">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-sm btn-primary">
                    Restore Database
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
@endsection
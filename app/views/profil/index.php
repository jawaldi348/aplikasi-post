<div class="col-md-12 col-lg-12 col-xl-2">
    <div class="card m-b-30">
        <?php if ($foto == NULL || $foto == '' || !file_exists($foto)) : ?>
        <img class="card-img-top img-fluid" src="<?= base_url() ?>assets/images/users/avatar.png" alt="Foto User">
        <?php else : ?>
        <img class="card-img-top img-fluid" src="<?= base_url($foto) ?>" alt="Foto User">
        <?php endif; ?>
        <div class="card-body">
            <a href="#" class="btn btn-primary waves-effect waves-light btn-block btngantifoto">Ganti Foto Profil</a>
        </div>
    </div>
</div>
<div class="col-md-12 col-lg-12 col-xl-10">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-pinterest waves-effect waves-light btngantiprofil">
                <i class="fa fa-user"></i> Update Profil
            </button>
            <button type="button" class="btn btn-twitter waves-effect waves-light btngantipassword">
                <i class="fa fa-lock"></i> Ganti Password
            </button>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('pesan'); ?>
            <table class="table table-striped">
                <tr>
                    <td style="width: 20%;">ID User </td>
                    <td style="width: 2%;">:</td>
                    <td><?= $iduser; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Nama lengkap user </td>
                    <td style="width: 2%;">:</td>
                    <td><?= $namauser; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Grup</td>
                    <td style="width: 2%;">:</td>
                    <td><?= $namagrup; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script src="<?= base_url('assets/novinaldi/profil.js') ?>"></script>
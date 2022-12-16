<?php $logo = $this->session->userdata('logo'); ?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="<?= $this->config->item('title'); ?>" name="<?= $this->config->item('author'); ?>" />
    <meta content="<?= $this->config->item('title'); ?>" name="<?= $this->config->item('author'); ?>" />
    <link rel="shortcut icon" href="<?= base_url($logo); ?>">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    <!-- Fontawesome -->
    <link href="<?= base_url(); ?>assets/plugins/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="<?= base_url(); ?>assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- Load Plugin Toast -->
    <script src="<?= base_url(); ?>assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.js">
    </script>
    <link href="<?= base_url(); ?>assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet"
        type="text/css">

    <!-- SweetAlert -->
    <script src="<?= base_url(); ?>assets/plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/dist/sweetalert2.min.css" type="text/css">

    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>

    <title><?= $this->session->userdata('namatoko'); ?></title>
</head>

<body>
    <div class="container-fluid mb-4">
        {isi}
    </div>
    <!-- Footer -->
    <!-- <footer class="footer mt-auto py-3 fixed-bottom bg-primary">
        <div class="container">
            <span class="text-muted">&copy; Kopmart Dinas Pendidikan 2020</span>
        </div>
    </footer> -->
    <!-- Footer -->

    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>
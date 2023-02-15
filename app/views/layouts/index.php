<?php $logo = $this->session->userdata('logo'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= $title . ' &mdash; ' . bisnis() ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?= logo() ?>">

    @provide(style)
    <link href="<?= assets() ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= assets() ?>css/icons.css" rel="stylesheet">
    <link href="<?= assets() ?>css/style.css" rel="stylesheet">
    <link href="<?= assets() ?>plugins/animate/animate.min.css" rel="stylesheet">
    <link href="<?= assets() ?>plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="<?= assets() ?>plugins/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet">
    <link href="<?= assets() ?>plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>
    <header id="topnav">
        <?php $this->load->view('layouts/top-bar') ?>
        <?php $this->load->view('layouts/navbar') ?>
    </header>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="btn-group pull-right">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item">
                                    <?= date('D, d-M-y'); ?>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span id="jam"></span>&nbsp;<span id="menit"></span>&nbsp;<span id="detik"></span>
                                </li>
                            </ol>
                        </div>
                        <h4 class="page-title"><?= $title ?></h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Content Starts -->
                @provide(content)
                <!-- Content ends -->
            </div>
        </div>
    </div>
    <?php $this->load->view('layouts/footer') ?>

    <script src="<?= assets() ?>js/jquery.min.js"></script>
    <script src="<?= assets() ?>js/popper.min.js"></script>
    <script src="<?= assets() ?>js/bootstrap.min.js"></script>
    <script src="<?= assets() ?>js/modernizr.min.js"></script>
    <script src="<?= assets() ?>js/waves.js"></script>
    <script src="<?= assets() ?>js/jquery.slimscroll.js"></script>
    <script src="<?= assets() ?>js/jquery.nicescroll.js"></script>
    <script src="<?= assets() ?>js/jquery.scrollTo.min.js"></script>
    @provide(script)
    <script src="<?= assets() ?>js/app.js"></script>
    <script src="<?= assets() ?>plugins/fontawesome/js/all.min.js"></script>
    <script src="<?= assets() ?>plugins/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
    <script src="<?= assets() ?>plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        window.setTimeout("waktu()", 1000);

        function waktu() {
            var waktu = new Date();
            setTimeout("waktu()", 1000);
            document.getElementById("jam").innerHTML = waktu.getHours() +
                ` : `;
            document.getElementById("menit").innerHTML = waktu.getMinutes() +
                ` : `;;
            document.getElementById("detik").innerHTML = waktu.getSeconds();
        }
    </script>

</html>
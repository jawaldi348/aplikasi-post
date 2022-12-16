<?php $logo = $this->session->userdata('logo'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= $this->config->item('title'); ?></title>
    <meta content="<?= $this->config->item('title'); ?>" name="<?= $this->config->item('author'); ?>" />
    <meta content="<?= $this->config->item('title'); ?>" name="<?= $this->config->item('author'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?= base_url($logo); ?>">

    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/plugins/animate/animate.min.css" rel="stylesheet" type="text/css">

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
</head>


<body>
    <?php
    $idtoko = $this->session->userdata('idtoko');
    $ambiltoko = $this->db->get_where('nn_namatoko', ['idtoko' => $idtoko]);
    $row = $ambiltoko->row_array();
    $logotoko = $row['logo'];

    if ($this->session->userdata('fotouser') == '' || $this->session->userdata('fotouser') == null || !file_exists($this->session->userdata('fotouser'))) {
        $fotouser = "./assets/images/users/avatar.png";
    } else {
        $fotouser = $this->session->userdata('fotouser');
    }
    ?>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Navigation Bar-->
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">

                <!-- Logo container-->
                <div class="logo">
                    <!-- Text Logo -->
                    <!--<a href="index.html" class="logo">-->
                    <!--Annex-->
                    <!--</a>-->
                    <!-- Image Logo -->
                    <a href="#" class="logo" onclick="window.location.reload();">
                        <img src="<?= base_url($logotoko); ?>" alt="Belum ada logo" height="40" class="logo-large">
                    </a>

                </div>
                <!-- End Logo container-->


                <div class="menu-extras topbar-custom">
                    <?php
                    // Notifikasi kadaluarsa Produk
                    $query_kadaluarsa = $this->db->query("SELECT produk_tglkadaluarsa.*, TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) AS selisih_bulan, namaproduk FROM produk_tglkadaluarsa JOIN produk ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` WHERE TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) < 1");
                    $totaldata = $query_kadaluarsa->num_rows();
                    ?>
                    <ul class="list-inline float-right mb-0">
                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-bell-outline noti-icon"></i>
                                <span class="badge badge-success noti-icon-badge"><?= $totaldata; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5>Daftar Produk Kadaluarsa</h5>
                                </div>
                                <?php foreach ($query_kadaluarsa->result_array() as $rr) : ?>
                                <!-- item-->
                                <a href="<?= site_url('stokproduk/detailproduk/' . sha1($rr['kodebarcode'])); ?>"
                                    class="dropdown-item notify-item">
                                    <div class="notify-icon bg-danger"><i class="fa fa-tasks"></i></div>

                                    <p class="notify-details"><b>
                                            <?= $rr['kodebarcode'] ?></b><small
                                            class="text-muted"><?= $rr['namaproduk']; ?><br>
                                            Tgl.Kadaluarsa
                                            <?= "<strong>" . date('d-m-Y', strtotime($rr['tglkadaluarsa'])) . "</strong>" ?>
                                        </small></p>
                                </a>
                                <?php endforeach; ?>

                            </div>
                        </li>
                        <!-- User-->
                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?= base_url($fotouser); ?>" alt="user" class="rounded-circle">
                                <?php echo $this->session->userdata('namalengkapuser') ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5>Halo !</h5>
                                </div>
                                <a class="dropdown-item" href="<?= site_url('profil/index') ?>"><i
                                        class="mdi mdi-account-circle m-r-5 text-muted"></i> Profil</a>
                                <a class="dropdown-item" href="<?= site_url('login/logout'); ?>"><i
                                        class="mdi mdi-logout m-r-5 text-muted"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                        <li class="menu-item list-inline-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle nav-link">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>

                    </ul>
                </div>
                <!-- end menu-extras -->

                <div class="clearfix"></div>

            </div> <!-- end container -->
        </div>
        <!-- end topbar-main -->

        <!-- MENU Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <div id="navigation">
                    <!-- Navigation Menu-->


                    {menu}

                    <!-- End navigation menu -->
                </div> <!-- end #navigation -->
            </div> <!-- end container -->
        </div> <!-- end navbar-custom -->
    </header>
    <!-- End Navigation Bar-->


    <div class="wrapper">
        <div class="container-fluid">

            <!-- Page-Title -->
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
                        <h4 class="page-title">
                            {judul}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="row">
                {isi}
            </div>
            <!-- end page title end breadcrumb -->

        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->


    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    © <?= date('Y') . ' ' . $this->config->item('title'); ?>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <!-- jQuery  -->
    <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/modernizr.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/waves.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="<?= base_url(); ?>assets/js/app.js"></script>
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
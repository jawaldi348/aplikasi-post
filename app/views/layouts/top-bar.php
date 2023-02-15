<div class="topbar-main">
    <div class="container-fluid">
        <div class="logo">
            <a href="#" class="logo" onclick="window.location.reload();">
                <img src="<?= logo() ?>" alt="<?= bisnis() ?>" height="40" class="logo-large">
            </a>
        </div>
        <div class="menu-extras topbar-custom">
            <ul class="list-inline float-right mb-0">
                <li class="list-inline-item dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-bell-outline noti-icon"></i>
                        <span class="badge badge-success noti-icon-badge">1</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                        <div class="dropdown-item noti-title">
                            <h5>Daftar Produk Kadaluarsa</h5>
                        </div>
                        <a href="1" class="dropdown-item notify-item">
                            <div class="notify-icon bg-danger"><i class="fa fa-tasks"></i></div>
                            <p class="notify-details">
                                <b>1</b>
                                <small class="text-muted">Produk1<br>
                                    Tgl.Kadaluarsa <strong>01-01-1999</strong></small>
                            </p>
                        </a>
                    </div>
                </li>
                <li class="list-inline-item dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?= assets() ?>images/users/avatar.png" alt="user" class="rounded-circle"> Admin
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <div class="dropdown-item noti-title">
                            <h5>Halo !</h5>
                        </div>
                        <a class="dropdown-item" href="<?= site_url('profil') ?>"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profil</a>
                        <a class="dropdown-item" href="<?= site_url('logout'); ?>"><i class="mdi mdi-logout m-r-5 text-muted"></i>
                            Logout
                        </a>
                    </div>
                </li>
                <li class="menu-item list-inline-item">
                    <a class="navbar-toggle nav-link">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
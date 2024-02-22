<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/dashboard" class="brand-link">
    <img src="<?= base_url('assets/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?= service('settings')->get('App.siteName'); ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <a href="/profile" class="d-block">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?= base_url('assets/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <?= user()->username ?>
        </div>
      </div>
    </a>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-header">Dashboard</li>
        <li class="nav-item">
          <a href="/dashboard" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">Data</li>
        <?php if (in_groups(['warga', 'admin'])) : ?>
          <li class="nav-item">
            <a href="/keluarga" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Keluarga</p>
            </a>
          </li>
        <?php endif ?>

        <?php if (in_groups(['admin', 'operator_kelurahan'])) : ?>
          <li class="nav-item">
            <a href="/penduduk" class="nav-link">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>Penduduk</p>
            </a>
          </li>
        <?php endif ?>

        <?php if (in_groups(['admin', 'operator_kelurahan'])) : ?>
          <li class="nav-item">
            <a href="/pengguna" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>Pengguna</p>
            </a>
          </li>
        <?php endif ?>

        <?php if (in_groups(['admin'])) : ?>
          <li class="nav-header">Settings</li>
          <li class="nav-item">
            <a href="/settings" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
        <?php endif ?>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Starter</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">

  <?= $this->renderSection('styles'); ?>

  <style>
    .max-height-300 pre{max-height:300px}.theme-switch{display:inline-block;height:24px;position:relative;width:50px}.theme-switch input{display:none}.slider{background-color:#ccc;bottom:0;cursor:pointer;left:0;position:absolute;right:0;top:0;transition:400ms}.slider::before{background-color:#fff;bottom:4px;content:"";height:16px;left:4px;position:absolute;transition:400ms;width:16px}input:checked+.slider{background-color:#66bb6a}input:checked+.slider::before{transform:translateX(26px)}.slider.round{border-radius:34px}.slider.round::before{border-radius:50%}
  </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse dark-mode">
  <div class="wrapper">

    <!-- Navbar -->
    <?= $this->include('components/navbar'); ?>

    <!-- Main Sidebar Container -->
    <?= $this->include('components/sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <?= $this->include('components/content-header'); ?>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <?= $this->renderSection('content'); ?>
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?= $this->include('components/footer'); ?>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <?= $this->renderSection('required_scripts'); ?>

  <!-- jQuery -->
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

  <!-- Others Scripts -->
  <?= $this->renderSection('scripts'); ?>

  <script>
    var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    var currentTheme = localStorage.getItem('theme');
    var mainHeader = document.querySelector('.main-header');

    if (currentTheme) {
      if (currentTheme === 'dark') {
        if (!document.body.classList.contains('dark-mode')) {
          document.body.classList.add("dark-mode");
        }
        if (mainHeader.classList.contains('navbar-light')) {
          mainHeader.classList.add('navbar-dark');
          mainHeader.classList.remove('navbar-light');
        }
        toggleSwitch.checked = true;
      }
    }

    function switchTheme(e) {
      if (e.target.checked) {
        if (!document.body.classList.contains('dark-mode')) {
          document.body.classList.add("dark-mode");
        }
        if (mainHeader.classList.contains('navbar-light')) {
          mainHeader.classList.add('navbar-dark');
          mainHeader.classList.remove('navbar-light');
        }
        localStorage.setItem('theme', 'dark');
      } else {
        if (document.body.classList.contains('dark-mode')) {
          document.body.classList.remove("dark-mode");
        }
        if (mainHeader.classList.contains('navbar-dark')) {
          mainHeader.classList.add('navbar-light');
          mainHeader.classList.remove('navbar-dark');
        }
        localStorage.setItem('theme', 'light');
      }
    }

    toggleSwitch.addEventListener('change', switchTheme, false);
  </script>
</body>

</html>
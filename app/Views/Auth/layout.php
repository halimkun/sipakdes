<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi pengajuan surat warga">
    <meta name="author" content="ikiteloo">

    <title>SIPAKDES | <?= isset($title) ? $title : '-' ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url("assets/plugins/fontawesome-free/css/all.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/dist/css/adminlte.min.css?v=3.2.0") ?>">

    <?= $this->renderSection('pageStyles') ?>
</head>

<body class="hold-transition login-page">

    <?= '' //view('App\Views\Auth\_navbar') ?>

    <main role="main" class="">
        <?= $this->renderSection('main') ?>
    </main><!-- /.container -->

    <!-- Bootstrap core JavaScript -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= base_url("assets/plugins/jquery/jquery.min.js") ?>"></script>
    <script src="<?= base_url("assets/plugins/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
    <script src="<?= base_url("assets/dist/js/adminlte.min.js?v=3.2.0") ?>"></script>

    <?= $this->renderSection('pageScripts') ?>
</body>

</html>
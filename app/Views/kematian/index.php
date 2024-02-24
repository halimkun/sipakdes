<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-md-9 order-2 order-md-1">
    <div class="card card-outline card-success">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-dizzy mr-1"></i> Surat Kematian
        </h3>
      </div>
      <div class="card-body">
        <table class="table table-striped table-hover" id="table-kematian">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>Tempat</th>
              <th>Sebab</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $i => $d) : ?>
              <tr>
                <td><?= $i + 1; ?></td>
                <td><?= $d->nama; ?></td>
                <td><?= $d->tanggal; ?></td>
                <td><?= $d->tempat; ?></td>
                <td><?= $d->sebab; ?></td>
                <td>
                  <a href="/surat/kematian/<?= $d->id; ?>" class="btn btn-sm btn-primary">Detail</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-3 order-1 order-md-2">
    <div class="card card-outline card-info">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i> Informasi Surat
        </h3>
      </div>
      <div class="card-body pt-1">
        <p class="text-muted">Surat Kematian adalah surat yang dikeluarkan oleh pemerintah desa untuk mengkonfirmasi kematian seseorang.</p>
        <a href="/surat/kematian/new" class="btn btn-primary btn-block"><b>Buat Surat Kematian</b></a>
      </div>
    </div>
    <div class="card card-outline card-warning">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1 text-warning"></i> Informasi Lain
        </h3>
      </div>
      <div class="card-body pt-1 pb-0">
        <p class="text-muted">
          Akun penduduk dengan status <b>meninggal</b> tidak akan dapat digunakan untuk mengakses layanan pada aplikasi ini. Untuk mengubah status akun penduduk, silahkan hubungi pihak desa.
        </p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<script>
  $(document).ready(function() {
    // DataTable
    $('#table-kematian').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    });
  });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="card card-outline card-success">
  <div class="card-header border-0">
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="card-title">
        <i class="fas fa-users mr-1"></i>
        Data Penduduk
      </h3>
      <a href="/penduduk/new">
        <button class="btn btn-primary btn-xs">
          <i class="fas fa-plus mr-1"></i>
          Tambah Penduduk
        </button>
      </a>
    </div>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>No.</th>
          <th>KK & NIK</th>
          <th>Nama</th>
          <th>Umur</th>
          <th>Jk</th>
          <th>Pendidikan</th>
          <th>Jenis Pekerjaan</th>
          <th>verified</th>
          <th class="text-right">#</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($penduduk as $key => $pen) : ?>
          <tr>
            <td><?= $key + 1; ?></td>
            <td>
              <div class="d-flex flex-column align-items-start" style="gap: 0.5rem;">
                <div class="badge badge-secondary" data-toggle="tooltip" data-placement="left" title="nomor KK"><?= $pen->kk; ?></div>
                <div class="badge badge-success" data-toggle="tooltip" data-placement="left" title="nomor NIK"><?= $pen->nik; ?></div>
              </div>
            </td>
            <td>
              <p class="p-0 m-0"><?= $pen->nama; ?></p>
              <?php if ($pen->berkas) : ?>
                <a href="/penduduk/berkas/kk/<?= $pen->berkas; ?>" target="_blank">
                  <div class="badge badge-light">lihat KK <i class="fas fa-external-link-alt text-xs ml-1"></i>
                </a>
              <?php endif ?>
            </td>
            <td><?= date_diff(date_create($pen->tanggal_lahir), date_create('today'))->y; ?> Th</td>
            <td><?= $pen->jenis_kelamin; ?></td>
            <td><?= $pen->pendidikan; ?></td>
            <td><?= $pen->jenis_pekerjaan; ?></td>
            </td>
            <td><?= $pen->is_verified ? '<span class="badge badge-success">Verified</span>' : '<span class="badge badge-danger">Unverified</span>'; ?></td>
            <td class="text-right">
              <a href="/penduduk/edit/<?= $pen->id; ?>">
                <button class="btn btn-warning btn-xs">
                  <i class="fas fa-edit"></i>
                </button>
              </a>
              <a href="/penduduk/delete/<?= $pen->id; ?>">
                <button class="btn btn-danger btn-xs">
                  <i class="fas fa-trash"></i>
                </button>
              </a>
            </td>
          <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= '' // base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') 
                              ?>">
<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<!-- <script src="<?= '' //base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') 
                  ?>"></script> -->
<!-- <script src="<?= '' //base_url('/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') 
                  ?>"></script> -->
<!-- <script src="<?= '' //base_url('/assets/plugins/jszip/jszip.min.js') 
                  ?>"></script> -->
<!-- <script src="<?= '' //base_url('/assets/plugins/datatables-buttons/js/buttons.html5.min.js') 
                  ?>"></script> -->

<script>
  $(document).ready(function() {
    // DataTable
    $('.table').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
<?= $this->endSection(); ?>
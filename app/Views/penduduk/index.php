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
    <table class="table table-hover table-striped" id="table-penduduk">
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
              <a href="#">
                <button class="btn btn-danger btn-xs btn-delete" data-id="<?= $pen->id; ?>" data-nik="<?= $pen->nik; ?>" data-nama="<?= $pen->nama; ?>">
                  <i class="fas fa-trash"></i>
                </button>
              </a>
            </td>
          <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade " tabindex="-1" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
          <p>Are you sure you want to delete this data?</p>
          <!-- nik dan nama -->
          <table class="table table-sm table-borderless table-hover">
            <tr>
              <td>NIK</td>
              <td>:</td>
              <td id="delete-nik"></td>
            </tr>
            <tr>
              <td>Nama</td>
              <td>:</td>
              <td id="delete-nama"></td>
            </tr>
          </table>

          <div class="d-flex justify-content-end" style="gap: 0.5rem;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= '' // base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<!-- <script src="<?= '' //base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')  ?>"></script> -->
<!-- <script src="<?= '' //base_url('/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')  ?>"></script> -->
<!-- <script src="<?= '' //base_url('/assets/plugins/jszip/jszip.min.js')  ?>"></script> -->
<!-- <script src="<?= '' //base_url('/assets/plugins/datatables-buttons/js/buttons.html5.min.js')  ?>"></script> -->

<script>
  $(document).ready(function() {
    // DataTable
    $('#table-penduduk').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    });

    // delete modal
    $('.btn-delete').click(function() {
      $('#delete-nik').text($(this).data('nik'));
      $('#delete-nama').text($(this).data('nama'));

      // action form
      $('form').attr('action', '/penduduk/' + $(this).data('id') + '/delete');

      $('.modal').modal('show');
    });
  });
</script>
<?= $this->endSection(); ?>
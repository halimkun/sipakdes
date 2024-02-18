<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-lg-8 order-2 order-lg-1">
    <div class="card card-outline card-info">
      <div class="card-header border-0">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title">
            <i class="fas fa-users mr-1"></i> Data Keluarga Anda
          </h3>
        </div>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover table-striped" id="table-penduduk">
          <thead>
            <tr>
              <th>No.</th>
              <th>KK & NIK</th>
              <th>Nama</th>
              <th>Tgl Lahir</th>
              <th>J. Kelamin</th>
              <th>Pendidikan</th>
              <th>J. Pekerjaan</th>
              <th>verified</th>
              <th class="text-right">#</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($keluarga as $key => $kel) : ?>
              <tr>
                <td><?= $key + 1; ?></td>
                <td>
                  <div class="d-flex flex-column align-items-start" style="gap: 0.5rem;">
                    <div class="badge badge-secondary" data-toggle="tooltip" data-placement="left" title="nomor KK"><?= $kel->kk; ?></div>
                    <div class="badge badge-success" data-toggle="tooltip" data-placement="left" title="nomor NIK"><?= $kel->nik; ?></div>
                  </div>
                </td>
                <td>
                  <p class="p-0 m-0"><?= $kel->nama; ?></p>
                  <div class="badge <?= 'badge-' . getColorHubungan($kel->hubungan) ?>"><?= $kel->hubungan == 'Ayah' ? 'Kepala Keluarga' : $kel->hubungan; ?></div>
                </td>
                <td>
                  <p class="p-0 m-0"><?= \Carbon\Carbon::parse($kel->tanggal_lahir)->isoFormat('dddd, D MMMM Y'); ?></p>
                  <small class="text-muted">
                    <?= \Carbon\Carbon::parse($kel->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan %d hari'); ?>
                  </small>
                </td>
                <td><?= $kel->jenis_kelamin; ?></td>
                <td><?= $kel->pendidikan; ?></td>
                <td><?= $kel->jenis_pekerjaan; ?></td>
                <td><?= $kel->is_verified ? '<span class="badge badge-success">Verified</span>' : '<span class="badge badge-warning">Unverified</span>'; ?></td>
                <td class="text-right">
                  <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                    <a class="btn btn-info btn-edit" href="/keluarga/<?= $kel->id ?>/edit"><i class="fa fa-pen"></i></a>
                    <a class="btn btn-danger btn-delete" href="#" data-id="<?= $kel->id; ?>" data-nik="<?= $kel->nik; ?>" data-nama="<?= $kel->nama; ?>">
                      <i class="fa fa-trash"></i>
                    </a>
                  </div>
                </td>
              <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4 order-1 order-lg-2">
    <div class="card card-outline card-success position-sticky" style="top: 1rem;">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-check-circle mr-1"></i> Informasi Keluarga
        </h3>
      </div>
      <div class="card-body p-0">
        <div class="row">
          <div class="col-md-12 table-hover">
            <table class="table table-borderless">
              <tr>
                <th>Kepala Keluarga</th>
                <td>:</td>
                <td><?= $kepalakeluarga->nama ?? "-" ?></td>
              </tr>
              <tr>
                <th>Kartu Keluarga</th>
                <td>:</td>
                <td><?= $user->pendudukData()->kk ?></td>
              </tr>
            </table>
          </div>
          <div class="col-md-12">
            <div class="px-3 pb-3 h-100">
              <div class="d-flex justify-content-center align-items-center flex-column h-100">
                <a href="#" class="btn btn-primary btn-block btn-upload-kk">
                  <i class="fas fa-upload mr-1"></i> Upload KK
                </a>
                <a href="/keluarga/new" class="btn btn-success btn-block">
                  <i class="fas fa-plus mr-1"></i> Tambah Anggota Keluarga
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal delete confirmation -->
<div class="modal fade " id="mode-delete-confirmation" tabindex="-1" role="dialog" data-backdrop="static">
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

          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle mr-1"></i> Warning!
            <p class="p-0 m-0">This action will delete the user data, as a result the user cannot access the system anymore.</p>
          </div>

          <div class="d-flex justify-content-end" style="gap: 0.5rem;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- modal upload KK -->
<div class="modal fade" id="modal-upload-kk" tabindex="-1" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload KK</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Anda akan mengupload berkas KK untuk penduduk dengan detail sebagai berikut:
        <table class="table table-sm table-borderless table-hover">
          <tr>
            <td>NIK</td>
            <td>:</td>
            <td id="upload-nik"></td>
          </tr>
          <tr>
            <td>Nama</td>
            <td>:</td>
            <td id="upload-nama"></td>
          </tr>
        </table>

        <form action="/keluarga/upload-kk" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" id="upload-id">
          <input type="hidden" name="kk" id="upload-kk">
          <div class="form-group mb-3">
            <label for="berkas-kk">Berkas KK</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="berkas" name="berkas" aria-describedby="berkas kk">
              <label class="custom-file-label" for="berkas">Choose file</label>
            </div>
          </div>

          <div class="d-flex justify-content-end" style="gap: 0.5rem;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Upload</button>
          </div>
      </div>
    </div>
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
    $('#table-penduduk').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    });

    // delete modal
    $('.btn-delete').click(function() {
      $('#delete-nik').text($(this).data('nik'));
      $('#delete-nama').text($(this).data('nama'));

      // action form
      $('form').attr('action', '/keluarga/' + $(this).data('id') + '/delete');

      // show modal
      $('#mode-delete-confirmation').modal('show');
    });
  });
</script>
<?= $this->endSection(); ?>
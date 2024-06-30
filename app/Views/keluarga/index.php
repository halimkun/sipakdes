<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-lg-9 order-2 order-lg-1">
    <div class="card card-outline card-info">
      <div class="card-header border-0">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title">
            <i class="fas fa-users mr-1"></i> Data Keluarga Anda
          </h3>
        </div>
      </div>
      <div class="card-body table-responsive">
        <!-- TODO : Tambahkan acation buat request user untuk penduduk yang belum memiliki user dan usia > 5 Tahub -->
        <table class="table table-hover table-striped" id="table-penduduk">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama</th>
              <th>Tgl Lahir</th>
              <th>JK</th>
              <th>Pendidikan</th>
              <th>Hubungan</th>
              <th class="text-right">
                <?php if (!in_groups('warga')) : ?>
                  #
                <?php endif ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($keluarga as $key => $kel) : ?>
              <tr>
                <td><?= $key + 1; ?></td>
                <td>
                  <p class="p-0 m-0">
                    <?= $kel->is_verified ? '<i class="fa fa-check-circle text-success mr-1" data-toggle="tooltip" title="data diverifikasi"></i>' : '<i class="fa fa-times-circle text-danger mr-1" data-toggle="tooltip" title="data belum diverifikasi"></i>'; ?>
                    <?= $kel->nama; ?>
                  </p>
                </td>
                <td>
                  <p class="p-0 m-0"><?= \Carbon\Carbon::parse($kel->tanggal_lahir)->isoFormat('dddd, D MMMM Y'); ?></p>
                  <small class="text-muted">
                    <?= \Carbon\Carbon::parse($kel->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan %d hari'); ?>
                  </small>
                </td>
                <td><?= $kel->jenis_kelamin == "Perempuan" ? "P" : "L"; ?></td>
                <td><?= $kel->pendidikan; ?></td>
                <td>
                  <div class="badge <?= 'badge-' . getColorHubungan($kel->hubungan) ?>"><?= $kel->hubungan; ?></div>
                </td>
                <td class="text-right">
                  <div class="btn-group btn-group-sm" role="group" aria-label="Action">
                    <a class="btn btn-info btn-edit" href="/keluarga/<?= $kel->id ?>/edit"><i class="fa fa-pen"></i></a>
                  </div>
                </td>
              <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-3 order-1 order-lg-2">
    <div class="card card-outline card-success position-sticky" style="top: 1rem;">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-check-circle mr-1"></i> Informasi Keluarga
        </h3>
      </div>
      <div class="card-body p-0">
        <div class="row">
          <div class="col-md-12 table-hover">
            <table class="table table-sm table-borderless">
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
              <div class="d-flex justify-content-center align-items-center flex-column h-100" style="gap: 0.5rem;">
                <div class="d-flex justify-content-center align-items-center w-100" style="gap: 0.5rem;">
                  <?php if ($berkasKk) : ?>
                    <a href="/uploads/bkk/<?= $berkasKk->berkas ?>" class="btn btn-info w-100" target="_blank">
                      <i class="fas fa-search mr-1"></i> Lihat KK
                    </a>
                  <?php endif ?>
                  <button class="btn btn-primary btn-upload-kk w-100">
                    <i class="fas fa-upload mr-1"></i> <?= $berkasKk ? 'Ganti Kk' : 'Upload KK' ?>
                  </button>
                </div>
                <a href="/keluarga/new" class="btn btn-success btn-block">
                  <i class="fas fa-plus mr-1"></i> Tambah Anggota Keluarga
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-outline card-warning">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i> Informasi Lain
        </h3>
      </div>
      <div class="card-body pb-0 pt-0">
        <p>Jika terdapat kesalahan anggota keluarga, silahkan hubungi admin atau operator desa untuk melakukan perubahan.</p>
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
        Upload Kartu Keluarga untuk keluarga anda, pastikan file yang diupload adalah file yang valid.

        <form action="/keluarga/upload-kk" method="post" enctype="multipart/form-data">
          <input type="hidden" name="kk" id="upload-kk" class="form-control" value="<?= $user->pendudukData()->kk ?>" />

          <div class="mb-3 mt-3">
            <div class="form-group mb-0">
              <label for="berkas-kk">Berkas KK</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="berkas" name="berkas" aria-describedby="berkas kk">
                <label class="custom-file-label" for="berkas">Choose file</label>
              </div>
            </div>
            <small>Berkas ini akan digunakan untuk keperluan administrasi dan verifikasi data keluarga anda.</small>
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

    // upload kk
    $('.btn-upload-kk').click(function() {
      $('#modal-upload-kk').modal('show');
    });
  });
</script>
<?= $this->endSection(); ?>
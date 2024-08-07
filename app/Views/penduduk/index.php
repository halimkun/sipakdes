<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="card card-outline card-success">
  <div class="card-header border-0">
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="card-title">
        <i class="fas fa-users mr-1"></i> Data Penduduk
      </h3>
      <div class="d-flex" style="gap: 10px">
        <a href="#importModal" data-toggle="modal" data-backdrop="static">
          <button class="btn btn-warning btn-xs">
            <i class="fas fa-file-import mr-1"></i> Import
          </button>
        </a>
        <a href="/penduduk/template">
          <button class="btn btn-info btn-xs">
            <i class="fas fa-download mr-1"></i> Template
          </button>
        </a>
        <a href="/penduduk/export">
          <button class="btn btn-success btn-xs">
            <i class="fas fa-file-excel mr-1"></i> Export
          </button>
        </a>
      </div>
      <div class="d-flex" style="gap: 10px">
        <a href="/penduduk/new">
          <button class="btn btn-primary btn-xs">
            <i class="fas fa-plus mr-1"></i> Tambah Penduduk
          </button>
        </a>
      </div>
    </div>
  </div>
  <div class="card-body table-responsive">
    <!-- TODO : Tambahkan acation buat user untuk penduduk yang belum memiliki user dan usia > 5 Tahub -->
    <table class="table table-hover table-striped" id="table-penduduk">
      <thead>
        <tr>
          <th>No.</th>
          <th>KK & NIK</th>
          <th>Nama</th>
          <th>Umur</th>
          <th>J. Kelamin</th>
          <th>Pendidikan</th>
          <th>J. Pekerjaan</th>
          <th>Berkas KK</th>
          <th>verified</th>
          <th class="text-right">
            <?php if (!in_groups('warga')) : ?>
              #
            <?php endif ?>
          </th>
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
            </td>
            <td><?= date_diff(date_create($pen->tanggal_lahir), date_create('today'))->y; ?> Th</td>
            <td><?= $pen->jenis_kelamin; ?></td>
            <td><?= $pen->pendidikan; ?></td>
            <td><?= $pen->jenis_pekerjaan; ?></td>
            <td>
              <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <button type="button" class="btn <?= !$pen->berkas ? 'btn-warning' : 'btn-success' ?> btn-preview" <?= !$pen->berkas ? 'disabled' : '' ?> title="<?= !$pen->berkas ? 'kk belum diupload' : 'preview berkas' ?>" data-href="/uploads/bkk/<?= $pen->berkas ?>">
                  <i class="fa <?= !$pen->berkas ? 'fa-times-circle' : 'fa-search' ?>"></i>
                </button>
                <button type="button" class="btn btn-info btn-keterangan" <?= !$pen->keterangan_berkas ? 'disabled' : '' ?> title="<?= !$pen->keterangan_berkas ? 'tidak ada keterangan tambahan' : 'keterangan berkas' ?>" data-info="<?= $pen->keterangan_berkas ?>">
                  <i class="fa fa-info-circle"></i>
                </button>
              </div>
            </td>
            <td><?= $pen->is_verified ? '<span class="badge badge-success">Verified</span>' : '<span class="badge badge-danger">Unverified</span>'; ?></td>
            <td class="text-right">
              <div class="d-flex justify-content-end">
                <div class="btn-group dropleft">
                  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="/penduduk/<?= $pen->id ?>/edit"><i class="fa fa-pen mr-1"></i> Edit Data</a>
                    <a class="dropdown-item btn-upload-kk" href="#" data-id="<?= $pen->id; ?>" data-kk="<?= $pen->kk; ?>" data-nik="<?= $pen->nik; ?>" data-nama="<?= $pen->nama; ?>"><i class="fa fa-file-upload mr-1"></i> Upload KK</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item <?= $pen->is_verified ? 'text-danger' : 'text-success' ?>" href="/penduduk/<?= $pen->id ?>/verify" onclick="return confirm('Apakah anda yakin data ini sudah benar?')">
                      <?php if ($pen->is_verified) : ?>
                        <i class="fa fa-times mr-1"></i> Batalkan Verifikasi
                      <?php else : ?>
                        <i class="fa fa-check mr-1"></i> Verifikasi Data
                      <?php endif ?>
                    </a>
                    <div class="dropdown-divider"></div>
                    <!-- <a class="dropdown-item text-danger btn-delete" href="#" data-id="<?= $pen->id; ?>" data-nik="<?= $pen->nik; ?>" data-nama="<?= $pen->nama; ?>">
                      <i class="fa fa-trash mr-1"></i> Hapus Data
                    </a> -->
                  </div>
                </div>
              </div>
            </td>
          <?php endforeach ?>
      </tbody>
    </table>
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

<!-- Modal informati tambahan berkas kk -->
<div class="modal fade" id="modal-keterangan" tabindex="-1" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Keterangan Berkas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="tip">Informasi Tambahan</h5>
        <p id="keterangan-berkas"></p>
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

        <form action="/penduduk/upload-kk" method="post" enctype="multipart/form-data">
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
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Data Penduduk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Anda dapat mengimport data penduduk dengan mengupload file excel yang berisi data penduduk. Silahkan download template excel <a href="/penduduk/template">disini</a>.
        <form action="/penduduk/import" method="post" enctype="multipart/form-data">
          <div class="form-group mb-3">
            <label for="berkas-kk">Berkas Excel</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="berkas" name="berkas" aria-describedby="berkas excel" accept=".xls,.xlsx">
              <label class="custom-file-label" for="berkas">Choose file</label>
            </div>
          </div>
          <div class="d-flex justify-content-end" style="gap: 0.5rem;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Upload</button>
          </div>
        </form>
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
    // $('.btn-delete').click(function() {
    //   $('#delete-nik').text($(this).data('nik'));
    //   $('#delete-nama').text($(this).data('nama'));

    //   // action form
    //   $('form').attr('action', '/penduduk/' + $(this).data('id') + '/delete');

    //   // show modal
    //   $('#mode-delete-confirmation').modal('show');
    // });

    // keterangan modal
    $('.btn-keterangan').click(function() {
      $('#keterangan-berkas').text($(this).data('info') ? $(this).data('info') : 'Tidak ada keterangan');
      $('#modal-keterangan').modal('show');
    });

    // btn preview
    $('.btn-preview').click(function() {
      if ($(this).attr('disabled')) {
        return;
      }
      window.open($(this).data('href'), '_blank');
    });

    // upload kk
    $('.btn-upload-kk').click(function() {
      $('#upload-nik').text($(this).data('nik'));
      $('#upload-nama').text($(this).data('nama'));
      $('#upload-id').val($(this).data('id'));
      $('#upload-kk').val($(this).data('kk'));

      $('#modal-upload-kk').modal('show');
    });
  });
</script>
<?= $this->endSection(); ?>
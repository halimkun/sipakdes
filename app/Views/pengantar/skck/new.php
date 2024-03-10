<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-md-9">
    <div class="card card-outline card-success">
      <div class="card-header">
        <div class="card-title">
          <div class="fa fa-fingerprint mr-2"></div>
          Entry Data Pengajuan
        </div>
      </div>
      <div class="card-body">
        <?php if (isset($fields) && $fields) : ?>
          <form action="/surat/pengantar/skck/store" method="post">
            <div class="row">
              <?php foreach ($fields as $key => $field) : ?>
                <div class="col-12">
                  <?= getFields($field) ?>
                </div>
              <?php endforeach ?>
            </div>

            <div class="mt-4">
              <div class="table-responsive">
                <table class="table table-hover table-striped" id="table-penduduk">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>NIK</th>
                      <th>Tgl Lahir</th>
                      <th>JK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($penduduk as $key => $kel) : ?>
                      <tr>
                        <td>
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="<?= $kel->id ?>" name="id_penduduk" value="<?= $kel->id ?>" <?= old('id_penduduk') == $kel->id ? 'checked' : '' ?>>
                            <label for="<?= $kel->id ?>" class="custom-control-label"><?= $kel->nama ?></label>
                          </div>
                        </td>
                        <td>
                          <div class="badge badge-secondary">
                            <?= $kel->nik ?>
                          </div>
                        </td>
                        <td>
                          <p class="p-0 m-0"><?= \Carbon\Carbon::parse($kel->tanggal_lahir)->isoFormat('dddd, D MMMM Y'); ?></p>
                          <small class="text-muted">
                            <?= \Carbon\Carbon::parse($kel->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan %d hari'); ?>
                          </small>
                        </td>
                        <td><?= $kel->jenis_kelamin ?></td>
                      <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group row mt-4">
              <div class="col-md-6">
                <a href="/surat/pengantar/skck" class="btn btn-secondary btn-block">Batal</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-block">Simpan</button>
              </div>
            </div>
          </form>
        <?php else : ?>
          <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            Terjadi kesalahan saat memuat form, atau form belum diinisialisasi.
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-3">
    <div class="card card-outline card-info">
      <div class="card-header border-0">
        <h4 class="card-title">
          <i class="fa fa-info-circle mr-1"></i> Informasi
        </h4>
      </div>
      <div class="card-body">
        <p class="text-muted">
          Surat keterangan berkelakuan baik atau adat istiadat baik dari kepala desa. Surat pengantar ini diperlukan untuk mengurus atau membuat SKCK.
        </p>

        <!-- button block -->
        <a href="/surat/pengantar/skck" class="btn btn-block btn-secondary">
          <i class="fa fa-arrow-left mr-1"></i> back
        </a>
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
    $('#table-penduduk').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    });

    // if tr click then radio checked
    $('#table-penduduk tbody tr').click(function() {
      $(this).find('input[type="radio"]').prop('checked', true);
    });
  });
</script>
<?= $this->endSection(); ?>
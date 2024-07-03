<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-md-9 order-2 order-md-1">
    <div class="card card-outline card-success">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-heart-broken mr-1"></i> Surat Kematian
        </h3>
      </div>
      <div class="card-body pb-1">
        <?php if (isset($kematianFields) && $kematianFields) : ?>
          <form action="/surat/kematian/store" method="post">
            <div class="row">
              <?php foreach ($kematianFields as $key => $field) : ?>
                <!-- make 2 columns from total fields -->
                <div class="col-md-6 <?= $field['type'] == 'hidden' ? 'd-none' : '' ?>">
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
                      <th>Tgl Lahir</th>
                      <th>JK</th>
                      <!-- <th>Hubungan</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($keluarga as $key => $kel) : ?>
                      <tr>
                        <td>
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="<?= $kel->id ?>" name="id_penduduk" value="<?= $kel->id ?>" <?= old('id_penduduk') == $kel->id ? 'checked' : '' ?>>
                            <label for="<?= $kel->id ?>" class="custom-control-label"><?= $kel->nama ?></label><br>
                            <!-- <div class="badge badge-secondary"><?= '' // $kel->nik ?></div> -->
                          </div>
                        </td>
                        <td>
                          <p class="p-0 m-0"><?= \Carbon\Carbon::parse($kel->tanggal_lahir)->isoFormat('dddd, D MMMM Y'); ?></p>
                          <small class="text-muted">
                            <?= \Carbon\Carbon::parse($kel->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan %d hari'); ?>
                          </small>
                        </td>
                        <td><?= $kel->jenis_kelamin ?></td>
                        <!-- <td>
                          <div class="badge <?= '' //'badge-' . getColorHubungan($kel->hubungan) ?>"><?= ''// $kel->hubungan; ?></div>
                        </td> -->
                      <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group row mt-1">
              <div class="col-md-6">
                <a href="/surat/kematian" class="btn btn-secondary btn-block">Batal</a>
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
  <div class="col-md-3 order-1 order-md-2">
    <div class="card card-outline card-info">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i> Informasi Surat
        </h3>
      </div>
      <div class="card-body pt-1">
        <p class="text-muted">Surat Kematian adalah surat yang dikeluarkan oleh pemerintah desa untuk mengkonfirmasi kematian seseorang.</p>
        <a href="/surat/kematian" class="btn btn-secondary btn-block"><b>Batal</b></a>
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


<?= $this->section('styles') ;?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/daterangepicker/daterangepicker.css') ?>">
<style>
  .daterangepicker .drp-calendar.left .calendar-table{
    margin-right: 8px;
    padding-right: 0px;
  }
</style>
<?= $this->endSection() ;?>


<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<script src="<?= base_url('/assets/plugins/moment/moment-with-locales.min.js') ?>"></script>
<script src="<?= base_url('/assets/plugins/moment/locale/id.js') ?>"></script>
<script src="<?= base_url('/assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<script>
  $(document).ready(function() {
    $('#table-penduduk').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    });
    
    // datepicker for tanggal
    $('input[name="tanggal"]').daterangepicker({
      singleDatePicker: true,
      timePicker24Hour: true,
      showDropdowns: true,
      timePicker: true,
      autoApply: true,
      locale: {
        format: 'YYYY-MM-DD HH:mm',
        daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
      }
    });

    // input[name="tanggal"] set value now using moment.js
    $('input[name="tanggal"]').val(moment().format('YYYY-MM-DD HH:mm'));

    // if tr click then radio checked
    $('#table-penduduk tbody tr').click(function() {
      $(this).find('input[type="radio"]').prop('checked', true);
    });

    $('input[name="nik_pelapor"]').attr('pattern', '[0-9]{16}');
    $('input[name="nik_pelapor"]').on('invalid', function(e) {
      e.target.setCustomValidity('');
      if (!e.target.validity.valid) {
        e.target.setCustomValidity('NIK harus terdiri dari 16 digit angka.');
      }
    });
  });
</script>
<?= $this->endSection(); ?>
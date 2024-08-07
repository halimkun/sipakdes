<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-md-9 order-2 order-md-1">
    <div class="card card-outline card-success">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-map-marked-alt mr-1"></i> Surat Domisili
        </h3>
      </div>
      <div class="card-body">
        <table class="table table-striped table-hover" id="table-domisili">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>
                <?php if (!in_groups('warga')) : ?>
                  Aksi
                <?php endif ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $i => $d) : ?>
              <tr>
                <td><?= $i + 1; ?></td>
                <td>
                  <?= $d->nama; ?><br>
                  <small class="text-muted">
                    <?= \Carbon\Carbon::parse($d->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y Tahun %m Bulan %d Hari'); ?>
                  </small>
                </td>
                <td>
                  <?= \Carbon\Carbon::parse($d->tanggal)->isoFormat('dddd, D MMMM Y'); ?> <br>
                  <small><?= \Carbon\Carbon::parse($d->tanggal)->isoFormat('HH:mm'); ?> WIB</small>
                </td>
                <td>
                  <div class="text-muted">
                    <?= $d->keterangan ?>
                  </div>
                </td>
                <td>
                  <?php if ($d->status == 'pending') : ?>
                    <span class="badge badge-info">Pending</span>
                  <?php elseif ($d->status == 'ditolak') : ?>
                    <span class="badge badge-danger">Ditolak</span>
                  <?php elseif ($d->status == 'selesai') : ?>
                    <span class="badge badge-success">Selesai</span>
                  <?php elseif ($d->status == 'batal') : ?>
                    <span class="badge badge-warning">Batal</span>
                  <?php else : ?>
                    <span class="badge badge-secondary">Invalid</span>
                  <?php endif ?>
                </td>
                <td>
                  <?php if (!in_groups('warga')) : ?>
                    <!-- dropdown -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="/surat/domisili/<?= $d->id_domisili ?>/print"><i class="fa fa-print mr-1"></i> Cetak</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item btn-update-status" href="#!" data-id="<?= $d->id_domisili ?>" data-nama="<?= $d->nama ?>" data-tempat="<?= $d->tempat ?>" data-tanggal="<?= $d->tanggal ?>"><i class="fa fa-pen mr-1"></i> Update Status</a>
                      </div>
                    </div>
                  <?php else : ?>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Penduduk">
                      <?php if (!in_array($d->status, ['pending'])) : ?>
                        <?php if ($d->status == 'approved') : ?>
                          <!-- <a href="/surat/domisili/<?= $d->id_domisili ?>/print" class="btn btn-sm btn-primary" data-toggle="tooltip" title="cetak"><i class="fa fa-print"></i></a> -->
                        <?php endif ?>
                        <!-- <button data-toggle="tooltip" title="batal" class="btn btn-sm btn-secondary btn-batal" disabled data-id="<?= $d->id_domisili ?>"><i class="fa fa-times-circle"></i></button> -->
                      <?php else : ?>
                        <!-- <a href="/surat/domisili/<?= $d->id_domisili ?>/batal" data-toggle="tooltip" title="batal" class="btn btn-sm btn-secondary" onclick="return confirm('Apakah anda yakin ingin membatalkan surat ini?')"><i class="fa fa-times-circle"></i></a> -->
                      <?php endif ?>
                    </div>
                  <?php endif ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-3 order-1 order-md-2">
    <div class="card card-warning">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i> Informasi Persyaratan
        </h3>
      </div>
      <div class="card-body">
        <ol>
          <li>Fotocopy KK & KTP</li>
          <li>Surat pindah dari daerah asal (optional)</li>
        </ol>
      </div>
    </div>

    <div class="card card-outline card-info">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i> Informasi Surat
        </h3>
      </div>
      <div class="card-body pt-1">
        <p class="text-muted">Surat Keterangan Domisili adalah sebuah surat keterangan resmi berupa dokumen yang memuat data kependudukan seperti yang terdapat pada KTP</p>
        <a href="/surat/domisili/new" class="btn btn-primary btn-block"><b>Buat Surat Domisili</b></a>
      </div>
    </div>
  </div>
</div>

<?php if (!in_groups('warga')) : ?>
  <!-- modal update status -->
  <div class="modal fade" id="modal-update-status" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Status Pengajuan Surat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="post">
            <!-- custom select bootstrao -->
            <div class="form-group">
              <label for="status">Status</label>
              <select class="custom-select" name="status" id="status" required>
                <option value="">Pilih status</option>
                <option value="pending">Pending</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
              </select>
            </div>

            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Keterangan tambahan"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif ?>
<?= $this->endSection(); ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<script>
  $(document).ready(function() {
    $('#table-domisili').DataTable({
      dom: '<"row"<"col-md-6"l><"col-md-6 text-right"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
      // buttons: ["csv", "excel"],
    });
  });
</script>

<?php if (!in_groups(['warga'])) : ?>
  <script>
    // btn-update-status click to open modal
    $('.btn-update-status').on('click', function(e) {
      let id = $(this).data('id');
      let nama = $(this).data('nama');
      let tempat = $(this).data('tempat');
      let tanggal = $(this).data('tanggal');

      $('#modal-update-status').modal('show');
      $('#modal-update-status form').attr('action', `/surat/domisili/${id}/update-status`);
    });
  </script>
<?php endif ?>
<?= $this->endSection(); ?>
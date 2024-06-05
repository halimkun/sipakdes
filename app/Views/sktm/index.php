<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-12 col-md-9">
    <div class="card card-outline card-success">
      <div class="card-header">
        <h4 class="card-title">
          <i class="fa fa-donate mr-1"></i> Pengajuan SKTM
        </h4>
      </div>
      <div class="card-body p-3">
        <div class="table-responsive mb-0">
          <table class="table table-striped" id="table-pengantar">
            <thead>
              <tr>
                <th>No</th>
                <!-- <th>No. Pengajuan</th> -->
                <th>Nama</th>
                <th>Keperluan</th>
                <th>Tgl Pengajuan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $key => $kia) : ?>
                <tr>
                  <td><?= $key + 1 ?></td>
                  <!-- <td></td> -->
                  <td>
                    <p class="p-0 m-0"><?= $kia->nama ?></p>
                    <div class="badge bdage-secondary"><?= $kia->nik ?></dc>
                  </td>
                  <td>
                    <div class="text-muted">
                      <?= $kia->keperluan ?>
                    </div>
                    <div class="text-warning"><small><?= $kia->keterangan ?></small></div>
                  </td>
                  <td><?= \Carbon\Carbon::parse($kia->created_at)->isoFormat('D MMMM Y'); ?></td>
                  <td>
                    <?php if ($kia->status == 'pending') : ?>
                      <span class="badge badge-info">Pending</span>
                    <?php elseif ($kia->status == 'ditolak') : ?>
                      <span class="badge badge-danger">Ditolak</span>
                    <?php elseif ($kia->status == 'selesai') : ?>
                      <span class="badge badge-success">Selesai</span>
                    <?php elseif ($kia->status == 'batal') : ?>
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
                          <a class="dropdown-item" href="/surat/sktm/<?= $kia->pengantar_id ?>/print"><i class="fa fa-print mr-1"></i> Cetak</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item btn-update-status" href="#!" data-id="<?= $kia->pengantar_id ?>" data-nama="<?= $kia->nama ?>" data-tempat-lahir="<?= $kia->tempat_lahir ?>"><i class="fa fa-pen mr-1"></i> Update Status</a>
                        </div>
                      </div>
                    <?php else : ?>
                      <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Penduduk">
                        <?php if (!in_array($kia->status, ['pending'])) : ?>
                          <?php if ($kia->status == 'selesai') : ?>
                            <a href="/surat/sktm/<?= $kia->pengantar_id ?>/print" class="btn btn-sm btn-primary" data-toggle="tooltip" title="cetak"><i class="fa fa-print"></i></a>
                          <?php endif ?>
                          <!-- <button data-toggle="tooltip" title="batal" class="btn btn-sm btn-secondary btn-batal" disabled data-id="<?= $kia->pengantar_id ?>"><i class="fa fa-times-circle"></i></button> -->
                        <?php else : ?>
                          <!-- <a href="/surat/sktm/<?= $kia->pengantar_id ?>/batal" data-toggle="tooltip" title="batal" class="btn btn-sm btn-secondary" onclick="return confirm('Apakah anda yakin ingin membatalkan surat ini?')"><i class="fa fa-times-circle"></i></a> -->
                        <?php endif ?>
                      </div>
                    <?php endif ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
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
          Surat keterangan tidak mampu (SKTM) adalah surat yang diperuntukan bagi keluarga yang kurang mampu dalam masalah finansial agar mendapatkan kemudahan dalam berbagai layanan pemerintah baik di bidang sosial, kesehatan, perekonomian dan pendidikan.
        </p>

        <!-- button block -->
        <a href="/surat/sktm/new" class="btn btn-block btn-info">
          <i class="fa fa-plus mr-1"></i> Buat
        </a>
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
    $('#table-pengantar').DataTable({
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
      let tempatLahir = $(this).data('tempat-lahir');

      $('#modal-update-status').modal('show');
      $('#modal-update-status form').attr('action', `/surat/sktm/${id}/update-status`);
    });
  </script>
<?php endif ?>
<?= $this->endSection(); ?>
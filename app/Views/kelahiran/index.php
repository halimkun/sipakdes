<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card card-outline card-success">
      <div class="card-header border-0">
        <h3 class="card-title">
          <i class="fas fa-baby-carriage mr-1"></i> Surat Keterangan Lahir
        </h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-stripped" id="table-kelahiran">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Usia</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($data)) : ?>
                <tr>
                  <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
              <?php else : ?>
                <?php $no = 1;
                foreach ($data as $d) : ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $d->nama; ?></td>
                    <td><?= $d->tempat_lahir; ?></td>
                    <td><?= $d->tanggal_lahir; ?></td>
                    <td><?= \Carbon\Carbon::parse($d->tanggal_lahir)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan %d hari'); ?></td>
                    <td>
                      <a href="/surat/kelahiran/<?= $d->id; ?>/print" class="btn btn-sm btn-primary" target="_blank">
                        <i class="fas fa-print"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>
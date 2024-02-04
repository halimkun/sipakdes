<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="card">
  <div class="card-header border-0">
    <div class="d-flex justify-content-between">
      <h3 class="card-title">
        <i class="fas fa-user mr-1"></i>
        Data Pengguna
      </h3>
      <a href="/admin/pengguna/create">
        <button class="btn btn-primary btn-xs">
          <i class="fas fa-plus mr-1"></i>
          Tambah Pengguna
        </button>
      </a>
    </div>
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>No.</th>
          <th>role</th>
          <th>username</th>
          <th>email</th>
          <th>nama</th>
          <th>status</th>
          <th>data</th>
          <th class="text-right">#</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $index => $user) : ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td>
              <div class="badge badge-secondary"><?= $user->role ?></div>
            </td>
            <td><?= $user->username ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->nama ?? '-' ?></td>
            <td>
              <?php if ($user->active) : ?>
                <div class="badge badge-success">Aktif</div>
              <?php else : ?>
                <div class="badge badge-danger">Tidak Aktif</div>
              <?php endif ?>
            </td>
            <td>
              <?php if (isLengkap($user->id_penduduk)) : ?>
                <!-- lengkap : show badge -->
                <div class="badge badge-primary">lengkap</div>
              <?php else : ?>
                <!-- tidak lengkap : show badge -->
                <div class="badge badge-warning">tidak lengkap</div>
              <?php endif ?>
            </td>
            <td>
              <div class="d-flex justify-content-end">
                <div class="btn-group dropleft">
                  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-info" href="#"><i class="fa fa-pen mr-1"></i> Edit Data</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-warning" href="#"><i class="fa fa-key mr-1"></i> Ubah Hak Akses</a>
                    <a class="dropdown-item text-warning" href="#"><i class="fa fa-lock mr-1"></i> Reset Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item <?= $user->active ? 'text-danger' : 'text-success' ?>" href="/admin/pengguna/<?= $user->user_id ?>/toggle" onclick="return confirm('Apakah anda yakin akan mengubah status pengguna ini?')">
                      <?php if ($user->active) : ?>
                        <i class="fa fa-ban mr-1"></i> Nonaktifkan
                      <?php else : ?>
                        <i class="fa fa-check mr-1"></i> Aktifkan
                      <?php endif ?>
                    </a>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection(); ?>
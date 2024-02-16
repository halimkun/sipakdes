<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-md-9">
    <div class="card card-outline card-success">
      <div class="card-header">
        <div class="card-title">
          <div class="fa fa-users mr-2"></div>
          Tambah Penduduk
        </div>
      </div>
      <div class="card-body">
        <?php if (isset($fields) && $fields) : ?>
          <form action="/penduduk/store" method="post">
            <?php foreach ($fields as $field) : ?>
              <?= getFields($field) ?>
            <?php endforeach ?>

            <div class="form-group row mt-4">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="/pengguna" class="btn btn-light">Batal</a>
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
  <div class="col-md-3">
    <div class="card card-outline card-info">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i>
          Informasi
        </h3>
      </div>
      <div class="card-body">
        <p class="text-justify">Satu <code class="text-success">data penduduk</code> mewakili <code>data user</code>, dimana dari data ini akan digunakan untuk menggunakan <strong>berbagai layanan</strong> yang ada di aplikasi ini.</p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>
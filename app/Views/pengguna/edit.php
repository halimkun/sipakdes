<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-md-9">
    <div class="card card-outline card-success">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-user mr-1"></i>
          Edit Pengguna
        </h3>
      </div>
      <div class="card-body pb-1">
        <form method="post" action="/pengguna/<?= $id ?>/update">
          <?php foreach ($fields as $field) : ?>
            <?= getFields($field, $user) ?>
          <?php endforeach ?>

          <div class="form-group row mt-4">
            <div class="col-sm-10 offset-sm-2">
              <button type="submit" class="btn btn-success">Simpan</button>
              <a href="/pengguna" class="btn btn-light">Batal</a>
            </div>
          </div>
        </form>
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
        <p><code>Data user</code> adalah data pengguna untuk bisa melakukan login ke dalam sistem.</p>
        <p>Data ini berbeda dengan <code class="text-success">data penduduk</code> yang merupakan data penduduk yang ada di kelurahan. <code class="text-success">Data penduduk</code> dan <code>data user</code> akan saling terkait satu sama lain.</p>
        <p>
          Setiap <code>data user</code> akan memiliki 1 <code class="text-success">data penduduk</code> yang terkait. dan masing-masing <code class="text-success">data penduduk</code> akan memiliki 1 <code>data user</code> yang terkait yang bisa digunakan untuk login dan mengakses sistem.
        </p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>
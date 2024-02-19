<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-outline card-success">
      <div class="card-header">
        <div class="card-title">
          <div class="fa fa-users mr-2"></div>
          Tambah Penduduk
        </div>
      </div>
      <div class="card-body">
        <?php if (isset($fields) && $fields) : ?>
          <form action="/keluarga/<?= $id ?>/update" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="row">
              <?php foreach ($fields as $key => $field) : ?>
                <!-- make 2 columns from total fields -->
                <div class="col-md-6">
                  <?= getFields($field, $keluarga) ?>
                </div>
              <?php endforeach ?>
            </div>

            <div class="form-group row mt-4">
              <div class="col-md-6">
                <a href="/keluarga" class="btn btn-secondary btn-block">Batal</a>
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
</div>
<?= $this->endSection(); ?>
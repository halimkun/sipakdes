<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-12 col-md-6">
    <div class="card card-outline card-warning">
      <div class="card-header">
        <h4 class="card-title">Settings</h4>
      </div>
      <div class="card-body">
        <?php if (isset($settingsFields) && $settingsFields) : ?>
          <form action="/settings/update" method="post">
            <?php foreach ($settingsFields as $field) : ?>
              <?= getFields($field, $settings) ?>
            <?php endforeach ?>

            <div class="form-group mt-4">
              <button type="submit" class="btn btn-warning">Simpan</button>
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
  <div class="col-12 col-md-6">
    <div class="card card-outline card-success">
      <div class="card-header">
        <h4 class="card-title">Settings</h4>
      </div>
      <div class="card-body">
        <?php if (isset($settingDesaFields) && $settingDesaFields) : ?>
          <form action="/settings/update-desa" method="post">
            <?php foreach ($settingDesaFields as $field) : ?>
              <?= getFields($field, $settingDesa) ?>
            <?php endforeach ?>

            <div class="form-group mt-4">
              <button type="submit" class="btn btn-success">Simpan</button>
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
<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<style>
  .thin-scroll {
    scrollbar-color: #f1f1f1 transparent;
    scrollbar-width: thin;
  }

  /* rounded thumb */
  .thin-scroll::-webkit-scrollbar-thumb {
    background-color: #f1f1f1;
    border-radius: 20px;
  }
</style>
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
    <div class="card card-outline card-info">
      <div class="card-header">
        <h4 class="card-title">App Updater</h4>
      </div>
      <div class="card-body">
        <a href="/update" class="btn btn-info mb-2">Update App</a>
        <div class="mt-3">
          <div class="p-2 px-3 rounded mb-3 thin-scroll" style="background-color: rgba(0, 0, 0, 0.1);">
            <code class="text-white p-0 m-0">
              <pre class="text-white p-0 m-0">
App Name          : <?= $info['name'] ?>

App Description   : <?= $info['name'] ?>

App Version       : <?= $info['version'] ?>

Last Updated      : <?= \Carbon\Carbon::parse($info['updated_at'])->translatedFormat('d F Y H:i') ?>

              </pre>
            </code>
          </div>

          <div class="p-2 px-3 rounded thin-scroll" style="background-color: rgba(0, 0, 0, 0.1); max-height: 300px; overflow-y: auto;">
            <code class="text-white p-0 m-0">
              <pre class="text-white p-0 m-0">
+======================================+
|              CHANGE LOG              | 
+======================================+ 

<?php foreach ($info['changeLog'] as $item) : ?>
Version : <?= $item['version'] ?>

Date    : <?= \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y H:i') ?>

Changes :
<?php foreach ($item['changes'] as $change) : ?>
  > <?= $change ?>

<?php endforeach ?>

<?php endforeach ?>
              </pre>
            </code>
          </div>
        </div>
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
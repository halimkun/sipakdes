<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="mb-3">
  <h5>Ringkasan Data Penduduk</h5>
  <div class="row">
    <?php foreach ($metrics['penduduk'] as $item) : ?>
      <div class="col-md-4 col-sm-6 col-12">
        <div class="info-box">
          <span class="info-box-icon <?= $item['options']['color'] ?>"><i class="far <?= $item['options']['icon'] ?>"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><?= $item['title'] ?></span>
            <span class="info-box-number"><?= $item['count'] ?></span>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>
<?= $this->endSection(); ?>
<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<div class="mb-3">
  <div class="d-flex justify-content-between align-items-center">
    <h5>Ringkasan Data Surat</h5>
    <!-- input date year -->
    <form action="<?= base_url('dashboard') ?>" method="get">
      <div class="input-group mb-3">
        <input type="month" class="form-control form-control-sm" name="monthYear" value="<?= $monthYear ?? date('Y-m') ?>" onchange="this.form.submit()">
      </div>
    </form>
  </div>
  <div class="row">
    <?php foreach ($metricsSurat as $key => $item) : ?>
      <div class="col-md-4 col-sm-6 col-12">
        <div class="info-box">
          <span class="d-none d-lg-flex info-box-icon bg-light"><i class="<?= getIconText($key) ?>"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-lg"><?= getTitleText($key) ?></span>
            <div class="row">
              <?php foreach ($item as $key => $value) : ?>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-5">
                      <span class="info-box-text text-<?= getColorStatus($key) ?>"><?= ucfirst($key) ?></span>
                    </div>
                    <div class="col-4">
                      <span class="info-box-text text-bold">: <?= $value ?></span>
                    </div>
                  </div>
                </div>
              <?php endforeach ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>

<div class="mb-3">
  <h5>Ringkasan Data Penduduk</h5>
  <div class="row">
    <?php foreach ($metrics['penduduk'] as $item) : ?>
      <div class="col-md-3 col-sm-6 col-12">
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

<div class="mb-3">
  <h5>Ringkasan Penduduk Berdasarkan Pekerjaan</h5>
  <div class="row">
    <?php foreach ($metrics['pekerjaan'] as $item) : ?>
      <div class="col-md-2 col-sm-4 col-6">
        <div class="info-box">
          <span class="d-none d-md-block info-box-icon <?= $item['options']['color'] ?>"><i class="far <?= $item['options']['icon'] ?>"></i></span>
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
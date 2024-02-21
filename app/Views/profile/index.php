<?= $this->extend('layouts/app'); ?>
<?= $this->section('content'); ?>
<style>
  .card-penduduk small {
    display: none;
    visibility: hidden;
  }

  .form-group {
    margin-bottom: 0.3rem;
  }
</style>

<div class="row">
  <div class="col-md-8 order-2 order-md-1">
    <div class="card card-outline card-success">
      <div class="card-header">
        <div class="card-title">
          <div class="fa fa-users mr-2"></div>
          Data Anda
        </div>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body card-penduduk">
        <p class="mb-3">
          Data penduduk yang tercantum di bawah ini adalah data yang terdaftar di sistem. Jika ada kesalahan atau perubahan data, anda bisa merubahnya melalui link berikut.
        </p>

        <a href="/keluarga/<?= $penduduk['id'] ?>/edit" class="btn btn-success btn-sm mb-3">
          <i class="fa fa-edit
          "></i> Ubah Data
        </a>

        <?php if (isset($pendudukFields) && $pendudukFields) : ?>
          <div class="row">
            <?php foreach ($pendudukFields as $key => $field) : ?>
              <div class="col-6">
                <?= getFields($field, $penduduk, true) ?>
              </div>
            <?php endforeach ?>
          </div>
        <?php else : ?>
          <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            Terjadi kesalahan saat memuat form, atau form belum diinisialisasi.
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>

  <div class="col-md-4 order-1 order-md-2">
    <div class="card card-outline card-warning">
      <div class="card-header">
        <div class="card-title">
          <div class="fa fa-user mr-2"></div>
          Data Akun
        </div>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body pb-1">
        <p>Anda dapat merubah data akun anda melalui form di bawah ini.</p>
        <?php if (isset($userFields) && $userFields) : ?>
          <form action="/profile/user-update" method="post" class="pb-1">
            <input type="hidden" name="id" value="<?= user()->id ?>" />
            <?php foreach ($userFields as $key => $field) : ?>
              <?= getFields($field, $user, true) ?>
            <?php endforeach ?>

            <div class="form-group row mt-5">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-warning btn-block">Simpan Perubahan</button>
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
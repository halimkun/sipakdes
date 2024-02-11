<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="login-box" style="margin-left: auto; margin-right: auto;">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="/" class="h1"><b>SIPAK</b>DES</a>
    </div>
    <div class="card-body">
      <p><?= lang('Auth.enterCodeEmailPassword') ?></p>
      <?= view('App\Views\Auth\_message_block') ?>

      <form action="<?= url_to('reset-password') ?>" method="post">
        <?= csrf_field() ?>

        <div class="input-group mb-3">
          <input type="text" class="form-control <?php if (session('errors.token')) : ?>is-invalid<?php endif ?>" name="token" placeholder="<?= lang('Auth.token') ?>" value="<?= old('token', $token ?? '') ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-puzzle-piece"></span>
            </div>
          </div>

          <div class="invalid-feedback">
            <?= session('errors.token') ?>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>

          <div class="invalid-feedback">
            <?= session('errors.email') ?>
          </div>
        </div>

        <br>

        <div class="input-group mb-3">
          <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" name="password" placeholder="<?= lang('Auth.newPassword') ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

          <div class="invalid-feedback">
            <?= session('errors.password') ?>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm" placeholder="<?= lang('Auth.newPasswordRepeat') ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

          <div class="invalid-feedback">
            <?= session('errors.pass_confirm') ?>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.resetPassword') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
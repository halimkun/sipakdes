<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="login-box" style="margin-left: auto; margin-right: auto;">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="/" class="h1"><b>SIPAK</b>DES</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg"><?= lang('Auth.enterEmailForInstructions') ?></p>
            <?= view('App\Views\Auth\_message_block') ?>
            <form action="<?= url_to('forgot') ?>" method="post">
                <?= csrf_field() ?>
                <div class="input-group mb-3">
                    <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" placeholder="<?= lang('Auth.email') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                    <div class="invalid-feedback">
                        <?= session('errors.email') ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.sendInstructions') ?></button>
            </form>

            <p class="mt-3 mb-1">
                <a href="<?= url_to('login') ?>">Login</a>
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="login-box" style="margin-left: auto; margin-right: auto;">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="/" class="h1"><b>SIPAK</b>DES</a>
        </div>
        <div class="card-body">
            <?= view('App\Views\Auth\_message_block') ?>

            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <div class="input-group mb-3">
                    <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-envelope"></i>
                        </div>
                    </div>
                    <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                </div>

                <br>

                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
            </form>

            <div class="mt-3">
                <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.signIn') ?></a></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
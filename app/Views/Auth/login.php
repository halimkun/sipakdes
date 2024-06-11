<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="login-box" style="margin-left: auto; margin-right: auto;">
	<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<a href="/" class="h1"><b>SIPAK</b>DES</a>
		</div>
		<div class="card-body">
			<p class="login-box-msg">Sign in to start your session</p>

			<?= view('App\Views\Auth\_message_block') ?>

			<form action="<?= url_to('login') ?>" method="post">
				<?= csrf_field() ?>

				<?php if ($config->validFields === ['email']) : ?>
					<div class="input-group mb-3">
						<input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.email') ?>">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>

						<div class="invalid-feedback">
							<?= session('errors.login') ?>
						</div>
					</div>
				<?php else : ?>
					<div class="input-group mb-3">
						<input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>

						<div class="invalid-feedback">
							<?= session('errors.login') ?>
						</div>
					</div>
				<?php endif; ?>


				<div class="input-group mb-3">
					<input type="password" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="password" placeholder="<?= lang('Auth.password') ?>">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
					<div class="invalid-feedback">
						<?= session('errors.login') ?>
					</div>
				</div>

				<?php if (!$config->activeResetter) : ?>
					<center class="mb-3">
						Lupa password hubungi kantor desa
					</center>
				<?php endif ?>

				<div class="row">
					<div class="col-8">
						<?php if ($config->allowRemembering) : ?>
							<div class="icheck-primary">
								<input type="checkbox" id="remember" name="remember" <?php if (old('remember')) : ?> checked <?php endif ?>>
								<label for="remember">
									<?= lang('Auth.rememberMe') ?>
								</label>
							</div>
						<?php endif; ?>
					</div>

					<div class="col-4">
						<button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.loginAction') ?></button>
					</div>
				</div>
			</form>

			<?php if ($config->activeResetter) : ?>
				<p class="mb-1">
					<a href="<?= url_to('forgot') ?>">I forgot my password</a>
				</p>
			<?php endif ?>
			
			<?php if ($config->allowRegistration) : ?>
				<p class="mb-0">
					<a href="<?= url_to('register') ?>" class="text-center">Register a new membership</a>
				</p>
			<?php endif ?>
		</div>
	</div>
</div>

<?= $this->endSection() ?>
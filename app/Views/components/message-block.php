<!-- get error, errors, success from session -->

<!-- ----- error -->
<?php if (session()->get('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong class="mr-1">
            <i class="mr-1 fas fa-exclamation-triangle"></i> Error!
        </strong>
        <?= session()->get('error') ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>

<!-- ----- errors -->
<?php if (session()->get('errors')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong class="mr-1">
            <i class="mr-1 fas fa-exclamation-triangle"></i> Error!
        </strong>
        Terdapat kesalahan dalam pengisian form:

        <ul class="m-0 px-3">
            <?php foreach (session()->get('errors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>

<!-- ----- success -->
<?php if (session()->get('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong class="mr-1">
            <i class="mr-1 fas fa-check"></i> Success!
        </strong>
        <?= session()->get('success') ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>
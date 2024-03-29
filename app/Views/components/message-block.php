<!-- get error, errors, success from session -->

<!-- ----- error -->
<?php if (session()->get('error')) : ?>
    <blockquote class="quote-danger fade show m-0 mb-3" role="alert">
        <strong class="mr-1">
            <i class="mr-1 fas fa-exclamation-triangle"></i> Error!
        </strong>
        <?= session()->get('error') ?>
    </blockquote>
<?php endif ?>

<!-- ----- errors -->
<?php if (session()->get('errors')) : ?>
    <blockquote class="quote-danger fade show m-0 mb-3" role="alert">
        <strong class="mr-1">
            <i class="mr-1 fas fa-exclamation-triangle"></i> Error!
        </strong>
        Terdapat kesalahan dalam pengisian form:

        <ul class="m-0 px-3">
            <?php foreach (session()->get('errors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
    </blockquote>
<?php endif ?>

<!-- ----- success -->
<?php if (session()->get('success')) : ?>
    <blockquote class="quote-success fade show m-0 mb-3" role="alert">
        <strong class="mr-1">
            <i class="mr-1 fas fa-check"></i> Success!
        </strong>
        <?= session()->get('success') ?>
    </blockquote>
<?php endif ?>
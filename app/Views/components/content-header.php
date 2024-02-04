<?php if (isset($title) || isset($breadcrumbs)) : ?>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <?php if (isset($title)) : ?>
            <h4 class="m-0"><?= $title ?></h4>
          <?php endif ?>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <?php if (isset($breadcrumbs)) : ?>
            <!-- breadvrumbs is array -->
            <ol class="breadcrumb float-sm-right">
              <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                <?php if (isset($breadcrumb['active']) && $breadcrumb['active']) : ?>
                  <li class="breadcrumb-item active"><?= $breadcrumb['title'] ?></li>
                <?php else : ?>
                  <li class="breadcrumb-item"><a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a></li>
                <?php endif ?>
              <?php endforeach ?>
            </ol>
          <?php endif ?>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
<?php else : ?>
  <div class="pb-3"></div>
<?php endif ?>
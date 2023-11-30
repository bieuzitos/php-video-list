<?php $this->layout('_theme', ['seo' => $seo]); ?>

<?php $this->push('libraries_css'); ?>
    <?= (new \Source\Support\Csrf())->insertMetaToken(); ?>

    <link rel="stylesheet" href="<?= $this->asset('/assets/css/libraries/video-js.min.css'); ?>" />
<?php $this->end(); ?>

<?php $this->push('styles_css'); ?>
    <link rel="stylesheet" href="<?= $this->asset('/assets/css/styles/movie.min.css'); ?>" />
<?php $this->end(); ?>

<div class="container">
    <?php $this->insert('home/components/form', ['categories' => $categories]); ?>

    <?php $this->insert('home/components/list', ['categories' => $categories, 'movies' => $movies]); ?>

    <?php $this->insert('home/modal/categoryCreate'); ?>
    <?php $this->insert('home/modal/categoryDelete'); ?>
    <?php $this->insert('home/modal/videoDelete'); ?>
    <?php $this->insert('home/modal/videoPlayer'); ?>
</div>

<?php $this->push('libraries_js'); ?>
    <script src="<?= $this->asset('/assets/js/libraries/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= $this->asset('/assets/js/libraries/jquery.validate.min.js'); ?>"></script>
    <script src="<?= $this->asset('/assets/js/libraries/video.min.js'); ?>"></script>
<?php $this->end(); ?>

<?php $this->push('scripts_js'); ?>
    <script src="<?= $this->asset('/assets/js/scripts/notification.min.js'); ?>"></script>

    <?php $this->insert('home/scripts/categoryCreate'); ?>
    <?php $this->insert('home/scripts/categoryDelete'); ?>
    <?php $this->insert('home/scripts/videoDelete'); ?>
    <?php $this->insert('home/scripts/videoPlayer'); ?>
    <?php $this->insert('home/scripts/video'); ?>
<?php $this->end(); ?>
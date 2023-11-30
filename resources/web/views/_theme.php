<!DOCTYPE html>
<html lang="<?= str_replace('_', '-', SITE_LOCALE); ?>">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?= $seo; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?= $this->section('libraries_css'); ?>

    <link rel="stylesheet" href="<?= $this->asset('/assets/css/styles/global.min.css'); ?>" />
    <?= $this->section('styles_css'); ?>
</head>

<body>
    <header></header>

    <main>
        <?= $this->section('content'); ?>
    </main>

    <footer><br></footer>

    <script src="<?= $this->asset('/assets/js/libraries/jquery.min.js'); ?>"></script>
    <?= $this->section('libraries_js'); ?>

    <script src="<?= $this->asset('/assets/js/scripts/main.min.js'); ?>"></script>
    <?= $this->section('scripts_js'); ?>
</body>
</html>
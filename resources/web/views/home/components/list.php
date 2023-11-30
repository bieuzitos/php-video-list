<div class="list">
    <div class="list-header">
        <div class="list-title">
            <span>Videos</span>
        </div>
        <div class="list-count"><?= count($movies); ?></div>
    </div>

    <div class="list-middle">
        <div class="list-content">
            <?php foreach ($movies as $key => $movie) : ?>
                <a class="" data-href="<?= $movie['url']; ?>" data-movie="<?= $movie['_id']; ?>">
                    <div class="list-item">
                        <picture>
                            <img src="" />
                            <div class="overlay">
                                <i class="fa-regular fa-circle-play"></i>
                            </div>
                            <div class="identifier">
                                <span><?= $movie['_id']; ?></span>
                            </div>
                        </picture>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="<?= ($movies ? 'list-empty hidden' : 'list-empty'); ?>">
            <div class="icon">
                <i class="fa-solid fa-film"></i>
            </div>
            <span class="text">Não há videos disponíveis no momento!</span>
        </div>
    </div>
</div>
<div class="form">
    <div class="form-header">
        <div class="form-title">Compartilhe <i>Links</i></div>
        <span class="form-subtitle">Salve os seus diversos links de videos e selecione a qual categoria ele pertence!</span>
    </div>

    <form id="movie" action="<?= $router->route('movie.create'); ?>">
        <div class="form-middle">
            <div class="input">
                <input name="url" placeholder=" " value="" />
                <label>Url</label>
                <div class="icon">
                    <i class="fa-solid fa-link"></i>
                </div>
                <div class="submit disabled">
                    <button class="disabled" disabled><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <div class="form-categories">
                <div class="categories-list">
                    <?php foreach ($categories as $category) : ?>
                        <input id="item-<?= $category['_id']; ?>" name="category" type="radio" value="<?= $category['_id']; ?>">
                        <label for="item-<?= $category['_id']; ?>"><?= $category['name']; ?></label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-action">
                <button data-category="create">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button data-category="delete">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<div id="categoryDelete_modal" class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content small">
            <div class="modal-header">
                <span class="modal-title">Remover categorias</span>
                <div class="modal-action">
                    <div class="modal-button">
                        <button class="close" data-bs-dismiss="modal" aria-label="fechar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <form action="<?= $router->route('category.delete'); ?>">
                    <div class="form-categories">
                        <div class="categories-list danger"></div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <div class="modal-button transparent" data-bs-dismiss="modal">
                    <a>Cancelar</a>
                </div>
                <div class="modal-button disabled">
                    <button class="disabled" disabled>Remover</button>
                </div>
            </div>
        </div>
    </div>
</div>
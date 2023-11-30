<div id="categoryCreate_modal" class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content small">
            <div class="modal-header">
                <span class="modal-title">Criar categorias</span>
                <div class="modal-action">
                    <div class="modal-button">
                        <button class="close" data-bs-dismiss="modal" aria-label="fechar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <form action="<?= $router->route('category.create'); ?>">
                    <div class="input">
                        <input name="category" placeholder=" " />
                        <label>Categoria</label>
                        <div class="icon">
                            <i class="fa-solid fa-table-cells-large"></i>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <div class="modal-button transparent" data-bs-dismiss="modal">
                    <a>Cancelar</a>
                </div>
                <div class="modal-button disabled">
                    <button class="disabled" disabled>Adicionar</button>
                </div>
            </div>
        </div>
    </div>
</div>
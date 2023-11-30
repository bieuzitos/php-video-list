<script>
    const category_delete_modal = '#categoryDelete_modal'
    const category_delete_form = category_delete_modal + ' form '

    const category_delete_button_1 = '#categoryDelete_modal .modal-footer .modal-button'
    const category_delete_button_2 = '#categoryDelete_modal .modal-footer .modal-button button'
    const category_delete_button_3 = '#categoryDelete_modal .modal-footer .modal-button a'

    $(document).ready(function() {
        $('.form-action button[data-category="delete"]').on('click', function(event) {
            event.preventDefault()
            getCategories()
        })

        $(category_delete_button_3).on('click', function(event) {
            event.preventDefault()
            $(category_delete_modal).modal('hide')
            formCategoryDeleteReset()
        })

        $(category_delete_button_2).on('click', function(event) {
            event.preventDefault()
            $(category_delete_form).submit()
        })

        let formCategoryDelete = $(category_delete_form).validate({
            errorElement: 'span',
            rules: {
                category: {
                    required: true
                }
            },
            messages: {
                category: {
                    required: 'A categoria é um campo obrigatório.'
                }
            },
            submitHandler: function(form) {
                if (validationCategoryDelete()) {
                    return
                }

                $.ajax({
                    url: $(form).prop('action'),
                    type: 'POST',
                    data: getToken() + 'categories=' + JSON.stringify(getCheckedCategories()),
                    dataType: 'JSON',
                    beforeSend: function() {
                        submitButton('start', category_delete_button_1, category_delete_button_2)
                    },
                    success: function(action) {
                        formCategoryDeleteReset()

                        if (action.status) {
                            $.showNotification({
                                type: 'success',
                                message: action.status_message
                            })

                            addToken(action.token)
                            delCategory(action.categories)

                            formCategoryDeleteReset()
                            return
                        }

                        $.showNotification({
                            type: action.status_type,
                            message: action.status_message
                        })
                    },
                    error: function(action) {
                        if (action.responseJSON.status_input) {
                            submitButton('close', category_delete_button_1, category_delete_button_2)

                            const errors = {
                                category: action.responseJSON.status_message
                            }
                            formCategoryDelete.showErrors(errors)
                            return
                        }

                        $.showNotification({
                            type: 'error',
                            message: 'Não foi possível processar a sua solicitação!'
                        })
                    }
                })
            }
        })

        function formCategoryDeleteReset() {
            formCategoryDelete.resetForm()
            submitButton('close', category_delete_button_1, category_delete_button_2)

            $(category_delete_form)[0].reset()
            $(category_delete_form).find('.error').removeClass('error')
            $(category_delete_form).find('[aria-invalid]').removeAttr('aria-invalid')
            $(category_delete_form).find('[aria-describedby]').removeAttr('aria-describedby')
            $(category_delete_form).find('span').remove()
        }
    })

    $(document).on('click', category_delete_form + 'input[type="checkbox"]', function() {
        validationCategoryDelete()
    })

    function validationCategoryDelete() {
        let empty = false

        $(category_delete_form + 'input').each(function() {
            if ($(category_delete_form).find('input.error')[0]) {
                empty = true
            }

            if ($(this).attr('name') === 'category') {
                if ($(this).is(':checked')) {
                    empty = true
                    return false
                }
            }
        })

        if (empty) {
            $(category_delete_button_1).removeClass('disabled')
            $(category_delete_button_2).removeClass('disabled').prop('disabled', false)
        } else {
            $(category_delete_button_1).addClass('disabled')
            $(category_delete_button_2).addClass('disabled').prop('disabled', true)
        }
    }

    function getCategories() {
        $.ajax({
            url: '<?= $router->route('category.list'); ?>',
            type: 'POST',
            dataType: 'JSON',
            success: function(action) {
                if (action.status) {
                    addToken(action.token)
                    addCategoryList(action.categories)

                    $(category_delete_modal).modal('show')
                    return
                }

                $.showNotification({
                    type: action.status_type,
                    message: action.status_message
                })
            },
            error: function() {
                formCategoryDeleteReset()

                $.showNotification({
                    type: 'error',
                    message: 'Não foi possível processar a sua solicitação!'
                })
            }
        })
    }

    function getCheckedCategories() {
        let categories = []

        $(category_delete_form + 'input').each(function() {
            if ($(this).attr('name') === 'category') {
                if ($(this).is(':checked')) {
                    categories.push(parseInt($(this).val()))
                }
            }
        })

        return categories
    }

    function addCategoryList(categories) {
        $(category_delete_form + '.categories-list').empty()

        $.each(categories, function(index, value) {
            const content = `
                <input id="del-${value._id}" name="category" type="checkbox" value="${value._id}">
                <label for="del-${value._id}">${value.name}</label>`

            $(category_delete_form + '.categories-list').prepend(content)
        })
    }

    function delCategory(categories) {
        $(category_delete_modal).modal('hide')

        $.each(categories, function(index, value) {
            $('#item-' + value._id).next('label').addBack().remove()
        })
    }
</script>
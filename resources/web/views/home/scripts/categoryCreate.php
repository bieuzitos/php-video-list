<script>
    const category_create_modal = '#categoryCreate_modal'
    const category_create_form = category_create_modal + ' form '

    const category_create_button_1 = '#categoryCreate_modal .modal-footer .modal-button'
    const category_create_button_2 = '#categoryCreate_modal .modal-footer .modal-button button'
    const category_create_button_3 = '#categoryCreate_modal .modal-footer .modal-button a'

    $(document).ready(function() {
        $('.form-action button[data-category="create"]').on('click', function(event) {
            event.preventDefault()
            $(category_create_modal).modal('show')
        })

        $(category_create_button_3).on('click', function(event) {
            event.preventDefault()
            $(category_create_modal).modal('hide')
            formCategoryCreateReset()
        })

        $(category_create_button_2).on('click', function(event) {
            event.preventDefault()
            $(category_create_form).submit()
        })

        $(category_create_form + '.input input').focusin(function() {
            let interval = setInterval(function() {
                validationCategory()
            }, 200)

            $(this).focusout(function() {
                clearInterval(interval)
            })
        })

        $(category_create_form + '.input input').on('input', function() {
            validationCategory()
        })

        $.validator.addMethod('alpha', function(value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value)
        })

        let formCategoryCreate = $(category_create_form).validate({
            errorElement: 'span',
            rules: {
                category: {
                    required: true,
                    minlength: 3,
                    maxlength: 16,
                    alpha: true
                }
            },
            messages: {
                category: {
                    required: 'A categoria é um campo obrigatório.',
                    minlength: jQuery.validator.format('A categoria deve conter no mínimo {0} caracteres.'),
                    maxlength: jQuery.validator.format('A categoria deve conter no máximo {0} caracteres.'),
                    space: 'A categoria não pode conter espaços em branco.',
                    alpha: 'A categoria só pode conter letras.'
                }
            },
            submitHandler: function(form) {
                if (validationCategory()) {
                    return
                }

                $.ajax({
                    url: $(form).prop('action'),
                    type: 'POST',
                    data: getToken() + $(form).serialize(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        submitButton('start', category_create_button_1, category_create_button_2)
                    },
                    success: function(action) {
                        formCategoryCreateReset()

                        if (action.status) {
                            $.showNotification({
                                type: 'success',
                                message: action.status_message
                            })

                            addToken(action.token)
                            addCategory(action.category)

                            formCategoryCreateReset()
                            return
                        }

                        $.showNotification({
                            type: action.status_type,
                            message: action.status_message
                        })
                    },
                    error: function(action) {
                        if (action.responseJSON.status_input) {
                            submitButton('close', category_create_button_1, category_create_button_2)

                            const errors = {
                                category: action.responseJSON.status_message
                            }
                            formCategoryCreate.showErrors(errors)
                            return
                        }

                        formMovieReset()

                        $.showNotification({
                            type: 'error',
                            message: 'Não foi possível processar a sua solicitação!'
                        })
                    }
                })
            }
        })

        function formCategoryCreateReset() {
            formCategoryCreate.resetForm()
            submitButton('close', category_create_button_1, category_create_button_2)

            $(category_create_form)[0].reset()
            $(category_create_form).find('.error').removeClass('error')
            $(category_create_form).find('[aria-invalid]').removeAttr('aria-invalid')
            $(category_create_form).find('[aria-describedby]').removeAttr('aria-describedby')
            $(category_create_form).find('span').remove()
        }
    })

    function validationCategory() {
        let empty = false

        $(category_create_form + '.input input:not([disabled])').each(function() {
            if ($(this).val().length === 0 || $(category_create_form + '.input').find('input.error')[0]) {
                empty = true
            }

            if ($(this).attr('name') === 'category') {
                let input = $(this).val()
                let regex_name = /^[a-zA-Z]+$/
                if (input.length < 3 || input.length > 16 || !regex_name.test(input)) {
                    empty = true
                }
            }
        })

        if (empty) {
            $(category_create_button_1).addClass('disabled')
            $(category_create_button_2).addClass('disabled').prop('disabled', true)
            return true
        } else {
            $(category_create_button_1).removeClass('disabled')
            $(category_create_button_2).removeClass('disabled').prop('disabled', false)
        }

        return false
    }

    function addCategory(category) {
        $(category_create_modal).modal('hide')

        const content = `
            <input id="item-${category._id}" name="category" type="radio" value="${category._id}">
            <label for="item-${category._id}">${category.name}</label>`

        $('form#movie .categories-list').prepend(content)
    }
</script>
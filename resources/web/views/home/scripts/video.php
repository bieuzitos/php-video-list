<script>
    const movie_form = '.form form#movie '

    const movie_button_1 = 'form#movie .submit'
    const movie_button_2 = 'form#movie .submit button'

    $(document).ready(function() {

        $(movie_button_1).on('click', function(event) {
            event.preventDefault()
            $(movie_form).submit()
        })

        $(movie_form + '.input input').focusin(function() {
            let interval = setInterval(function() {
                validationMovie()
            }, 200)

            $(this).focusout(function() {
                clearInterval(interval)
            })
        })

        $(movie_form + '.input input').on('input', function() {
            validationMovie()
        })

        $.validator.addMethod('video', function(value, element) {
            return this.optional(element) || /\.(m3u8|mp4)(?:\?[^\s]*)?$/.test(value)
        })

        let formMovie = $(movie_form).validate({
            errorElement: 'span',
            rules: {
                url: {
                    required: true,
                    url: true,
                    video: true
                },
                category: {
                    required: true
                }
            },
            messages: {
                url: {
                    required: 'A url é um campo obrigatório.',
                    url: 'A url informada não é valida.',
                    video: 'A url deve conter um arquivo vídeo.'
                },
                category: {
                    required: 'A categoria é um campo obrigatório.'
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr('type') === 'radio' && element.hasClass('error')) {
                    error.insertBefore(element.closest('.categories-list'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                if (validationMovie()) {
                    return
                }

                $.ajax({
                    url: $(form).prop('action'),
                    type: 'POST',
                    data: getToken() + $(form).serialize(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        submitButton('start', movie_button_1, movie_button_2)
                    },
                    success: function(action) {
                        formMovieReset()

                        if (action.status) {
                            $.showNotification({
                                type: 'success',
                                message: action.status_message
                            })

                            addToken(action.token)
                            addMovie(action.movie)

                            formMovieReset()
                            return
                        }

                        $.showNotification({
                            type: action.status_type,
                            message: action.status_message
                        })
                    },
                    error: function(action) {
                        if (action.responseJSON.status_input) {
                            submitButton('close', movie_button_1, movie_button_2)

                            const errors = {
                                url: action.responseJSON.status_message
                            }
                            formMovie.showErrors(errors)
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

        function formMovieReset() {
            formMovie.resetForm()
            submitButton('close', movie_button_1, movie_button_2)

            $(movie_form)[0].reset()
            $(movie_form).find('.error').removeClass('error')
            $(movie_form).find('[aria-invalid]').removeAttr('aria-invalid')
            $(movie_form).find('[aria-describedby]').removeAttr('aria-describedby')
            $(movie_form).find('span').remove()
        }

        function validationMovie() {
            let empty = false

            $(movie_form + '.input input:not([disabled])').each(function() {
                if ($(this).val().length === 0 || $(movie_form + '.input').find('input.error')[0]) {
                    empty = true
                }

                if ($(this).attr('name') === 'url') {
                    let input = $(this).val()
                    let regex_url = /^(?:(?:(?:https?|ftp):)?\/\/)(?:(?:[^\]\[?\/<~#`!@$^&*()+=}|:";',>{ ]|%[0-9A-Fa-f]{2})+(?::(?:[^\]\[?\/<~#`!@$^&*()+=}|:";',>{ ]|%[0-9A-Fa-f]{2})*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\u00a1-\uffff][a-z0-9\u00a1-\uffff_-]{0,62})?[a-z0-9\u00a1-\uffff]\.)+(?:[a-z\u00a1-\uffff]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$/
                    let regex_m3u8 = /\.(m3u8|mp4)(?:\?[^\s]*)?$/
                    if (!regex_url.test(input) || !regex_m3u8.test(input)) {
                        empty = true
                    }
                }
            })

            if (empty) {
                $(movie_button_1).addClass('disabled')
                $(movie_button_2).addClass('disabled').prop('disabled', true)
                return true
            } else {
                $(movie_button_1).removeClass('disabled')
                $(movie_button_2).removeClass('disabled').prop('disabled', false)
            }

            return false
        }

        function addMovie(movie) {
            $('.list .list-empty').addClass('hidden')

            const count = parseInt($('.list .list-count').text())
            const content = `<a class="" data-href="${movie.url}" data-movie="${movie._id}">
                <div class="list-item">
                    <picture>
                        <img src="" />
                        <div class="overlay">
                            <i class="fa-regular fa-circle-play"></i>
                        </div>
                        <div class="identifier">
                            <span>${movie._id}</span>
                        </div>
                    </picture>
                </div>
            </a>`

            let newCount = count + 1

            $('.list .list-count').text(newCount)
            $('.list .list-content').prepend(content)
        }
    })
</script>
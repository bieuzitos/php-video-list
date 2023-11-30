<script>
    const video_delete_modal = '#videoDelete_modal'

    const video_delete_button_1 = video_delete_modal + ' .modal-footer .modal-button'
    const video_delete_button_2 = video_delete_modal + ' .modal-footer .modal-button button'
    const video_delete_button_3 = video_delete_modal + ' .modal-footer .modal-button a'

    $(document).on('click', video_delete_button_2, function(event) {
        event.preventDefault()

        const movie = $(this).attr('data-movie')

        $.ajax({
            url: '<?= $router->route('movie.delete'); ?>',
            type: 'POST',
            data: getToken() + 'movie=' + movie,
            dataType: 'JSON',
            beforeSend: function() {
                submitButton('start', video_delete_button_1, video_delete_button_2)
            },
            success: function(action) {
                submitButton('active', video_delete_button_1, video_delete_button_2)

                if (action.status) {
                    $.showNotification({
                        type: 'success',
                        message: action.status_message
                    })

                    addToken(action.token)
                    delMovie(action.movie)

                    return
                }

                $.showNotification({
                    type: action.status_type,
                    message: action.status_message
                })
            },
            error: function(action) {
                submitButton('active', video_delete_button_1, video_delete_button_2)

                $.showNotification({
                    type: 'error',
                    message: 'Não foi possível processar a sua solicitação!'
                })
            }
        })
    })

    $(document).on('click', category_delete_button_3, function(event) {
        event.preventDefault()
        $(video_delete_modal).modal('hide')
    })

    function delMovie(movie) {
        $(video_delete_modal).modal('hide')

        const list = $('.list .list-content a');

        list.each(function(index) {
            const data = $(this).data('movie')

            if (data === movie._id) {
                $(this).remove()

                const last = index === list.length - 1;
                if (last) {
                    $('.list .list-empty').removeClass('hidden')
                }
            }
        })
    }
</script>
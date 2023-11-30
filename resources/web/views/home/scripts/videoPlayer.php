<script>
    let videoPlayer

    const video_player_modal = '#videoPlayer_modal'

    $(document).on('click', '#videoPlayer_modal .modal-button button[data-type="delete"]', function(event) {
        event.preventDefault()
        $(video_player_modal).modal('hide')
        $(video_delete_modal).modal('show')
    })

    $(document).on('click', '.list .list-content a', function(event) {
        const source = $(this).data('href')

        $(video_delete_modal + ' .modal-footer .modal-button button').attr('data-movie', $(this).data('movie'))

        $(video_player_modal + ' .modal-body video source').attr('src', source)
        $(video_player_modal).modal('show')

        if (!videoPlayer) {
            videoPlayer = videojs('video')
        }

        videoPlayer.reset();
        videoPlayer.src(source)
    })

    $(video_player_modal).on('hidden.bs.modal', function() {
        if (videoPlayer) {
            videoPlayer.pause()
        }
    })
</script>

/*
|--------------------------------------------------------------------------
| Button Submit
|--------------------------------------------------------------------------
*/

$('form').on('keypress', function (event) {
    let press = event.keyCode || event.which
    if (press === 13) {
        event.preventDefault()
        return false
    }
})

/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

function getToken() {
    let content = $('meta[name="csrf_token"]').attr('content')
    if (content !== undefined && content !== '') {
        return 'csrf_token=' + encodeURIComponent(content) + '&'
    }

    return ''
}

function addToken(content) {
    $('meta[name="csrf_token"]').attr('content', content)
}

/*
|--------------------------------------------------------------------------
| Submit Button
|--------------------------------------------------------------------------
*/

function submitButton(action, div, button) {
    if (action === 'start') {
        if ($(button).attr('loading') === true) {
            return false
        }

        $(div).removeClass('disabled').addClass('inactive')
        $(button).attr('loading', true).removeClass('disabled').addClass('inactive')
        $(button).attr('data-text', $(button).html())
        $(button).css('width', $(button).outerWidth()).html('<i class="fa-solid fa-spinner fa-spin"></i>')
    }

    if (action === 'close') {
        $(div).addClass('disabled').removeClass('inactive')
        $(button).attr('loading', false).addClass('disabled').removeClass('inactive')
        $(button).attr('disabled', true)
        $(button).css('width', '').html($(button).attr('data-text'))
    }

    if (action === 'active') {
        $(parent).removeClass('disabled').removeClass('inactive')
        $(button).attr('loading', false).removeClass('disabled').removeClass('inactive')
        $(button).attr('disabled', false)
        $(button).css('width', '').html($(button).attr('data-text'))
    }
}
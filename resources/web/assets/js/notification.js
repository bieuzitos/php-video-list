(function ($) {
    function Notification(props) {
        this.props = {
            position: 'bottom-right',
            type: '',
            message: '',
            duration: 10000
        }

        this.containerId = 'notification'

        for (var prop in props) {
            this.props[prop] = props[prop]
        }

        this.template =
            "<div class='notification-content " + this.props.type + "'>" +
            "   <div class='notification-icon'>" +
            "       <i></i>" +
            "   </div>" +
            "   <div class='notification-message'>" +
            "       <span>" + this.props.message + "</span>" +
            "   </div>" +
            "   <div class='notification-close'>" +
            "       <button>" +
            "           <i class='fas fa-times'></i>" +
            "       </button>" +
            "   </div>" +
            "</div>"

        this.$container = $('#' + this.containerId)

        if (!this.$container.length) {
            this.$container = $('<div id="' + this.containerId + '" class="' + this.props.position + '"></div>')
            $(document.body).append(this.$container)
        }

        this.showNotification()
    }

    Notification.prototype.showNotification = function () {
        var $notification = $(this.template)
        this.$container.prepend($notification)

        $notification.on('click', '.notification-close button', function () {
            $notification.fadeOut(500, function () {
                $notification.remove()
            })
        })

        if (this.props.duration) {
            setTimeout(function () {
                $notification.fadeOut(700, function () {
                    $notification.remove()
                })
            }, this.props.duration)
        }
    }

    Notification.prototype.removeNotification = function () {
        var $notification = $(this.template)
        this.$container.prepend($notification)
        $notification.remove()
    }

    $.extend({
        showNotification: function (props) {
            return new Notification(props)
        },
        removeNotification: function () { }
    })
}(jQuery))
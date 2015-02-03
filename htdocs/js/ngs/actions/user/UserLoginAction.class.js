ngs.UserLoginAction = Class.create(ngs.AbstractAction, {
    initialize: function($super, shortCut, ajaxLoader) {
        $super(shortCut, "user", ajaxLoader);
    },
    getUrl: function() {
        return "do_login";
    },
    getMethod: function() {
        return "POST";
    },
    beforeAction: function() {
    },
    afterAction: function(transport) {
        var data = transport.responseText.evalJSON();
        if (data.status === "ok") {
            window.location.reload();
        } else if (data.status === "err") {
            jQuery('#fl_user_login_error').html(data.message);
        }
    }
});

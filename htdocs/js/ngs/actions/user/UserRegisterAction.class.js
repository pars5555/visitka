ngs.UserRegisterAction = Class.create(ngs.AbstractAction, {
    initialize: function($super, shortCut, ajaxLoader) {
        $super(shortCut, "user", ajaxLoader);
    },
    getUrl: function() {
        return "do_register";
    },
    getMethod: function() {
        return "POST";
    },
    beforeAction: function() {
    },
    afterAction: function(transport) {
        var data = transport.responseText.evalJSON();
        if (data.status === "ok") {
            jQuery('#f_registration_popup_dialog').dialog('close');
        } else if (data.status === "err") {
            jQuery('#ur_user_register_error').html(data.message);
        }
    }
});

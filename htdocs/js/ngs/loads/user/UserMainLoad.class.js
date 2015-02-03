ngs.UserMainLoad = Class.create(ngs.AbstractLoad, {
    initialize: function($super, shortCut, ajaxLoader) {
        $super(shortCut, "user", ajaxLoader);
    },
    getUrl: function() {
        return "main";
    },
    getMethod: function() {
        return "POST";
    },
    getContainer: function() {
        return "content";
    },
    getName: function() {
        return "user_main";
    },
    afterLoad: function() {  
        ngs.nestLoad(jQuery("#contentLoad").val(), {});
    }
});

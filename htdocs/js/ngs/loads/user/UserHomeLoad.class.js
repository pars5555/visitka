ngs.UserHomeLoad = Class.create(ngs.AbstractLoad, {
    initialize: function($super, shortCut, ajaxLoader) {
        $super(shortCut, "user", ajaxLoader);
    },
    getUrl: function() {
        return "home";
    },
    getMethod: function() {
        return "POST";
    },
    getContainer: function() {
        return "content";
    },
    getName: function() {
        return "user_home";
    },
    afterLoad: function() {
  
    }
});

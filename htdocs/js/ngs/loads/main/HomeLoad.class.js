ngs.HomeLoad = Class.create(ngs.AbstractLoad, {
    initialize: function($super, shortCut, ajaxLoader) {
        $super(shortCut, "home", ajaxLoader);
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
        return "home";
    },
    afterLoad: function() {
      
    }
    


});

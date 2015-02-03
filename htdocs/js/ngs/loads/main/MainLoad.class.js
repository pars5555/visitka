ngs.MainLoad = Class.create(ngs.AbstractLoad, {
    initialize: function($super, shortCut, ajaxLoader) {
        $super(shortCut, "main", ajaxLoader);
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
        return "main";
    },
    afterLoad: function() {
      ngs.nestLoad(jQuery("#contentLoad").val(), {});
    }
    


});

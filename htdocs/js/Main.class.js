ngs.tracksItemContent = [];
Object.extend(ngs, {
    ajaxLoader: null,
    pageManager: null,
    toolbarManager: null,
    loadtracking: true,
    
    setCurrentmanager: function(currentManager){
        this.currentManager = currentManager;
    },
    
    load: function(loadName, params, dynContainer){
        var load = this.getLoad(loadName);
        if (!params || typeof(params) == "undefined") {
            params = {};
        }
        load.setParams(params);
        load.setDynContainer(dynContainer);
        load.load();
    },
    
    action: function(actionName, params){
        var action = this.actionFactory.getAction(actionName);
        action.setParams(params);
        action.action();
    },
    
    getLoad: function(loadName){
        return this.loadFactory.getLoad(loadName);
    },
    
    main: function(){
        this.defaultParams = {};
        //		this.defaultParams.userId = false;
        //		if($("defaultUserId") && $("defaultUserId").value != ""){
        //			this.defaultParams.userId = $("defaultUserId").value;
        //		}
        
        ngs.LoadManager.init();
        
        this.ajaxLoader = new ngs.AjaxLoader(SITE_URL + "/dyn/", "ajax_loader", ngs.LoadManager, this.defaultParams);
        
        this.loadFactory = new ngs.LoadFactory(this.ajaxLoader);
        this.actionFactory = new ngs.ActionFactory(this.ajaxLoader);
        this.ajaxLoader.start(this.loadFactory, null);
        
        
        if ($("disableLoadTracking")) {
            this.ajaxLoader.disableLoadtracking();
        }
        this.runInitialLoad();
    },
    
    nestLoad: function(loadName, params){
        var load = this.loadFactory.getLoad(loadName);
        if (params) {
            load.setParams(params);
        }
        load.type = "nested";
        this.ajaxLoader.afterLoad(load);  
    },
    
    runInitialLoad: function(){
        var initialLoadElem = $("initialLoad");
        if (initialLoadElem) {
            this.nestLoad(initialLoadElem.value);
        }
    },
    
    getDefaultParams: function(){
        return this.defaultParams;
    }
    
});

document.observe("dom:loaded", function() {
	ngs.main(ngs);
});

Element.prototype.attr = function(attrName, attrValue){
	if(attrValue){
		return this.writeAttribute(attrName, attrValue);
	}
	return this.readAttribute(attrName);
};
var stripHtml = function(htmlValue){
	if(htmlValue){
		var tmp = document.createElement("DIV");
	  tmp.innerHTML = htmlValue;
	  return tmp.textContent||tmp.innerText;
	}
};

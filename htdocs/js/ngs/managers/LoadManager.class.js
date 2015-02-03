ngs.LoadManager={
	
  init: function(){
   	this.urlObserver = ngs.UrlObserver.init();
   	this.urlObserver.addListener(this.onUrlChange.bind(this));
   	
  },
  
  start: function(loadFactory){
  	this.loadFactory = loadFactory;
  	this.initLoads();
  },
  
  initLoads: function(){
  	var loadStr = this.urlObserver.getUrl();
  	var loadObj = this.toObject(loadStr);
		//this.runLoads(loadObj);  	
  },
  
  setLoad: function(load){
  
  	var method = load.getMethod();
  	if(method.toUpperCase() == "POST"){
  		return;
  	}
  	var loadStr = this.urlObserver.getUrl();
  	var loadObj = this.toObject(loadStr, load);
  	var curLoads = this.computeObject(loadObj);
  	var loads = ngs.DeepnessArranger.arrange(this.createObj(load), curLoads);
	  var loadStr = this.toString(loads);
	  loadStr = "/"+loadStr;
	  this.urlObserver.setUrl(loadStr);
  },
  
  onUrlChange: function(oldLocation, location){
  	var loadObj = this.toObject(location);
  	this.runLoads(loadObj);  	  	
  },
  
  runLoads: function(objs){
  	if(!objs){
  		return;
  	}
  	for(var i = 0; i< objs.length; i++){
  		var obj = objs[i];
	  	var load = this.loadFactory.getLoad(obj.s);
	  	load.setParams(obj.p);
	  	load.load();
	  	if(obj.a && obj.a.length > 0){
	  		this.runLoads(obj.a);
	  	}
	  }
  },
	
	toString: function(obj){
		return ngs.UrlFormater.toString(obj);
	},
	
	toObject: function(str, loadObj){
		return ngs.UrlFormater.toObject(str);
	},
	computeObject: function(objs){
		if(!objs){
  		return [];
  	}
  	var newObjs = [];
  	for(var i = 0; i< objs.length; i++){
  		var obji = objs[i];
  		var load = this.loadFactory.getLoad(obji.s);
	  	load.setParams(obji.p);
  		var obj = {
									elem: $(load.getContainer()),
									elems: [],
									load: load
								};
  		
	  	if(obji.a && obji.a.length > 0){
	  		obj.elems = this.computeObject(obji.a);
	  	}
	  	newObjs.push(obj);
	  }
	  return newObjs;
	},
	
	createObj: function(load){
		var obj = {
								elem: $(load.getContainer()),
								elems: [],
								load: load
							};
		
		return obj;
	}
};

//[{s:short1,p:[param:value],a:[{s:short2,p:[param:value],{s:short1,p:[param:value],a:[short2]}]
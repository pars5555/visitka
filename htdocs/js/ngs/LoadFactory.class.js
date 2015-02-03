ngs.LoadFactory= Class.create();
ngs.LoadFactory.prototype={
	
	initialize: function(ajaxLoader){
		this.loads = [];
		this.loads["main"] = function temp(){return new ngs.MainLoad("main", ajaxLoader);};
		this.loads["home"] = function temp(){return new ngs.HomeLoad("home", ajaxLoader);};
		
		
        //admin
        this.loads["admin_main"] = function temp(){return new ngs.AdminMainLoad("admin_main", ajaxLoader);};
		this.loads["admin_home"] = function temp(){return new ngs.AdminHomeLoad("admin_home", ajaxLoader);};
		this.loads["user_main"] = function temp(){return new ngs.DentistMainLoad("user_main", ajaxLoader);};
		this.loads["user_home"] = function temp(){return new ngs.DentistHomeLoad("user_home", ajaxLoader);};
       
      
		},
	
	getLoad: function(name){
		try{
			return this.loads[name]();
		}
		catch(ex){
		}
	}
};
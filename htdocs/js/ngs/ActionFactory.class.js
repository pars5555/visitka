ngs.ActionFactory = Class.create();
ngs.ActionFactory.prototype={

	initialize: function(ajaxLoader){
		this.actions = [];

        //admin
       // this.actions["admin_add_user"] = function temp(){return new ngs.AdminAddUserAction("admin_add_user", ajaxLoader);};
        },

	getAction: function(name){
		return this.actions[name]();
	}
};
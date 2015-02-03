/**
* @fileoverview
* @class core class of the framework which is responsible for ajax requests and delivery of responses
* work with loads and actions which are for supporting same structure as backend has
* @author Naghashyan Solutions, e-mail:info@naghashyan.com 
* @version 1.0
*/

Framework.Media = Class.create();
Framework.Media.prototype={
	initialize: function(accessor, ajaxLoader, callbacks, isSecure){
		
		this.dispatcher = "media";
  	this.isLocked = accessor.isLocked();
  	this.mediaId = accessor.getMediaId();

  	this.ajaxLoader = ajaxLoader;
  	this.callbacks = callbacks;
  	this.isSecure = isSecure? isSecure: false;
  	this.currentStatus = accessor.getCurrentStatus();
  	//this.afterStatusChanges = afterStatusChanges? afterStatusChanges: Prototype.emptyFunction;
 		
  	this.approveBox = accessor.statuses.getApproveBox();
  	this.removeBox = accessor.statuses.getRemoveBox();
  	this.retouchBox = accessor.statuses.getRetouchBox();
  	this.retouchOffBox = accessor.statuses.getRetouchOffBox();
  	
  	if(!this.isLocked){
	  	this.approveBox.onclick = this.markToRemove.bind(this);
	  	this.removeBox.onclick = this.markToApprove.bind(this);
	  	this.retouchBox.onclick = this.markToRetouch.bind(this);
	  	this.retouchOffBox.onclick = this.markToRetouchOffBox.bind(this);
	  	this.activeStatus();
	  }
  	
  	if(this.deleteBox = accessor.getDeleteBox()){
  		this.deleteBox.onclick = this.deleteMedia.bind(this);
  	}
  	
  },
  
  activeStatus: function(){
			
		if(Framework.ImageStatuses.TO_RETOUCH == this.currentStatus){
			
			Element.hide(this.removeBox);
			Element.hide(this.retouchOffBox);
			Element.show(this.retouchBox);
			Element.show(this.approveBox);
		}
		else if(Framework.ImageStatuses.APPROUVED == this.currentStatus){
			
			Element.hide(this.removeBox);
			Element.hide(this.retouchBox);
			Element.show(this.retouchOffBox);
			Element.show(this.approveBox);
			
		}
		else if(Framework.ImageStatuses.DONT_SAVE == this.currentStatus){
		
			Element.hide(this.approveBox);
			Element.hide(this.retouchBox);
			Element.show(this.retouchOffBox);
			Element.show(this.removeBox);
			
		}
		
		
  },
  
  changeStatus: function(mediaId, status){
  	this.ajaxLoader.setSecure(this.isSecure);
  	this.ajaxLoader.setDispatcher(this.dispatcher);		
  	var params = 	{
  									mediaId: mediaId,
  									status: status
  								};
  	this.ajaxLoader.request("do_change_status", params, this.callbacks.onStatusChange);
  	
  },

  
  markToRemove : function(){

		Element.hide(this.retouchBox);
		Element.hide(this.approveBox);
		Element.show(this.retouchOffBox);
		Element.show(this.removeBox);
		
		this.currentStatus = Framework.ImageStatuses.DONT_SAVE;
		this.changeStatus(this.mediaId, Framework.ImageStatuses.DONT_SAVE);
  },
  
  markToApprove : function(){

		Element.hide(this.removeBox);
		Element.show(this.approveBox);
		
		this.currentStatus = Framework.ImageStatuses.APPROUVED;
		this.changeStatus(this.mediaId, Framework.ImageStatuses.APPROUVED);
		
  },
  
  markToRetouch: function(){
	
		Element.hide(this.retouchBox);
		Element.hide(this.removeBox);
		Element.show(this.retouchOffBox);
		Element.show(this.approveBox);
		
		this.currentStatus = Framework.ImageStatuses.APPROUVED;
		this.changeStatus(this.mediaId, Framework.ImageStatuses.APPROUVED);
		
  },
  
  markToRetouchOffBox: function(){

		Element.hide(this.retouchOffBox);
		Element.hide(this.removeBox);
		Element.show(this.retouchBox);
		Element.show(this.approveBox);
		
		this.currentStatus = Framework.ImageStatuses.TO_RETOUCH;
		this.changeStatus(this.mediaId, Framework.ImageStatuses.TO_RETOUCH);
			
  },
  
  deleteMedia: function(){
  	if(!confirm("Do you really want to delete the image?")){
  		return;
  	}
  	var params = 	{
  									mediaId: this.mediaId
  								};
		this.ajaxLoader.setSecure(this.isSecure);
  	this.ajaxLoader.setDispatcher(this.dispatcher);		
  	this.ajaxLoader.updateContent("do_delete_media", "", params, this.callbacks.onDelete);
 		
	}
  
};




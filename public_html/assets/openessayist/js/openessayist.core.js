/**
 * 
 */
var openEssayist = function(){};

openEssayist.LOG_URL = null;
/**
 * 
 * @param msg
 * @returns {myClass.doSomething}
 */
openEssayist.log4j = function(action,data) {
    console.log(action);
	$.ajax({
		type : "POST",
		method: "post",
		url : openEssayist.LOG_URL, // URL and function to call
		data : {	
				"action": action,
				"data" : data
		}, 
		//contentType : "application/json; charset=utf-8",
		//processData : true,
		dataType : "json",
		success : function(msg, status) {
			// Set Response outcome
			console.log(msg);
			//location.reload();
		},
		error : function(xhr, msg, e) {
			// this should only fire if the ajax call did not happen or
			// there was an unhandled exception
			console.log(msg);
			//$('body').modalmanager('loading'); 
		}
	});		    
};

/**
 * 
 */
openEssayist.prototype = {
};

openEssayist.showSRTool = function()
{
	$.pnotify.defaults.history = false;
	optsQ1 = {
			title: 'Self-reflective tool',
			text: "At this point in time, do you think you have enough information to rewrite your essay?" + 
				"<p style='margin-top:6px;'><button id='reflect1-yes'>Yes</button><button id='reflect1-no'>No</button></p>",
			addclass: "stack-bottomright",
	        stack: $.pnotify.defaults.openessayist, 
	        hide: false,
	        closer_hover: false,
	        sticker_hover: false,
	        sticker: false, 
	        icon: 'icon-large  icon-comments',
	        before_open: function(pnotify) {
		        $(pnotify.text_container).attr('role', 'alert');
		        $(pnotify.closer).attr('role', 'button');
		        $(pnotify.closer).attr('tabindex', '0');
		        $(pnotify.closer).attr('title', 'Close this notice');
		        $(pnotify.closer).addClass('btn btn-mini');
		        
	        },
	        after_open: function(pnotify) {
	        	$("#reflect1-yes").click(function() {
	        		pnotify.pnotify_remove();
				});
	        	$("#reflect1-no").click(function() {
	        		$.pnotify(optsQ2);
	        		pnotify.pnotify_remove();
	        		//setTimeout(function(){
	        		//	pnotify.pnotify_remove();
	        		//},1000);
	        		
				});
	        }
		}; 
	optsQ2 = {
			title: 'Self-reflective tool',
			text: "Do you need to explore more content?" + 
				"<p style='margin-top:6px;'><button id='reflect2-yes'>Yes</button><button id='reflect2-no'>No</button></p>",
			addclass: "stack-bottomright",
	        stack: $.pnotify.defaults.openessayist, 
	        hide: false,
	        closer_hover: false,
	        sticker_hover: false,
	        sticker: false, 
	        icon: 'icon-large  icon-comments',
	        before_open: function(pnotify) {
		        $(pnotify.text_container).attr('role', 'alert');
		        $(pnotify.closer).attr('role', 'button');
		        $(pnotify.closer).attr('tabindex', '0');
		        $(pnotify.closer).attr('title', 'Close this notice');
		        $(pnotify.closer).addClass('btn btn-mini');
		        
	        },
	        after_open: function(pnotify) {
	        	$("#reflect2-yes").click(function() {
	        		pnotify.pnotify_remove();
				});
	        	$("#reflect2-no").click(function() {
	        		pnotify.pnotify_remove();
				});
	        }
		}; 
	setTimeout(function(){
		$.pnotify(optsQ1);
	},2000);	
};




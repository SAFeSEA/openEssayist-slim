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
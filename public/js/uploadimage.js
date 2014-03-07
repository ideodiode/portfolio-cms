/*
 *	Call sendData(data, '/admin/images') and handle alerts w/ response
 *	Call responseHandler(response, action) on success
 */
function uploadImage(data, filename, alertID, callback) {
	sendData(data, "/admin/images")
	.done(function(response){
		// Passes validation
		if(response.passes == true)
		{
			var message = "'"+response.filename+"' uploaded succesfully!";
			
			alertHandler(alertID, message, "success");
			callback(response);
			
		}
		// Image with that name already exists
		else if("url" in response)
		{
			var message = response.msg;
			
			alertHandler(alertID, message, "warning");
			callback(response);
		}
		// Other validation failed
		else
		{
			var message = response.msg;
			
			alertHandler(alertID, message, "error");
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown){
		//If reason failed = filesize > upload_max_filsize, change errorThrown
		if(jqXHR.responseText.indexOf("upload_max_filesize") != -1)
			errorThrown = "Failed to upload '"+filename+"'; exceeds 2Mb size limit";
		
		alertHandler(alertID, errorThrown, "error");
	});
};


/*
*	Ajax function
*/
function sendData(data, url) {
	data.append("_token", $('input[name="_token"]').val());

	return $.ajax({
		data: data,
		type: "POST",
		url: url,
		contentType: false,
		processData: false,
		success: function(response) {	
			return ([true, response]);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			var responseContainer = [false, errorThrown];
			return responseContainer;
		}
	});
};
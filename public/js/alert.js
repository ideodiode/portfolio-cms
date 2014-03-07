/*
 *	Adjust close function for closing alert
 */
 $(document).ready(function() {
	$('.alert .close').addClass("hidden");
	$('.alert .close').click( function(e) {
		$(this).parent().removeClass("in");
		$(this).addClass("hidden");
		$(this).siblings("ul").html("");
	});
});


/*
*	Alert handler
*/
function alertHandler(alertID, message, status){
	alertID = "#"+alertID;
	$(alertID).children("button").removeClass("hidden");
	if(status == "success"){
		if(!$(alertID).hasClass("in"))
			$(alertID).attr("class", "alert alert-success fade in");

		$(alertID+" ul").append("<li><strong>Success!</strong> "+message+"</li>");
	}
	else if(status == "warning"){
		if(!$(alertID).hasClass("in"))
			$(alertID).attr("class", "alert alert-warning fade in");			
		// .alert-warning takes precedence over alert-success
		else if($(alertID).hasClass("alert-success"))
			$(alertID).toggleClass("alert-success alert-warning");
				
		$(alertID+" ul").append("<li><strong>Warning! </strong> "+message+"</li>");
	}
	else if(status == "error"){
		if(!$(alertID).hasClass("in"))
			$(alertID).attr("class", "alert alert-danger fade in");
			// .alert-danger takes precedence over all
		else if(!$(alertID).hasClass("alert-danger"))
		{
			$(alertID).removeClass("alert-success alert-warning");
			$(alertID).addClass("alert-danger");
		}
		$(alertID+" ul").prepend("<li><strong>Error! </strong> "+message+"</li>");
	}
};
{{--
---- Work Layout - thumbnail child view
----
---- @params collection $works
---- @params int $width
----
--}}

<br/>
<!--
----	Thumbnail error alert
--->
<div id ="thumbsAlert"class="alert alert-warning fade" >
	<button type="button" class="close" aria-hidden="true">&times;</button>
	<ul>
	</ul>
</div>

<div class="row" style="position:relative;">
	<!-- Main Panel -->
	<div class="col-sm-6">
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h3 class="panel-title">Thumbnail</h3>
			</div>
			<div id="uploadPanel" class="panel-body slim-padding cropModeA">
				<p>Upload or choose an image above to crop into the thumbnail</p>
				<input type="file" id="tempUpload" accept="image/*">
				<button type="button" id="cancelCrop" class="btn btn-default pull-right hidden">Cancel</button>
			</div>
			<div class="panel-body slim-padding cropModeB text-center hidden">
				<button type="button" id="newCrop" class="btn btn-warning">Create New Thumbnails</button>
			</div>
		</div>
	</div>
	<!-- Info Panel -->
	<div id="infoPanel" class="col-sm-6 hidden cropModeA">
		<div class="panel panel-default" >
			<div class="panel-body slim-padding">
				<h4 class="panel-title">Standard Display (DPPX: 1)</h4>
				<div class="row">
					<div class="col-xs-6 col-md-5 ">
						<button id="uploadThumbnail" type="button" class="btn btn-warning btn-block" style="margin-top: 15px;">Crop<br/>Thumbnail</button>
					</div>
					<div class="col-xs-6 col-md-offset-1">
						<p>
							<ul class="list-unstyled">
								<li>Height: <span class="infoH">N/A</span></li>
								<li>Width: <span class="infoW">N/A</span></li>
								<li><abbr title="Dots Per Pixel unit" class="initialism">DDPX</abbr>: <span class="infoDPPX">N/A</span></li>
								<li>Quality: <span class="infoQ">N/A</span></li>
							</ul>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- TempImg & ThumbImg, cropModeA -->
<div class="row cropModeA">
	<div class="col-sm-6">
		<div class="text-center">
			<img id="tempImg" class="img-responsive hidden" style="display: inline;">
		</div>
	</div>
	<!-- Thumb -->
	<div class="col-sm-6 text-center">
		<div id="thumbImgContainer" class="hidden" >
			<img id="thumbImg">
		</div>
	</div>
</div>


<!-- StandardThumb & RetinaThumb, cropModeB -->
<div class="row cropModeB hidden">
	<div class="col-sm-6">
		<!-- Standard info -->
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
				<div id="standardPanel" class="panel panel-default" >
					<div class="panel-body">
					<h4 class="panel-title text-center">Standard Thumbnail</h4>
					<br/>
						<div class="row slim-padding">
							<div class="col-xs-6">
								<p class="text-right">
									Height: <span class="infoH">N/A</span>
								</p>
							</div>
							<div class="col-xs-6">
								<p>
									Width: <span class="infoW">N/A</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Standard Thumb -->
		<div class="text-center">
			<input type="hidden" name="thumbnail_filepath" value="{{{ $thumbnail_filepath }}}" >
			<div class="workThumbnail">
				<img id="standardThumb" class="thumbnail-img" src="{{{ asset($thumbnail_filepath) }}}">
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<!-- Retina info -->
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-2">
				<div id="retinaPanel" class="panel panel-default" >
					<div class="panel-body">
					<h4 class="panel-title text-center">Retina Thumbnail</h4>
					<br/>
						<div class="row slim-padding">
							<div class="col-xs-6">
								<p class="text-right">
									Height: <span class="infoH">N/A</span>
								</p>
							</div>
							<div class="col-xs-6">
								<p>
									Width: <span class="infoW">N/A</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Retina Thumb -->
		<div class="text-center">
			<input type="hidden" name="thumbnail2x_filepath" value="{{{ $thumbnail2x_filepath }}}" >
			<div class="workThumbnail">
				<img id="retinaThumb" class="thumbnail2x-img" src="{{{ asset($thumbnail2x_filepath) }}}">
			</div>
		</div>
	</div>
</div>
<br/>


<script>
$(document).ready(function() {
	/*	
	*	ImgAreaSelect Initialize/Update
	*/
	ias = $('#tempImg').imgAreaSelect({ 
		handles: true,
		instance: true,
		onSelectChange: previewThumb, 
		onSelectEnd: updatedThumbSelection
	});

	// Update imageAreaSelect when alert transitions finish
	$('.alert').on('webkitTransitionEnd transitionend', function(e) {
		ias.update();
	});
});


/*	
 *	Button img upload handlers
 */
 $("#cancelCrop").click(function(){
	cropMode("B");
});

$("#newCrop").click(function(){
	cropMode("A");
});
 
$("#tempUpload").on("change", function (){
	var file = this.files[0];
	var data = new FormData();
	data.append("file", file);
	data.append("action", "temp");
	
	uploadImage(data, file.value, "thumbsAlert", function(response){
		$("#tempImg").attr("src", response.url);
		$("#thumbImg").attr("src", response.url);
		$("#tempImg").removeClass("hidden");
		ias.update();
	});
});

$("#uploadThumbnail").click(function (){
	var url = $("#thumbImg").attr("src");
	var data = new FormData();
	data.append("url", url);
	data.append("action", "worksThumb");
	var selection = window.selection;
	
	// Scale selection to original image dimensions
	var scaleX = selection.img.naturalWidth / $("#tempImg").width();
	var scaleY = selection.img.naturalHeight / $("#tempImg").height();
	var x1 = Math.round(selection.x1*scaleX);
	var y2 = Math.round(selection.y1*scaleY);
	var w = Math.round(selection.w*scaleX);
	var h = Math.round(selection.h*scaleY);
	data.append("x1", x1);
	data.append("y1", y2);
	data.append("w", w);
	data.append("h", h);
	
	// Retina data
	if($("#infoPanel .panel-title").html() == "Standard Display (DPPX: 1)")
		var retina = false
	else
		var retina = true
	data.append("retina", retina);
	
	uploadImage(data, "thumbnail", "thumbsAlert", thumbUploadHandler);
});


/*
 *	Update upon changing cropModes
 */
function cropMode(mode){
	if(mode == "A"){
		// CropModeA visible only
		$(".cropModeA").removeClass("hidden");
		$(".cropModeB").addClass("hidden");
		
		// Turn on #imgContainer usage 
		$("#imgContainer img").addClass("highlight");
		$("#imgContainer").on("click", "img", function (e){
			url =  $(e.target).attr("full-src");
			$("#tempImg").attr("src", url);
			$("#thumbImg").attr("src", url);
			$("#tempImg").removeClass("hidden");
		});
		
		$("#infoPanel").addClass("hidden");
		$("#thumbImgContainer").addClass("hidden");
		
		ias.cancelSelection();
		ias.update();
	} else if(mode == "B"){
		// CropModeA visible only
		$(".cropModeA").addClass("hidden");
		$(".cropModeB").removeClass("hidden");
		
		// Show cancel button on main panel when shown
		$("#cancelCrop").removeClass("hidden");
		
		// Turn off #imgContainer usage 
		$("#imgContainer img").removeClass("highlight");
		$("#imgContainer").off("click", "img");
		
		$('#retinaThumb').imagesLoaded()
		  .done( function(instance){
			workThumbnail = $("#retinaPanel").parents(".col-sm-6").find(".workThumbnail");
			h = workThumbnail.height()+"px";
			w = workThumbnail.width()+"px";
			$("#retinaPanel").find(".infoH").html(h);
			$("#retinaPanel").find(".infoW").html(w);
		
			workThumbnail = $("#standardPanel").parents(".col-sm-6").find(".workThumbnail");
			h = workThumbnail.height()+"px";
			w = workThumbnail.width()+"px";
			$("#standardPanel").find(".infoH").html(h);
			$("#standardPanel").find(".infoW").html(w);
		});
		
		ias.update();
	}
}


/*	
 *	ImgAreaSelect compute selection
 */
function previewThumb(img, selection) { 
	var tempWidth = $("#tempImg").width();
	var scaleX = 300 / (selection.width || 1);
	$('#thumbImg').css({ 
		width:  Math.round(scaleX * tempWidth) + 'px',
		marginLeft: '-' + Math.round(selection.x1*scaleX) + 'px',
		marginTop: '-' + Math.round(selection.y1*scaleX) + 'px',
	});
	$('#thumbImg').parent("div").css({ 
		height: Math.round((selection.height || 1)*scaleX) + 'px'
	});
};


/*	
 *	Selection changed, update all
 */
function updatedThumbSelection( image, selection ) {
	// Update info panel
	
	$(".infoH", "#infoPanel").html($("#thumbImgContainer").height()+"px");
	$(".infoW", "#infoPanel").html($("#thumbImgContainer").width()+"px");
	var scaleX = (selection.width || 1) / $("#tempImg").width();
	var dppx = scaleX * image.naturalWidth / $("#thumbImgContainer").width();
	$(".infoDPPX", "#infoPanel").html(dppx.toFixed(2));
	
	// Determine quality of Standard/Retina
	if($("#infoPanel .panel-title").html() == "Standard Display (DPPX: 1)"){
		if (dppx < 0.5 || dppx > 1.5)
			$(".infoQ", "#infoPanel").html("Poor");
		else if (dppx < .75 || dppx > 1.25)
			$(".infoQ", "#infoPanel").html("Fair");
		else
			$(".infoQ", "#infoPanel").html("Great");
	} else {
		if (dppx < 1.5 || dppx > 2.5)
			$(".infoQ", "#infoPanel").html("Poor");
		else if (dppx < 1.75 || dppx > 2.25)
			$(".infoQ", "#infoPanel").html("Fair");
		else
			$(".infoQ", "#infoPanel").html("Great");
	}
	
	// Hide/Show info panel and thumbnail
	if(selection.width == 0)
	{
		$("#thumbImgContainer").addClass("hidden");
		$("#infoPanel").addClass("hidden");
	}
	else if($("#thumbImgContainer").hasClass("hidden"))
	{
		$("#thumbImgContainer").removeClass("hidden");
		$("#infoPanel").removeClass("hidden");
	}
	
	// Add data to window for thumb crop via ajax
	window.selection = {
		x1: selection.x1,
		y1: selection.y1,
		w: selection.width,
		h: selection.height,
		img: image,
	};
	
	// Update ias if broken from shifting elements
	ias.update();
};

function thumbUploadHandler(response){
	// Update based on infoPanel title
	if($("#infoPanel .panel-title").html() == "Standard Display (DPPX: 1)") {
		$("#infoPanel .panel-title").html("Retina Display (DPPX: 2)");
		
		// Add uploaded/cropped img to input & #standardThumb
		$("#standardThumb").attr("src", "");
		var d = new Date();
		$("#standardThumb").attr("src", response.url+"?"+d.getTime());
		
		var urlParts = response.url.split("/");
		urlParts.splice(0,3);
		var filepath = urlParts.join("/");
		$("[name='thumbnail_filepath']").val('/'+filepath);
		
		ias.cancelSelection()
	}
	else {
		$("#infoPanel .panel-title").html("Standard Display (DPPX: 1)");
		
		// Add uploaded/cropped img to input & #retinaThumb
		$("#retinaThumb").attr("src", ""); //Force imagesLoaded to fire
		var d = new Date();
		$("#retinaThumb").attr("src", response.url+"?"+d.getTime());
		
		var urlParts = response.url.split("/");
		urlParts.splice(0,3);
		var filepath = urlParts.join("/");
		$("[name='thumbnail2x_filepath']").val('/'+filepath);
		
		cropMode("B");
		
	}
	ias.update();
}

</script>
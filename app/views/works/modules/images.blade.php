{{--
---- Work module - images child view
----
---- @params collection $works
---- @params int $width
----
--}}



<!--
----	Image error alert
--->
<div id ="imagesAlert"class="alert alert-warning fade" >
	<button type="button" class="close" aria-hidden="true">&times;</button>
	<ul>
	</ul>
</div>

<!--
----	Portfolio image handling
--->
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h3 class="panel-title">Images</h3>
			</div>
			<div class="panel-body">
				<p>Upload files to attach to this work. Order shown is the order displayed publicly.
				</p>
				<input type="file" id="imgUpload" accept="image/*" multiple>
				
				<input type="hidden" name="img_order" value="{{ $img_order }}">
			</div>
		</div>
	</div>
</div>
<div id="imgContainer">
	@if(isSet($images))
		@foreach($images as $image)
			<span>
				<img src="{{ asset($image->path.'yThumbs/'.$image->name) }}" class='works-thumb-img thumbnail-img highlight' full-src='{{ asset($image->path.$image->name) }}' data-id='{{ $image->id }}'>
				<span class='glyphicon glyphicon-remove works-thumb-remove'></span>
				<span class='glyphicon glyphicon-remove-sign works-thumb-remove'></span>
			</span>
		@endforeach
	@endif
</div>



<script type="text/javascript">

$(document).ready(function() {
	if($("#imgContainer span").length == 0)
		$("[name='img_order']").val("");
		
	$("#imgContainer .glyphicon").on("click", worksThumbRemove);
});


/*	
 *	Button img upload handlers
 */
$("#imgUpload").change(function (){
	data = new FormData();
	data.append("action", "upload");
	for (var i = 0; i < this.files.length; i++) {
		data.append("file", this.files[i]);
		uploadImage(data, this.files[i].name, "imagesAlert", imgUploadHandler);
	}
});


/*	
 *	Handler after successful img upload
 */
function imgUploadHandler(response){
	// Insert /thumbsY/ into url
	var urlParts = response.url.split("/");
	var filename = urlParts.pop();
	urlParts.push("yThumbs");
	urlParts.push(filename);
	var url = urlParts.join("/");
	
	// Determine if img needs .highlight, append img to container
	var addClass = "";
	if (!$("#uploadPanel").hasClass("hidden"))
		addClass = " highlight";
	$("#imgContainer").append("<span><img src="+url+" class='works-thumb-img thumbnail-img" + addClass + "' full-src='"+response.url+"' data-id='"+response.id+"'><span class='glyphicon glyphicon-remove works-thumb-remove'></span><span class='glyphicon glyphicon-remove-sign works-thumb-remove'></span></span>");
	
	// Append on click to remove-glyph
	$("#imgContainer .glyphicon").on("click", worksThumbRemove);
	
	// Add img id to order input
	var order = $("[name='img_order']").val().split("/");
	order.push(response.id);
	$("[name='img_order']").val(order.join("/"));
	
	// Update imgareaselect when image loaded
	$('#imgContainer').imagesLoaded()
	  .done( function(instance){
		ias.update();
	});
}
	

/*
 *	On remove-glyph click, remove span & id from img_order
 */
function worksThumbRemove(e){
	e.stopImmediatePropagation();
	// Remove img's id from order
	var order = $("[name='img_order']").val().split("/");
	var id = $(e.target).siblings("img").attr("data-id");
	var idIndex = order.indexOf(id);
	
	order.splice(idIndex, 1);
	$("[name='img_order']").val(order.join("/"));
	
	$(e.target).parent().remove();
};

</script>
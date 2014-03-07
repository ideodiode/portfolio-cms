{{--
---- Work module - images child view
----
---- @params collection $works
---- @params int $width
----
--}}



<!--
----	Featured error alert
--->
<div id ="featuredAlert"class="alert alert-warning fade" >
	<button type="button" class="close" aria-hidden="true">&times;</button>
	<ul>
	</ul>
</div>

<!--
----	Featured image handling
--->
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default" >
			<div class="panel-heading">
				<h3 class="panel-title">Featured</h3>
			</div>
			<div class="panel-body">
				<div class="text-center">
					<div class="btn-group" data-toggle="buttons">
						@if($featured)
							<label id="featuredToggle" class="btn btn-primary icon-btn active">
								<span class='glyphicon glyphicon-star'></span>
								<input name="feature" type="checkbox" checked>
								<span class="btn-text">Currently Featured</span>
								<span class="invisible">Currently Featured</span>
							</label>
						@else
							<label id="featuredToggle" class="btn btn-primary icon-btn">
								<span class='glyphicon glyphicon-star-empty'></span>
								<input name="feature" type="checkbox">
								<span class="btn-text">Not Featured</span>
								<span class="invisible">Not Featured</span>
							</label>
						@endif
					</div>
				</div>
				<p>Featured carousel images should conform to 2000px X 1000px (for retina). All images will be resized in width and cropped in height to fit.</p>
				<input type="file" id="featuredUpload" accept="image/*" >
				<input type="hidden" name="featured_filepath" value="{{ $featured_filepath or '' }}">
			</div>
		</div>
	</div>
</div>
<div>
	<div id="featuredImgContainer" class="text-center" >
		@if(isset($featured_filepath))
			<img id="featuredImg" src="{{ asset($featured_filepath) }}" class="thumbnail-img">
		@else
			<img id="featuredImg" src="" class="thumbnail-img hidden">
		@endif
	</div>
</div>
<br/>


<script>
$("#featuredUpload").on("change", function (){
	var file = this.files[0];
	var data = new FormData();
	data.append("file", file);
	data.append("action", "workFeatured");
	
	uploadImage(data, file.value, "featuredAlert", function(response){
		$("#featuredImg").attr("src", response.url);
		$("#featuredImg").removeClass("hidden");
		
		var urlParts = response.url.split("/");
		urlParts.splice(0,3);
		var filepath = urlParts.join("/");
		$("[name='featured_filepath']").val("/"+filepath);
	});
});

$("#featuredToggle input").change(function(){
	$("#featuredToggle .glyphicon").toggleClass("glyphicon-star-empty glyphicon-star");
	if($(this).prop("checked")){
		
		$("#featuredToggle .btn-text").html("Currently Featured");
		$("#featuredToggle .invisible").html("Currently Featured");
	}
	else{
		$("#featuredToggle .btn-text").html("Not Featured");
		$("#featuredToggle .invisible").html("Not Featured");
	}
});
</script>
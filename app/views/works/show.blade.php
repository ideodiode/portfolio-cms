@extends('layoutPublic')

@section('content')
<div class="container">
	<div class="row">
		<img id="displayImg" src="{{ asset($images->first()->path.$images->first()->name) }}"class="img-responsive thumbnail-img">
	</div>
	
	<div class="row">
		<div id="imgThumbContainer">
		@if($images->count()>1)
			@foreach($images as $image)
				<img src="{{ asset($image->path.'yThumbs/'.$image->name) }}" full-src="{{ asset($image->path.$image->name) }}" class="thumbnail-img highlight">
			@endforeach
		@endif
		</div>
	</div>
	
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
				<h1>{{ $work->title }}</h1>
				<span><strong>Posted: {{ date("M j, Y",strtotime($work->created_at)) }} at {{ date("g:ha e",strtotime($work->created_at)) }}</strong></span>

				<p>{{ $work->lg_description }}</p>

				<span>Tags: 
				@foreach($tags as $tag)
					<span class="label label-primary">{{ $tag->name }}</span>
				@endforeach
				</span>
			</div>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#imgThumbContainer img").first().addClass("thumb-display");
	
		$("#imgThumbContainer img").click(function(){
			$("#imgThumbContainer img").removeClass("thumb-display");
			$(this).addClass("thumb-display");
			var src = $(this).attr("full-src");
			$("#displayImg").attr("src", src);
		});
	});
	
	$(document).keydown(function(e){
		var handler;
		// Left keypress
		if(e.which == "37" || e.which == "39"){
			if(e.which == "37"){
				handler = $("#imgThumbContainer .thumb-display").prev();
				if (!handler.length)
					handler = $("#imgThumbContainer img").last();
			}
			// Right keypress
			else if (e.which == "39"){
				handler = $("#imgThumbContainer .thumb-display").next();
				if (!handler.length)
					handler = $("#imgThumbContainer img").first();
			}
			
			$("#imgThumbContainer img").removeClass("thumb-display");
			handler.addClass("thumb-display");
			var src = handler.attr("full-src");
			$("#displayImg").attr("src", src);
		}
	});
</script>

@stop
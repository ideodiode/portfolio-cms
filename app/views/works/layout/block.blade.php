{{--
---- Work Layout - child view
----
---- @params collection $works
---- @params int $width
----
--}}


@if ($works->isEmpty())
	<p>There are no works! :(</p>
@else
	<div id="gallery" style="position:relative">
		@foreach($works as $work)
			<div class="workContainer" style="display:inline-block; max-width:{{ $width }}px;">
				@if($work->featured)
					<span class='glyphicon glyphicon-star works-favorite-img'></span>
					<span class='glyphicon glyphicon-star-empty works-favorite-img'></span>
				@endif
				<img src={{ asset($work->thumbnail_filepath) }} alt={{ $work->title.' thumbnail' }} class="thumbnail-img" style="max-width:{{ $width }}px;">
				<div class="workInfo text-center">
					<h4>{{ $work->title }}</h4>
					<p>
						<a href={{ action('WorksController@edit', $work->id) }} class="btn btn-warning" role="button">Edit</a>
						<button data-toggle="modal" data-target="#deleteModal" work-id="{{{ $work->id }}}" class="btn btn-danger">Delete</button>
					</p>
				</div>
			</div>
		@endforeach
	</div>
@endif


<script>
$('#gallery').imagesLoaded( function(){
	width = $('#gallery div').width();
	offset = 5;
	$('#gallery .workContainer').wookmark({
		autoResize: true,
		container: $('#gallery'),
		itemWidth: width,
		fillEmptySpace: true,
		offset: offset
	});
});
</script>
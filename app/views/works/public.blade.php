@extends('layoutPublic')

@section('head')
	<script type="text/javascript" src={{ asset('js/jquery.wookmark.js') }}></script>
	<script type="text/javascript" src={{ asset('js/imagesloaded.pkgd.min.js') }}></script>
@stop

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<div class="well well-sm">
					<p>Categories</p>
				
			</div>
		</div>
	</div>

	<div class="row slim-padding">
		<div id="gallery" style="position:relative">
			@foreach($works as $work)
				<a href="{{ action('WorksController@show', $work->id) }}">
					<div class="workContainer highlight">
						<img src={{ asset($work->thumbnail_filepath) }} alt={{ $work->title.' thumbnail' }}>
						<div class="workInfo text-center">
							<h4>{{ $work->title }}</h4>
							<p>{{ $work->sm_description }}</p>
						</div>
					</div>
				</a>
			@endforeach
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#gallery').imagesLoaded( function(){
			$('#gallery .workContainer').wookmark({
				autoResize: true,
				container: $('#gallery'),
				itemWidth: 200,
				fillEmptySpace: true,
				offset: 10
			});
		});
	});
</script>
@stop
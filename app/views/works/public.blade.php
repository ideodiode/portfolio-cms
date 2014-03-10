@extends('layoutPublic')

@section('head')
	<script type="text/javascript" src={{ asset('js/jquery.wookmark.js') }}></script>
	<script type="text/javascript" src={{ asset('js/imagesloaded.pkgd.min.js') }}></script>
@stop

@section('content')
<div class="container-fluid">
	<div id="categoryContainer" class="row text-center">
		<span class="well well-sm text-center">
			<a href="{{ action('WorksController@publicIndex') }}" >
				<span class="category {{ ($choice == 'All Works') ? 'label-success' : 'label-default' }}" >All Works</span>
			</a>
			@foreach($categories as $category)
				<a href="{{ action('WorksController@publicIndex', array('category'=>$category->name)) }}" >
					<span class="category {{ ($choice == $category->name) ? 'label-success' : 'label-default' }}" >{{ $category->name }}</span>
				</a>
			@endforeach
		</span>
	</div>
	
	@if ($works->isEmpty())
		<div class="text-center">
			<p>There are no works under '{{ $choice }}' :(</p>
		</div>
	@else
		<div class="row slim-padding">
			<div id="gallery" style="position:relative">
				@foreach($works as $work)
					<a href="{{ action('WorksController@show', $work->id) }}">
						<div class="workContainer highlight">
							<img src={{ asset($work->thumbnail_filepath) }} alt={{ $work->title.' thumbnail' }}>
							<div class="workInfo text-center">
								<h4>{{ $work->title }}</h4>
							</div>
						</div>
					</a>
				@endforeach
			</div>
		</div>
	@endif
	
</div>

<script type="text/javascript">
		$('#gallery').imagesLoaded( function(){
			width = 200;
			offset = 10;
			$('#gallery .workContainer').wookmark({
				autoResize: true,
				container: $('#gallery'),
				itemWidth: width,
				fillEmptySpace: true,
				offset: offset
			});
		});

</script>
@stop
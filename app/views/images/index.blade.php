@extends('layoutAdmin')

@section('head')
	<script type="text/javascript" src={{ asset('js/jquery.wookmark.js') }}></script>
	<script type="text/javascript" src={{ asset('js/imagesloaded.pkgd.min.js') }}></script>
@stop

@section('content')

	<div class="page-header">
		<h1>Images Index</h1>
	</div>
	
	@if ($images->isEmpty())
		<p>There are no images! :(</p>
	@else
		<div id="gallery" style="position:relative" >
			@foreach($images as $image)
				<div>
					<a href={{ action('ImagesController@edit', $image->id) }}  class="invisible">
						<img width=200 src={{ asset($image->path.'/xThumbs/'.$image->name) }} alt={{ $image->name }} class="thumbnail-img highlight">
					</a>
				</div>
			@endforeach
		</div>
	@endif
	
	<script type="text/javascript">
		$('#gallery').imagesLoaded( function(){
			$('#gallery div').wookmark({
				autoResize: true,
				container: $('#gallery'),
				itemWidth: 200,
				fillEmptySpace: true,
				offset: 5
			});
			$('#gallery a').toggleClass('invisible');
		}); 
	</script>

@stop
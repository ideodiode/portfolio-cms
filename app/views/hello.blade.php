@extends('layoutPublic')

@section('content')

	<div id="carousel">
		<div id="carousel-featured" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">	
				<li data-target="#carousel-featured" data-slide-to="0" class="active"></li>
				@for ($i = 1; $i < $features->count(); $i++)
					<li data-target="#carousel-featured" data-slide-to="{{ $i }}"></li>
				@endfor
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<div class="item active">
					<img src="{{{ $features->shift()->featured_filepath }}}" alt="...">
				</div>
				@foreach($features as $feature)
				<div class="item">
					<img src="{{{ $feature->featured_filepath }}}" alt="...">
				</div>
				@endforeach
			</div>
		</div>
	</div>
  <div class="container-fluid">
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
			<div class="text-center">
				<h1>You have arrived.</h1>
				<p><em>Welcome to ideodiode.com, currently in development (using Laravel 4) and to be released soon.<em></p>
				<p>Have a look at the cms admin console in progress</p>
				<a type="button" class="btn btn-info" href={{ url('admin') }}>Admin Console</a>
			</div>
		</div>
	</div>
</div>
@stop
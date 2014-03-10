@extends('layoutPublic')

@section('content')

@if(isset($features))
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
@endif

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
			<div class="text-center">
				<h1>You have arrived.</h1>
				<p><em>Welcome to ideodiode.com, currently in development and to be released soon.</em></p>
				<p>This site is currently being used as a demo. I do not claim any images that appear on this site. As the site is still under construction, certain functionality may not be implemented and not all platforms have been tested. All public facing pages are due to be reworked with new css for better accessibility and responsiveness.</p>
				<p>ideodiode.com was developed in PHP using the laravel 4 framework. The admin console (found via the button on the footer below), is designed as a CMS for project portfolios.</p>
			</div>
		</div>
	</div>
</div>
@stop
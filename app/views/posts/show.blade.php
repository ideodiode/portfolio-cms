@extends('layoutPublic')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			<div class="panel panel-default">
				<div class="panel-body">
					<h1>{{ $post->title }}:</h1>
					<span><strong>Posted: {{ date("M j, Y",strtotime($post->created_at)) }} at {{ date("g:ha e",strtotime($post->created_at)) }}</strong></span>

					<p><em>{{ $post->intro }}</em></p>

					<p>{{ $post->body }}</p>

					<span>Tags: 
					@foreach($tags as $tag)
						#{{ $tag->name }}
					@endforeach
					</span>
				</div>
			</div>
		</div>
		
		<div class="col-md-3 hidden-xs hidden-sm">
			<div class="panel panel-default">
				<div class="panel-body">
					<p>Sidebar</p>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
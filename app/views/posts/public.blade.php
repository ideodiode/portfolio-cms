@extends('layoutPublic')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			@foreach($posts as $post)
				<div class="panel panel-default">
					<div class="panel-body">
						<h1>{{ $post->title }}</h1>
						<span><strong>Posted: {{ date("M j, Y",strtotime($post->created_at)) }} at {{ date("g:ha e",strtotime($post->created_at)) }}</strong></span>
						<p><em>{{ $post->intro }}</em></p>
						<a href={{ action('PostsController@show', $post->id) }}>Read the rest of this post <span class="glyphicon glyphicon-chevron-right"></span></a>
						<hr/>
						<span>Tags: 
						@if(!$post->tags->isEmpty())
							@foreach($post->tags as $tag)
								#{{ $tag->name }}
							@endforeach
						@else
							N/A
						@endif
						</span>
					</div>
				</div>
			@endforeach
		</div>
		<div class="col-sm-4 col-md-3 hidden-xs hidden-sm">
			<div class="panel panel-default">
				<div class="panel-body">
					<p>Sidebar</p>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
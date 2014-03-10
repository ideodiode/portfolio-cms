@extends('layoutPublic')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-9">
			@if ($posts->isEmpty())
				<div class="text-center">
					<p>There are no posts under '{{ $choice }}' :(</p>
				</div>
			@else
				@foreach($posts as $post)
					<div class="panel panel-default">
						<div class="panel-body">
							<h1>{{ $post->title }}</h1>
							<span><strong>Posted: {{ date("M j, Y",strtotime($post->created_at)) }} at {{ date("g:ha e",strtotime($post->created_at)) }}</strong></span>
							<p><em>{{ $post->intro }}</em></p>
							<a href={{ action('PostsController@show', $post->id) }}>Read the rest of this post <span class="glyphicon glyphicon-chevron-right"></span></a>
							<hr/>
							<span><span class="glyphicon glyphicon-tags"></span> Tags: 
							@if(!$post->tags->isEmpty())
								@foreach($post->tags as $tag)
									<span class="label label-primary">{{ $tag->name }}</span>
								@endforeach
							@else
								N/A
							@endif
							</span>
						</div>
					</div>
				@endforeach
			@endif
			
			@if(isset($choice))
				{{ $posts->appends(array('category' => $choice ))->links() }}
			@else
				{{ $posts->links() }}
			@endif
		</div>
		<div class="col-sm-4 col-md-3 hidden-xs hidden-sm">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>Top Tags</h4>
						<ul class="list-unstyled">
						@foreach($categories as $category)
							<li>
								<a href="{{ action('PostsController@publicIndex', array('category'=>$category->name)) }}" >
									<span class="category {{ ($choice == $category->name) ? 'label-success' : 'label-default' }}" >{{ $category->name }}</span>
								</a>
							</li>
						@endforeach
						</ul>
						{{ Form::open(array( 'action'=>'PostsController@publicIndex', 'method'=>'GET')) }}
						<div class="input-group input-group-sm has-feedback">
							<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
							<span class="glyphicon glyphicon-search form-control-feedback"></span>
							<label for="category" class="sr-only">Search tags..</label>
							<input type="text" class="form-control" name="category" placeholder="Search Tags..">
							
						</div>
						
						{{ Form::close() }}
					
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>Past Posts</h4>
						
					@foreach($years as $year)
						<h5>{{ key($years) }}</h5>
						
						<ul class="list-unstyled">
						@foreach($year as $month)
							<li>
								<a href="{{ action('PostsController@publicIndex', array('month'=>$month['number'], 'year'=>key($years))) }}"><span class="category label-primary">{{ $month['name'] }} <span class="category-count">{{ $month['count'] }}</span></span></a>
							</li>
						@endforeach
						
					</ul>
					@endforeach
					
				</div>
			</div>
		</div>
	</div>
</div>
@stop
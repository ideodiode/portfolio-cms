@extends('layoutAdmin')

@section('content')

	<div class="page-header">
		<h1>Posts Index</h1>
	</div>
	<div class="row">
		<div class="col-xs-6 col-sm-8 slim-padding" >
			<a type="button" href="{{ action('PostsController@create') }}" class="btn btn-primary icon-btn">
				<span class="glyphicon glyphicon-plus"></span>
				<span class="hidden-xs btn-text">New Post</span>
				<span class="hidden-xs invisible">New Post</span>
			</a>
			<a type="button" href="{{ action('PostsController@getSettings') }}" class="btn btn-primary icon-btn">
				<span class="glyphicon glyphicon-cog"></span>
				<span class="hidden-xs btn-text">Settings</span>
				<span class="hidden-xs invisible">Settings</span>
			</a>
		</div>
	</div>
	
	<hr/>
	
	@if ($posts->isEmpty())
		<p>There are no posts! :(</p>
	@else
	
	{{ $posts->links() }}
	
	<table class="table table-striped" style="word-wrap: break-word; table-layout: fixed;">
		<thead>
			<tr>		
				<th class="col-xs-5">Title</th>
				<th class="col-xs-5">Tags</th>
				<th class="col-xs-2">Actions</th>		
			</tr>
		</thead>
		<tbody>
		
			@foreach($posts as $post)
			<tr>
				<td>{{ $post->title }}</td>
				<td >
					@foreach($post->tags as $tag) 
						<span class="label label-primary">{{ trim($tag->name) }}</span>
					@endforeach</td>
				<td>
					<div class="row">
						<div class="col-md-6 slim-padding text-center">
							<a href="{{ action('PostsController@edit', $post->id) }}" class="btn btn-warning btn-block btn-sm">Edit</a>
						</div>
						<div class="col-md-6 slim-padding text-center">
							<button data-toggle="modal" data-target="#deleteModal" post-id="{{{ $post->id }}}" class="btn btn-danger btn-block btn-sm">Delete</button>
						</div>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	{{ $posts->links() }}
	
	<!-- Modal for Delete Confirmation-->
	<div class="modal fade bs-modal-sm" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="deleteModalLabel">Are you sure?</h4>
				</div>
				<div class="modal-body">
					{{ Form::open(array('action'=>array('PostsController@destroy', 0), 'id'=>'deleteForm','method'=>'DELETE')) }}
						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6">
								<button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancel</button>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6">
								<button type="submit" class="btn btn-danger btn-block">Delete</button>
							</div>
						</div>
					{{ Form::close() }}
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script>
	$("button[post-id]").click(function() {
		
		postID = $(this).attr("post-id");
		url = $("#deleteForm").attr("action");
		
		<!-- regx to change url for chosen resource in delete confirmation modal -->
		url = url.replace(/\d*$/, postID);
		$("#deleteForm").attr("action", url);
	});
	</script>
@endif


@stop
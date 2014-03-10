@extends('layoutAdmin')

@section('head')
	<script src="{{ asset('js/summernote.min.js') }}"></script>
	<link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
	
	<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/uploadimage.js') }}"></script>
@stop

@section('content')

	<div class="page-header">
		<h1>Create Post</h1>
	</div>
	
	<form id="postForm" action="{{ action('PostsController@store')  }}" method="post" enctype="multipart/form-data" role="form">
		{{ Form::token() }}
		
		{{ $errors->first('title', '<div class="alert alert-danger">:message</div>') }}
		
		<div id="titleAlert" class="alert alert-warning fade" >
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<ul>
			</ul>
		</div>
		
		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Title:</h3>
				</div>
				<label for="title" class="sr-only">Title:</label>
				<input type="text" class="form-control" name="title" value="{{{ Input::old('title') }}}" />
			</div>
		</div>
		
		<div id="summernote1Alert" class="alert alert-warning fade" >
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<ul>
			</ul>
		</div>
		
		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Intro:</h3>
				</div>
				<label for="intro" class="sr-only">Introduction:</label>
				<textarea class="input-block-level summernote" id="summernote1" name="intro" rows="10">{{{ Input::old('intro', null) }}}</textarea>
			</div>
		</div>
		
		<div id="summernote2Alert" class="alert alert-warning fade" >
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<ul>
			</ul>
		</div>
		
		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Body:</h3>
				</div>
				<label for="body" class="sr-only">Body:</label>
				<textarea class="input-block-level summernote" id="summernote2" name="body" rows="10">{{{ Input::old('body') }}}</textarea>
			</div>
		</div>
		
		@include('tags.panel', array('tags'=>$tags))
		
		<div class="row">				
			<div class="col-xs-5 col-xs-offset-1 col-sm-3 col-sm-offset-2">
				<a href="{{ action('PostsController@index') }}" class="btn btn-default btn-block">Cancel</a>
			</div>
			<div class="col-xs-5 col-sm-3 col-sm-offset-2">
				<button type="submit" value="Store" class="btn btn-primary btn-block">Create Post</button>
			</div>
		</div>
	</form>
 
<script>

/*	
*	Initiate Summernote w/ new image upload function
*/
$(document).ready(function() {
	$('.summernote').summernote({
		
		onImageUpload: function(files, editor, welEditable) {
			alertID = welEditable.parentsUntil( ".form-group",".panel" ).children(".summernote").attr("id")+"Alert";
			data = new FormData();
			data.append("file", files[0]);
			data.append("action", "upload");
			
			uploadImage(data, files[0].name, alertID, function(response){
				editor.insertImage(welEditable, response.url);
			});
		}
	});
	
	/*
	 *	Form validation
	 */
	$("form").submit(function(event){
		var hash;
		// Validate title, remove whitespace in case only spaces
		val = $("input[name='title']").val();
		val = val.replace(/ /g,'');
		if(val.length <= 0){
			message = "Title is missing";
			hash = 'titleAlert';
			
			alertHandler("titleAlert", message, "error");
			$("input[name='title']").parents(".form-group").addClass('has-error');
			event.preventDefault();
		}
		if(hash)
			window.location.hash = hash;
	});
	$("input[name='title']").change(function(){
		val = $("input[name='title']").val();
		val = val.replace(/ /g,'');
		if(val.length <= 0){
			$("input[name='title']").parents(".form-group").removeClass('has-success').addClass('has-danger');
		}
		else{
			$("input[name='title']").parents(".form-group").addClass('has-success');
		}
	});
});

</script>
@stop
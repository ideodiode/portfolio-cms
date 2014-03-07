@extends('layoutAdmin')

@section('head')
	<script src="{{ asset('js/summernote.min.js') }}"></script>
	<link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="{{ asset('css/imgareaselect-default.css') }}" />
	<script type="text/javascript" src="{{ asset('js/jquery.imgareaselect.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
	
	<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/uploadimage.js') }}"></script>
@stop

@section('content')
	<div class="page-header">
		<h1>Create Work</h1>
	</div>
	
	<form id="postForm" action="{{ action('WorksController@store')  }}" method="post" role="form">
		{{ Form::token() }}
		
		{{ $errors->first('title', '<div class="alert alert-danger">:message</div>') }}
		<!--
		----	Image error alert
		--->
		<div id="textAlert" class="alert alert-warning fade" >
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<ul>
			</ul>
		</div>
		
		<!--
		----	Text Inputs
		--->
		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Title</h3>
				</div>
				<label for="title" class="sr-only">Title:</label>
				<input type="text" class="form-control" name="title" value="{{{ Input::old('title') }}}" />
			</div>
		</div>
		
		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Short Description</h3>
				</div>
				<label for="intro" class="sr-only">Short Description:</label>
				<textarea class="input-block-level summernote" id="summernote1" name="sm_description" >{{{ Input::old('sm_description') }}}</textarea>
			</div>
		</div>
		
		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Full Description</h3>
				</div>
				<label for="body" class="sr-only">Full Description:</label>
				<textarea class="input-block-level summernote" id="summernote2" name="lg_description">{{{ Form::getValueAttribute('lg_description', null) }}}</textarea>
			</div>
		</div>
		
		<!--
		----	Images module
		--->
		@include('works.modules.images', array('img_order'=> Form::getValueAttribute('img_order', null)))
		
		<!--
		----	Thumbnail module
		--->
		@include('works.modules.thumbnail', array('thumbnail_filepath'=>Form::getValueAttribute('thumbnail_filepath', null),'thumbnail2x_filepath'=>Form::getValueAttribute('thumbnail2x_filepath', null)))
		
		<!--
		----	Featured module
		--->
		@include('works.modules.featured', array('featured_filepath'=> Form::getValueAttribute('featured_filepath', null), 'featured'=> Form::getValueAttribute('featured', false)))
		
		<!--
		----	Tags panel
		--->
		@include('tags.panel', array('tags'=>$tags))
		
		<!--
		----	Form buttons
		--->
		<div class="row">				
			<div class="col-xs-5 col-xs-offset-1 col-sm-3 col-sm-offset-2">
				<a href="{{ action('WorksController@index') }}" class="btn btn-default btn-block">Cancel</a>
			</div>
			<div class="col-xs-5 col-sm-3 col-sm-offset-2">
				<button type="submit" value="Store" class="btn btn-primary btn-block">Create Work</button>
			</div>
		</div>
		
	</form>
	
	<script type="text/javascript">
	
	$(document).ready(function() {
		/*	
		 *	Initialize Summernote w/ new image upload function
		 */
		$('.summernote').summernote({
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']],
				['insert', ['link']],
				['view', ['fullscreen', 'codeview']],
				['help', ['help']]
			],
		});
		
		cropMode("A");
			
		/*
		 *	Form validation
		 */
		$("form").submit(function(event){
			var hash;
			// Validate featured_filepath (Standard)
			if($("#featuredToggle input").prop("checked")){
				val = $("input[name='featured_filepath']").val();
				if(val.length <= 0){
					message = "Featured image required if work is featured";
					hash = "featuredAlert";
					
					alertHandler("featuredAlert", message, "error")
					event.preventDefault();
				}
			}
			// Validate thumbnail_filepath (Standard)
			val = $("input[name='thumbnail_filepath']").val();
			if(val.length <= 0){
				message = "Standard thumbnail is missing";
				hash = "thumbnailAlert";
				
				alertHandler("thumbnailAlert", message, "error")
				event.preventDefault();
			}
			// Validate thumbnail2x_filepath (Retina)
			val = $("input[name='thumbnail2x_filepath']").val();
			if(val.length <= 0){
				message = "Retina thumbnail is missing";				
				hash = "thumbnailAlert";
				
				alertHandler("thumbnailAlert", message, "error");
				event.preventDefault();
			}
			// Validate img_order
			val = $("input[name='img_order']").val();
			if(val.length <= 0){
				message = "At least one image required";				
				hash = "imagesAlert";
				
				alertHandler("imagesAlert", message, "error");
				event.preventDefault();
			}
			// Validate title, remove whitespace in case only spaces
			val = $("input[name='title']").val();
			val = val.replace(/ /g,'');
			if(val.length <= 0){
				message = "Title is missing";
				hash = 'textAlert';
				
				alertHandler("textAlert", message, "error");
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
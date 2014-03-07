@extends('layoutAdmin')

@section('head')
	<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/uploadimage.js') }}"></script>
@stop

@section('content')

	<div class="page-header">
		<h1>Works Settings</h1>
	</div>
	
	<form action="{{ action('WorksController@setSettings')  }}" method="post" role="form">
		{{ Form::token() }}
		
		<h3>Choose categories for public views</h3>
		<!--
		----	Tags panel
		--->
		@include('tags.panel', array('tags'=>$tags,'relatedTags'=>$relatedTags,'relatedTagsString'=>$relatedTagsString))
	
		<div class="row">				
			<div class="col-xs-5 col-xs-offset-1 col-sm-3 col-sm-offset-2">
				<a href="{{ action('WorksController@index') }}" class="btn btn-default btn-block">Cancel</a>
			</div>
			<div class="col-xs-5 col-sm-3 col-sm-offset-2">
				<button type="submit" value="Store" class="btn btn-primary btn-block">Update Settings</button>
			</div>
		</div>
		
	</form>
		
@stop
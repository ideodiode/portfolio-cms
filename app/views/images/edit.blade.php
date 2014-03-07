@extends('layoutAdmin')

@section('content')

	<div class="page-header">
		<h1>Edit Image <small>{{ $image->name }}</small></h1>
	</div>

	<img src={{ asset($image->path.$image->name) }} alt={{ $image->name }} class="img-responsive">
	
@stop
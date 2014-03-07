@extends('layoutAdmin')

@section('head')
	<script type="text/javascript" src={{ asset('js/jquery.wookmark.js') }}></script>
	<script type="text/javascript" src={{ asset('js/imagesloaded.pkgd.min.js') }}></script>
@stop

@section('content')

	<div class="page-header">
		<h1>Works Index</h1>
	</div>
	<div class="row">
		<div class="col-xs-6 col-sm-8 slim-padding" >
			<a type="button" href="{{ action('WorksController@create') }}" class="btn btn-primary icon-btn">
				<span class="glyphicon glyphicon-plus"></span>
				<span class="hidden-xs btn-text">New Work</span>
				<span class="hidden-xs invisible">New Work</span>
			</a>
			<a type="button" href="" class="btn btn-primary icon-btn">
				<span class="glyphicon glyphicon-star"></span>
				<span class="hidden-xs btn-text">Featured</span>
				<span class="hidden-xs invisible">Featured</span>
			</a>
			<a type="button" href="{{ action('WorksController@getSettings') }}" class="btn btn-primary icon-btn">
				<span class="glyphicon glyphicon-cog"></span>
				<span class="hidden-xs btn-text">Settings</span>
				<span class="hidden-xs invisible">Settings</span>
			</a>
		</div>
		<div class="col-xs-6 col-sm-4 slim-padding">
			<div class="btn-group btn-group pull-right" data-toggle="buttons">
				<label class="btn btn-primary btn-group icon-btn {{{ $listLayout[0] or '' }}}">
					<input type="radio" name="layout" id="listLayout" {{ $listLayout[1] or '' }}>
					<span class="glyphicon glyphicon-th-list"></span>
				</label>
				<label class="btn btn-primary btn-group icon-btn {{{ $smBlockLayout[0] or '' }}}">
					<input type="radio" name="layout" id="smBlockLayout" {{ $smBlockLayout[1] or '' }}>
					<span class="glyphicon glyphicon-th"></span>
				</label>
				<label class="btn btn-primary btn-group icon-btn {{{ $lgBlockLayout[0] or '' }}}">
					<input type="radio" name="layout" id="lgBlockLayout" {{ $lgBlockLayout[1] or '' }}>
					<span class="glyphicon glyphicon-th-large"></span>
				</label>
			</div>
		</div>
	</div>
	
	<hr/>
	
	
	<div id="layout" class="invisible">
		{{ $html }}
	</div>

	<!-- Modal for Delete Confirmation-->
	<div class="modal fade bs-modal-sm" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="deleteModalLabel">Are you sure?</h4>
				</div>
				<div class="modal-body">
					{{ Form::open(array('action'=>array('WorksController@destroy', 0), 'id'=>'deleteForm','method'=>'DELETE')) }}
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
	
	<script type="text/javascript">
	$(document).ready(function() {
		id = $("input[name=layout]:checked").attr("id");
		if (id != "listLayout"){
			window.layout = "block";
			
			$('#gallery').imagesLoaded( function(){
				updateWookmark(id);
			});
		}
		else
			window.layout = "list";
		$("#layout").removeClass("invisible");
	});
	
	
	/*
	*	Update layout when layout radio toggled
	*/
	$(":input[type=radio]").change(function () {
		id = $('input[name=layout]:checked').attr('id');
		
		if (id != 'listLayout'){
			// If toggle was previously on list, get block layout
			if( window.layout == "list"){
				data = {layout_type: id};
				
				$("#layout").addClass("invisible");
				sendData(data, "GET", "/admin/works")
				.done(function(response){
					$("#layout").empty().html(response.html);
					$('#gallery').imagesLoaded( function(){
						updateWookmark(id);
						window.layout = "block";
					});
				});
			}
			else{
				updateWookmark(id);
				window.layout = "block";
			}
		}
		else {
			// Layout must have been block previously
			data = {layout_type: id};
			sendData(data, "GET", "/admin/works")
			.done(function(response){
				$("#layout").empty().html(response.html);
			});
			window.layout = "list";
		}
	});
	
	
	/*
	*	Update wookmark using :input id
	*/
	function updateWookmark(id){
		if (id == 'lgBlockLayout'){
			width = 300;
			offset = 10;
		}
		else {
			width = 150;
			offset = 5;
		}
		// If width isn't set, set it and wait for animation
		if (window.layout != "list" && $('#gallery img').first().css("max-width") != width+"px"){
			// Change session variable
			data = {session: id};
			sendData(data, "GET", "/admin/works");			
			
			$('#gallery img').css( "max-width", width+"px");
			$('#gallery .workContainer').css( "max-width", width+"px");
			$('#gallery .workContainer').on('webkitTransitionEnd transitionend otransitionend', function(e) {
				$('#gallery .workContainer').wookmark({
					autoResize: true,
					container: $('#gallery'),
					itemWidth: width,
					fillEmptySpace: true,
					offset: offset
				});
			});
		}
		else{	
			$('#gallery .workContainer').wookmark({
				autoResize: true,
				container: $('#gallery'),
				itemWidth: width,
				fillEmptySpace: true,
				offset: offset
			});
		}
		$("#layout").removeClass("invisible");
	};
	
	
	/*
	*	Change id in modal on delete btn click
	*/
	$("button[work-id]").click(function() {
		
		workID = $(this).attr("work-id");
		url = $("#deleteForm").attr("action");
		
		<!-- regx to change url for chosen resource in delete confirmation modal -->
		url = url.replace(/\d*$/, workID);
		$("#deleteForm").attr("action", url);
	});
	
	
	/*
	*	Ajax function
	*/
	function sendData(data, type, url) {
		if(type=="POST")
			data.append("_token", $('input[name="_token"]').val());
		
		return $.ajax({
			data: data,
			type: type,
			url: url,
			contentType: false,
			processData: (type!="POST"),
			success: function(response) {	
				return ([true, response]);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var responseContainer = [false, errorThrown];
				return responseContainer;
			}
		});
	};
	</script>
	
@stop
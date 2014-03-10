@extends('layoutAdmin')

@section('head')
	<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
@stop

@section('content')

	<div class="page-header">
		<h1>Tags Index</h1>
	</div>

		<div id ="tagsAlert" class="alert alert-warning fade" >
			<button type="button" class="close" aria-hidden="true">&times;</button>
			<ul>
			</ul>
		</div>
	
	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Tags:  
						<span class="sr-only">Legend:</span>
						<span class="label label-success">Selected</span>
						<span class="label label-default">Available</span>
						<span class="label label-info">New</span>
					</h3>
				</div>
				<div class="panel-body tag-display">
					@foreach($tags as $tag)
						<span class="tag label-default" tag-id={{ $tag->id }} ><span class="tagName">{{ $tag->name }}</span>
							<span class="tag-count">{{ $tag->count }}</span></span>
					@endforeach
				</div>
				<div class="panel-footer">
					<div class="input-group">
						<label for="createTag" class="sr-only">Create a New Tag:</label>
						<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
						<input type="text" class="form-control" name="createTag" placeholder="MyNewTag">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="submitTag" name="createTag">Create New Tag</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		
		<!--
		----	Edit & Delete Panel
		--->
		<div class="col-md-3 clearfix">
			{{ Form::open(array('action'=>array('TagsController@update', 0), 'id'=>'updateForm','method'=>'PUT', 'class'=>'form-horizontal')) }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<input type="text" id="updateInput" name="name" class="form-control" placeholder="Select Tag" value=""/>
					</h3>
				</div>
				<div  class="panel-body">
					<div id="editButtons" class="row fade hidden">
						<div class="col-md-12 col-md-offset-0 col-xs-4 col-xs-offset-2">
							<button type="submit" class="btn btn-warning btn-block">Update</button>	
							<div class="hidden-xs hidden-sm"></br></div>
						</div>
						<div class="col-md-12 col-xs-4">
							<button type="button" data-toggle="modal" data-target="#deleteModal" tag-id="" class="btn btn-danger btn-block">Delete</button>
						</div>
					</div>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
	
	<!--
	----	Tag relations table
	--->
	<div class="panel panel-default">
		<div class="panel-heading">	
			<h3 class="panel-title">
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3">
						<!-- Taggable Toggle -->
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<label class="btn btn-info btn-group active">
								<input type="radio" name="taggable" id="allToggle"checked> All
							</label>
							<label class="btn btn-info btn-group">
								<input type="radio" name="taggable" id="postsToggle"> Posts
							</label>
							<label class="btn btn-info btn-group">
								<input type="radio" name="taggable" id="worksToggle"> Works
							</label>
						</div>
					</div>
				</div>
			</h3>
		</div>
		<div class="panel-body" id="taggableResults">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-xs-5">Title</th>
						<th class="col-xs-5">Tags</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					</tr>
				</tbody>
			</table>
		</div>
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
					{{ Form::open(array('action'=>array('TagsController@destroy', 0), 'id'=>'deleteForm','method'=>'DELETE')) }}
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
		/*
		 *	updateForm validation
		 */
		$("#updateForm").submit(function(event){
			var hash;
			// Validate title, remove whitespace in case only spaces
			var val = $("#updateInput").val();
			val = val.replace(/ /g,'');
			if(val.length <= 0){
				var message = "Unable to update name. Field must not be blank.";
				hash = 'updateInput';
				
				alertHandler("tagsAlert", message, "error");
				event.preventDefault();
			}
			val = $("#updateInput").val();
			if(val.match(/[^-_a-zA-Z]/)){
				var message = "Unable to update name. Field contain only letters, numbers, or dashes.";
				hash = 'updateInput';
				
				alertHandler("tagsAlert", message, "error");
				event.preventDefault();
			}
			if(hash)
				window.location.hash = hash;
		});
	});
	
	
	/*
	*	Toggle label states
	*	Calls: setModal, updateTable
	*/
	$(".panel-body").on("click", ".tag", function(e){
		if(!$("#editButtons").hasClass("in"))
			$("#editButtons").toggleClass("in hidden")
		if(!$(this).hasClass("label-success"))
		{
			if($(".panel-body > .label-success").hasClass("newTag"))
				$(".panel-body > .label-success").toggleClass("label-success label-info");
			else
				$(".panel-body > .label-success").toggleClass("label-success label-default");
			
			$(this).removeClass("label-default label-info");
			$(this).addClass("label-success");
			
			var $tagID = $(this).attr("tag-id");
			var $tagName = $(this).children(".tagName").text();
			window.selectedID = $tagID;
			
			setModal($tagID, $tagName);
			setTable($tagID);
		}
	});

	
	/*
	*	Set tag-name and tag-id for modals
	*/
	function setModal($tagID, $tagName) {
		var url = $("#deleteForm").attr("action");
		<!-- regx to change url to selected resource in delete form confirmation modal -->
		url = url.replace(/\d*$/, $tagID);
		$("#deleteForm").attr("action", url);
		
		url = $("#updateForm").attr("action");
		<!-- regx to change url to selected resource in edit form -->
		url = url.replace(/\d*$/, $tagID);
		$("#updateForm").attr("action", url);
		
		$("#updateInput").val($tagName);
	};
	
	
	/*
	*	Update table based on taggable toggle
	*/
	function setTable($tagID) {
		
		if($("#allToggle").prop("checked"))
			var data = {taggableType: "all"};
			
		else if($("#postsToggle").prop("checked"))
			var data = {taggableType: "posts"};
			
		else if($("#worksToggle").prop("checked"))
			var data = {taggableType: "works"};
			
		sendData(data, "GET", "/admin/tags/"+$tagID)
		.done(function(response){
			$("#taggableResults").empty().html(response.html);
		});
	};
	
	
	/*
	*	Update table when post/works radio toggled
	*/
	$(":input[type=radio]").change(function () {
		if(window.selectedID != undefined)
			setTable(window.selectedID);
	});

	
	/*
	*	Create new tag and add to other tag elements
	*/
	$('#submitTag').click( function(e) {
		e.preventDefault();
		var name = $(':input[name=createTag]').val();
		
		// Validate name, remove whitespace in case only spaces
		if(name.replace(/ /g,'').length <= 0){
			var message = "Unable to update name. Field must not be blank.";
			
			alertHandler("tagsAlert", message, "error");
			window.location.hash = "tagsAlert";
		}
		// Validate name, only letters,numbers,dashes
		else if(name.match(/[^-_a-zA-Z]/)){
			var message = "Unable to update name. Field contain only letters, numbers, or dashes.";
			
			alertHandler("tagsAlert", message, "error");
			window.location.hash = "tagsAlert";
		}
		else{
			
			var data = new FormData();
			data.append("name", name);
			
			sendData(data, "POST", "/admin/tags")
			.done(function(response){
				if(response.passes)
				{
					$(".tag-display").append("<span class='tag label-info newTag' tag-id="+response.id+">#"+name+"</span>");
					$(':input[name=createTag]').val("");
				}
				else
				{
					var message =  "Unable to create \""+name+"\"."+response.msg;
					alertHandler("tagsAlert", message, "error");
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown){
				var message =  "Unable to create Tag \""+name+"\". "+errorThrown;
				alertHandler("tagsAlert", message, "error");
			});
		}
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
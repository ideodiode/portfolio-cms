{{--
---- Tag Panel - child view
----
---- @params(collection $tags)
----
---- Requires: 	function sendData(data, url)
---- 			function (.alert.close).click()
--}}


<!-- Tag error alert -->
<div id="tagsAlert" class="alert alert-warning fade" >
	<button type="button" class="close" aria-hidden="true">&times;</button>
	<ul>
	</ul>
</div>

<!-- Tag selection and creation panel -->
<div id="tagsPanel" class="form-group">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Tags:  
				<span class="sr-only">Legend:</span>
				<span class="label label-success">Selected</span>
				<span class="label label-default">Available</span>
				<span class="label label-info">New</span>
			</h3>
		</div>
		<div class="panel-body">
			@if(isset($relatedTags) && !empty($relatedTags))
				@foreach($relatedTags as $relatedTag)
					<span class="tag label-success"><span class="tag-name">{{ $relatedTag->name }}</span>
						<span class="tag-count">{{ $relatedTag->count }}</span></span>
				@endforeach
			@endif
			@foreach($tags as $tag)
				<span class="tag label-default"><span class="tag-name">{{ $tag->name }}</span>
					<span class="tag-count">{{ $tag->count }}</span></span>
			@endforeach
		</div>
		<div class="panel-footer">
			<div class="input-group">
				<label for="tagName" class="sr-only">Create a New Tag:</label>
				<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
				<input type="text" class="form-control" name="tagName" placeholder="MyNewTag">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" id="submitTag" name="createTag">Create New Tag</button>
				</span>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="tagsInput" name="tags" value={{ $relatedTagsString or '' }} >
</div>

<script>

/*
*	Add tag to hidden tag input and change state
*/
$(".panel-body").on("click", ".tag", function(e){
	var value = $("#tagsInput").attr("value");
	var tagName = "#"+$(this).children(".tag-name").text();
	
	
	
	// Chosen tag not selected yet, add to input
	if(!$(this).hasClass("label-success"))
	{
		$(this).removeClass("label-default label-info");
		$(this).addClass("label-success");
		value += tagName;
		$("#tagsInput").attr("value", value);
	}
	// Chosen tag selected AND .newTag, remove from input
	else if($(this).hasClass("newTag"))
	{
		$(this).toggleClass("label-success label-info");
		var re = new RegExp("\\b"+tagName+"\\b");
		value = value.replace(re,"");
		$("#tagsInput").attr("value", value);
	}
	// Chosen tag selected NOT .newTag, remove from input
	else
	{
		$(this).toggleClass("label-success label-default");
		value = value.replace(tagName,"");
		$("#tagsInput").attr("value", value);
	}
});

/*
*	Send new tag and add to other tag elements
*/
$('#submitTag').click( function(e) {
	e.preventDefault();
	name = $(':input[name=tagName]').val();
	
	// Validate name, remove whitespace in case only spaces
	if(name.replace(/ /g,'').length <= 0){
		message = "Unable to update name. Field must not be blank.";
		
		alertHandler("tagsAlert", message, "error");
		window.location.hash = "tagsAlert";
	}
	// Validate name, only letters,numbers,dashes
	else if(name.match(/[^-_a-zA-Z]/)){
		message = "Unable to update name. Field contain only letters, numbers, or dashes.";
		
		alertHandler("tagsAlert", message, "error");
		window.location.hash = "tagsAlert";
	}
	else{
		data = new FormData();
		data.append("name", name);
		
		sendData(data, "/admin/tags")
		.done(function(response){
			if(response.passes)
			{
				$("#tagsPanel .panel-body").append("<span class='tag label-info newTag' style='margin:0 .4em 0 0;'><span class='tag-name'>"+name+"</span> <span class='tag-count'>0</span></span>");
				$(":input[name='tagName']").val("");
			}
			else
			{
				var message = " Unable to create: \""+name+"\"."+response.msg;
				alertHandler("tagsAlert", message, "error");
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown){
			var message = " Unable to create Tag; "+errorThrown;
			alertHandler("tagsAlert", message, "error");
		});
	}
});

</script>
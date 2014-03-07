<div class="text-center">
	{{ $pagination }}
</div>
	<table class="table table-striped">
		<thead>
			<tr>
				@if(isset($taggables->first()->taggable_type))
				<th class="col-xs-5">Title</th>
				<th class="col-xs-5">Tags</th>
				<th class="col-xs-2">Type</th>
				@else
				<th class="col-xs-6">Title</th>
				<th class="col-xs-6">Tags</th>
				@endif
			</tr>
		</thead>
		<tbody id="taggableResults">
			@foreach($taggables as $taggable)
				<tr>
					<td>{{ $taggable->title }}</td>
					<td>
						@foreach($taggable->tags as $tag)
							#{{ ($tag->name)." " }}
						@endforeach
					</td>
					@if(isset($taggable->taggable_type))
						<td>{{ $taggable->taggable_type }}</td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>
<div class="text-center">
	{{ $pagination }}
</div>

<script>
$(document).ready(function()
{
	
	$(".pagination a").click(function()
	{
		if($("#allToggle").prop("checked"))
		{
			data = {taggableType: "all"};
		}
		else if($("#postsToggle").prop("checked"))
		{
			data = {taggableType: "posts"};
		}
		else if($("#worksToggle").prop("checked"))
		{
			data = {taggableType: "works"};
		}
		var myurl = $(this).attr('href');
		$.ajax(
		{
			data: data,
			url: myurl,
			type: "get",
			datatype: "html"
		})
		.done(function(data)
		{
			$("#taggableResults").empty().html(data.html);
		})
		.fail(function(jqXHR, ajaxOptions, thrownError)
		{
			  alert('No response from server');
		});
		return false;
	});
});
</script>
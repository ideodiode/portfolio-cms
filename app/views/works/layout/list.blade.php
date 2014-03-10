{{--
---- Work Layout - child view
----
---- @params collection $works
---- @params int $width
----
--}}


@if ($works->isEmpty())
	<p>There are no works! :(</p>
@else

{{ $works->links() }}

	<table class="table table-striped" style="word-wrap: break-word; table-layout: fixed;">
		<thead>
			<tr>		
				<th class="col-xs-5">Title</th>
				<th class="col-xs-4"><span class="glyphicon glyphicon-tags"></span> Tags</th>
				<th class="col-xs-3">Actions</th>		
			</tr>
		</thead>
		<tbody>
		
			@foreach($works as $work)
			<tr>
				<td>
					@if($work->featured)
						<span class='glyphicon glyphicon-star works-favorite'></span>
					@endif
					{{ $work->title }}
				</td>
				<td >
					@foreach($work->tags as $tag) 
						<span class="label label-primary">{{ trim($tag->name) }}</span>
					@endforeach</td>
				<td>
					<div class="row">
						<div class="col-md-6 slim-padding text-center">
							<a href="{{ action('WorksController@edit', $work->id) }}" class="btn btn-warning btn-sm btn-block">Edit</a>
						</div>
						<div class="col-md-6 slim-padding text-center">
							<button data-toggle="modal" data-target="#deleteModal" work-id="{{{ $work->id }}}" class="btn btn-danger btn-sm btn-block">Delete</button>
						</div>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
{{ $works->links() }}

@endif
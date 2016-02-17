@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<!-- Current Posts -->
			@if (count($posts) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						Current Posts
					</div>

					<div class="panel-body">
						<table class="table table-striped post-table">
							<thead>
								<th>&nbsp;</th>
								<th>Votes</th>
								<th>Post</th>
								<th>User</th>
							</thead>
							<tbody>
								@foreach ($posts as $post)
									<tr>
									<!-- Thumbs Up Button -->
										{{ csrf_field() }}
										<td>
												<button id="/post/{{ $post->id }}/up" class="btn btn-success">
													<i class="fa fa-thumbs-up"></i>
												</button>
												<button id="/post/{{ $post->id }}/down" class="btn btn-danger">
													<i class="fa fa-thumbs-down"></i>
												</button>
										</td>
										<td class="table-text"><div>{{ $post->voteCount }}</div></td>
										<td class="table-text"><div><a href="{{ $post->url }}">{{ $post->title }}</a></div></td>
										<td class="table-text"><div>{{ $post->user->name }}</div></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>
	</div>
<script>
	var csrf = document.getElementsByName('_token')[0].value;
	$("button[id^='/post/']").click(function() 
	{
		var votes = $(this).closest('td').next('td');
		$.post($(this).attr('id'), { _token: csrf }, function(data)
		{
			votes.text(data.votes);
		});
	});
</script>
@endsection

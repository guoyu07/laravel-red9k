@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-1 col-sm-10">
			<!-- Current Posts -->
			@if (count($posts) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						{{ isset($category) ? ucfirst($category) : 'All' }} Posts {{ isset($term) ? 'with ' . $term : '' }}
					</div>

					<div class="panel-body">
						<table class="table table-condensed post-table">
							<thead>
								<th>&nbsp;</th>
								<th>Votes</th>
								<th>Post</th>
								<th>Category</th>
								<th>Contributor</th>
							</thead>
							<tbody>
								@foreach ($posts as $post)
									<tr>
									<!-- Thumbs Up/Down Buttons -->
										{{ csrf_field() }}
										<td>
												<button id="/post/{{ $post->id }}/up" class="btn btn-success btn-xs">
													<i class="fa fa-thumbs-up"></i>
												</button>
												<br>
												<button id="/post/{{ $post->id }}/down" class="btn btn-danger btn-xs">
													<i class="fa fa-thumbs-down"></i>
												</button>
										</td>
										<td class="table-text"><div>{{ $post->voteCount }}</div></td>
										<td class="table-text">
											<div><a href="{{ $post->url }}">{{ $post->title }}</a></div>
											<div>
												<a style='font-size: 10px' href="{{ route('comments', ['postId' => $post->id]) }}">Comments</a>

												@if(Auth::check() && Auth::user()->admin)
													<!-- Post Delete Button -->
													<form action="/post/{{ $post->id }}/delete" method="POST">
														{{ csrf_field() }}
														{{ method_field('DELETE') }}
														<button type="submit" id="delete-post-{{ $post->id }}" class="btn btn-danger btn-xs">
															<i class="fa fa-btn fa-trash"></i> Delete Post
														</button>
													</form>
												@endif
											</div>
										</td>
										<td class="table-text"><div>{{ $post->category ? ucfirst($post->category) : 'Misc' }}</div></td>
										<td class="table-text">
											<div><a href="{{ route('user', ['user' => $post->user->id]) }}">{{ $post->user->name }}</a></div>

											<!-- Ban Button -->
											@if(Auth::check() && Auth::user()->admin)
												<div>
													<a href="{{route('confirmBan', ['userId' => $post->user->id])}}" class="btn btn-danger btn-xs">
														<i class="fa fa-btn fa-ban"></i> Ban User
													</a>
												</div>
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					{!! $posts->render() !!}
				</div>
			@else
				There is nothing to show here
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
			if (data.error)
			{
				votes.append(data.error);
			}
			else
			{
				votes.text(data.votes);
			}
		});
	});
</script>
@endsection

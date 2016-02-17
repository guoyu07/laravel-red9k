@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					New Post
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- New Post Form -->
					<form action="/post" method="POST" class="form-horizontal">
						{{ csrf_field() }}

						<!-- Post Title -->
						<div class="form-group">
							<label for="post-title" class="col-sm-3 control-label">Title</label>

							<div class="col-sm-6">
								<input type="text" name="title" id="post-title" class="form-control" value="{{ old('post') }}">
							</div>
						</div>
						
						<!-- Post Url -->
						<div class="form-group">
							<label for="post-url" class="col-sm-3 control-label">Url</label>
							
							<div class="col-sm-6">
								<input type="text" name="url" id="post-url" class="form-control" value="{{ old('post') }}">
							</div>
						</div>

						<!-- Add Post Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-btn fa-plus"></i>Add Post
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

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

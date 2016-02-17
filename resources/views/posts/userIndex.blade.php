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
								<th>Post</th>
								<th>User</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($posts as $post)
									<tr>
										<td class="table-text"><div><a href="{{ $post->url }}">{{ $post->title }}</a></div></td>
										<td class="table-text"><div>{{ $post->user->name }}</div></td>
										<!-- Post Delete Button -->
										<td>
											<form action="/post/{{ $post->id }}/delete" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}

												<button type="submit" id="delete-post-{{ $post->id }}" class="btn btn-danger">
													<i class="fa fa-btn fa-trash"></i>Delete
												</button>
											</form>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>
	</div>
@endsection

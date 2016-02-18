@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<!-- Current Posts -->
			@if (count($posts) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						Posts by {{$user->name}}
					</div>

					<div class="panel-body">
						<table class="table table-striped post-table">
							<thead>
								<th>Post</th>
								<th>User</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($posts as $post)
									<tr>
										<td class="table-text"><div><a href="{{ $post->url }}">{{ $post->title }}</a></div></td>
										<td class="table-text"><div>{{ $post->user->name }}</div></td>

										<td>
											<a href="{{ route('edit', ['postId' => $post->id]) }}" class="btn btn-primary btn-sm" role="button"><i class="fa fa-pencil"></i>
												Edit
											</a>
										</td>

										<!-- Post Delete Button -->
										<td>
											<form action="/post/{{ $post->id }}/delete" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}

												<button type="submit" id="delete-post-{{ $post->id }}" class="btn btn-danger btn-sm">
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

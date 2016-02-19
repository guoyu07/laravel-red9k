@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-1 col-sm-5">
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
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($posts as $post)
									<tr>
										<td class="table-text"><div><a href="{{ $post->url }}">{{ $post->title }}</a></div></td>

										@if ($post->user->id === Auth::user()->id)
											<!-- Post Delete Button -->
											<td>
												<form action="/post/{{ $post->id }}/delete" method="POST">
													{{ csrf_field() }}
													{{ method_field('DELETE') }}

													<button type="submit" id="delete-post-{{ $post->id }}" class="btn btn-danger btn-xs">
														<i class="fa fa-btn fa-trash"></i>Delete
													</button>
												</form>
											</td>
										@else
											<td>&nbsp;</td>
										@endif
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>
		<div class="col-sm-6">
			<!-- Current Comments -->
			@if (count($comments) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						Comments by {{$user->name}}
					</div>

					<div class="panel-body">
						<table class="table table-striped post-table">
							<thead>
							<th>Post</th>
							<th>Comment</th>
							<th>&nbsp;</th>
							</thead>
							<tbody>
							@foreach ($comments as $comment)
								<tr>
									<td class="table-text"><div><a href="{{ route('comments', ['postId' => $comment->post->id]) }}">{{ $comment->post->title }}</a></div></td>
									<td class="table-text"><div>{{ $comment->text }}</div></td>

									@if ($post->user->id === Auth::user()->id)
											<!-- Comment Delete Button -->
									<td>
										<form action="/comment/{{ $comment->id }}/delete" method="POST">
											{{ csrf_field() }}
											{{ method_field('DELETE') }}

											<button type="submit" id="delete-comment-{{ $comment->id }}" class="btn btn-danger btn-xs">
												<i class="fa fa-btn fa-trash"></i>Delete
											</button>
										</form>
									</td>
									@else
										<td>&nbsp;</td>
									@endif
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

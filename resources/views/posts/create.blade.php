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
					<form action="/red9k/post" method="POST" class="form-horizontal">
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

						<!-- Post Category -->
						<div class="form-group">
							<label for="post-category" class="col-sm-3 control-label">Category</label>

							<div class="col-sm-6">
								<select name="category" id="post-category" class="form-control" value="{{ old('post') }}">
									<option value="misc">Misc</option>
									<option value="art">Art</option>
									<option value="music">Music</option>
									<option value="writing">Writing</option>
									<option value="video">Video</option>
								</select>
							</div>
						</div>

						<!-- Add Post Button -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-btn fa-plus"></i>Add Post
									<span id="spinner"><i class="fa fa-spinner fa-pulse"></i></span>
								</button>
							</div>
						</div>


					</form>
				</div>
			</div>
		</div>
	</div>
<script>
	var spinner = $("#spinner");
	$(document).ready(function()
	{
		spinner.hide();
	})

	$("button").click(function()
	{
		spinner.show();
	});
</script>

@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New Comment
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Comment Form -->
                    <form action="/comment" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <!-- Comment Text -->
                        <div class="form-group">
                            <label for="post-text" class="col-sm-3 control-label">Text</label>

                            <div class="col-sm-6">
                                <textarea name="text" id="post-text" class="form-control" value="{{ old('post') }}"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="id" value="{{ $post->id }}">

                        <!-- Add Post Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i>Add Comment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

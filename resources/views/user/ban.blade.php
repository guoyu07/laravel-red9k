@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-1 col-sm-5">
            Are you <strong>sure</strong> you want to ban {{$user->name}}?
            <div>
                <a href="{{route('ban', ['userId' => $user->id])}}" class="btn btn-success btn-sm">
                    Yes
                </a>
                <a href="/posts" class="btn btn-danger btn-sm">
                    No
                </a>
            </div>
        </div>
    </div>
@endsection

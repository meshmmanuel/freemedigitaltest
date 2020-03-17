@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- SideBar --}}
        @include('layouts.left-sidebar')

        {{-- Form and Feeds --}}
        <div class="col-md-6">

            {{-- Form --}}
            <div class="card" style="margin-bottom: 10px">
                <form method="post" class="card-body" enctype="multipart/form-data" action="{{ route('post-store') }}">
                    @csrf
                    <div class="form-group">
                        <input id="my-input" class="form-control-file" type="file" name="file">
                    </div>
                    <div class="form-group">
                        <label for="">Who can see this?</label>
                        <select class="form-control" name="visible_by">
                            <option value="1">Friends</option>
                            <option value="2">Family</option>
                            <option value="3">Both</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            {{-- Feeds/Posts --}}
            <div class="card" style="padding: 10px;">
                @foreach ($posts as $post)
                    <div class="card" style="margin-bottom: 10px">
                        <img class="card-img-top" src="{{ asset('/posts/' . $post->content ) }}" alt="">
                        <div class="card-body">
                            <b>Posted by: @if ($post->user_id == Auth::id()) me @else {{$post->name}} @endif </b>
                            @if (Auth::id() == $post->user_id)
                                <p><a class="btn btn-danger" href="{{ route('post-trash', ['id' => $post->id ]) }}">Trash</a></p>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- <div id='app' class="container">
                    <post-component :auth-user=" {{ Auth::user() }} " :posts=" {{ $posts }} " :friends= "{{ Auth::user()->friends }}" :families= "{{ Auth::user()->families }}" ></post-component>
                </div> --}}
            </div>
        </div>

        {{-- RightSide Bar --}}
        @include('layouts.right-sidebar')

    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- SideBar --}}
        @include('layouts.left-sidebar')

        {{-- Form and Feeds --}}
        <div class="col-md-6">

            {{-- Feeds/Posts --}}
            <div class="card" style="padding: 10px;">
                @forelse ($trash as $post)
                    <div class="card" style="margin-bottom: 10px">
                        <img class="card-img-top" src="{{ asset('/posts/' . $post->content ) }}" alt="">
                        <div class="card-body">
                            <a class="btn btn-success" href="{{ route('trash-restore', ['id' => $post->id ]) }}">Restore</a>
                        </div>
                    </div>
                @empty
                    <div>
                        Nothing in your trash can.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- <div id='app' class="container">
            <example-component></example-component>
        </div> --}}

        {{-- RightSide Bar --}}
        @include('layouts.right-sidebar')

    </div>
</div>
@endsection

@section('scripts')
    <script src="js/app.js"></script>
    <script>
        export default {

        }
    </script>
@endsection

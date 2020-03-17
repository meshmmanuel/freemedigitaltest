@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{-- LeftSideBar --}}
        @include('layouts.left-sidebar')

        {{-- Family --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    My Friends
                </div>

                <div class="card-body">
                    <p>My Friends </p>
                    <div class="row">
                        @forelse ($friends as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
                                        <a href="{{ route('unfriend', ['id' => $user->id ]) }}" class="btn btn-danger text-center">Unfriend</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info" role="alert">
                                <strong>No friends yet</strong>
                            </div>
                        @endforelse
                    </div>

                    <br>
                    <p>Pending Request </p>
                    <div class="row">
                        {{-- Incoming Requests --}}
                        @forelse ($incoming as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
                                        <a href="{{ route('friend-reject', ['id' => $user->id ]) }}" class="btn btn-warning">Reject</a>&nbsp;
                                        <a href="{{ route('friend-accept', ['id' => $user->id ]) }}" class="btn btn-info">Accept</a>
                                    </div>
                                </div>
                            </div>
                        @empty

                        @endforelse

                        {{-- Outgoing Requests --}}
                        @forelse ($outgoing as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
                                        <a href="{{ route('friend-cancel', ['id' => $user->id ]) }}" class="btn btn-warning">Cancel Request</a>
                                    </div>
                                </div>
                            </div>
                        @empty

                        @endforelse
                    </div>

                    <br>
                    <p>Add Friends </p>
                    <div class="row">
                        @forelse ($users as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
                                        <a href="{{ route('friend-add', ['id' => $user->id ]) }}" class="btn btn-primary">Send Request</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info" role="alert">
                                <strong>No Users</strong>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- RightSide Bar --}}
        @include('layouts.right-sidebar')
    </div>
</div>
@endsection

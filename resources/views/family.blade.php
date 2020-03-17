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
                    Family Account
                </div>

                <div class="card-body">
                    {{-- If User doesn't not have a family account --}}
                    @if (!Auth::user()->family)
                        <p>No Family account yet</p>
                        <a class="btn btn-primary" href="{{ route('family-create') }}" role="button">Create Family</a>
                    @else
                        <a class="btn btn-danger" href="{{ route('family-delete') }}" role="button">Delete Famliy</a>
                        <br>
                        <hr>
                        <p>My Family Memebers </p>
                        <div class="row">
                            @forelse ($familyMembers as $user)
                                <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <p class="card-text">{{ $user->name }}</p>
                                            <a href="{{ route('family-remove', ['id' => $user->id ]) }}" class="btn btn-danger text-center">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info" role="alert">
                                    <strong>No family members yet</strong>
                                </div>
                            @endforelse
                        </div>
                        <br>
                        <hr>
                        <p>Pending Request </p>
                        <div class="row">
                            @forelse ($outgoing as $user)
                                <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <p class="card-text">{{ $user->name }}</p>
                                            <a href="{{ route('family-cancel', ['id' => $user->id ]) }}" class="btn btn-danger text-center">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                {{-- <div class="alert alert-info" role="alert">
                                    <strong>No family members yet</strong>
                                </div> --}}
                            @endforelse
                    </div>
                    @endif
                    <hr>
                    <p>Other Families I belong to </p>
                    <div class="row">
                        @forelse ($others as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text text-center">{{ $user->name }}<br> Family</p>
                                        <a href="{{ route('family-view', ['id' => $user->id ]) }}" class="btn btn-info text-center">View Members</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info" role="alert">
                                <strong>No family members yet</strong>
                            </div>
                        @endforelse
                    </div>
                    <br>
                    <hr>
                    <p>Incoming Family Request </p>
                    <div class="row">
                        @forelse ($incoming as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
                                        <a href="{{ route('family-reject', ['id' => $user->id ]) }}" class="btn btn-warning">Reject</a>&nbsp;
                                        <a href="{{ route('family-accept', ['id' => $user->id ]) }}" class="btn btn-info">Accept</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- <div class="alert alert-info" role="alert">
                                <strong>No family members yet</strong>
                            </div> --}}
                        @endforelse
                    </div>
                    <hr>
                    <p>Add Members </p>
                    <div class="row">
                        @forelse ($users as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
                                        <a href="{{ route('family-add', ['id' => $user->id ]) }}" class="btn btn-primary">Add</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info" role="alert">
                                <strong>No users</strong>
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

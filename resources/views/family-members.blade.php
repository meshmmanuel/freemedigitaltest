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
                    Member of {{ $owner->name }} Family
                </div>

                <div class="card-body">
                    <div class="row">
                        @forelse ($familyMembers as $user)
                            <div class="col-sm-1-12" style="margin: 0px 5px 5px 5px">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p class="card-text">{{ $user->name }}</p>
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

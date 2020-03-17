<div class="col-md-3" style="margin-bottom: 10px">
    <div class="list-group">
        <a href="{{ url('/home') }}" class="list-group-item list-group-item-action @if(Str::contains(url()->current(), 'home')) {{'active'}} @endif">Dashboard</a>
        <a href="{{ route('family') }}" class="list-group-item list-group-item-action @if(Str::contains(url()->current(), 'family')) {{'active'}} @endif">Family Account</a>
        <a href="{{ route('friends') }}" class="list-group-item list-group-item-action @if(Str::contains(url()->current(), 'friends')) {{'active'}} @endif">Friends</a>
        <a href="{{ route('trash') }}" class="list-group-item list-group-item-action @if(Str::contains(url()->current(), 'trash')) {{'active'}} @endif">Trash Can</a>
    </div>
</div>

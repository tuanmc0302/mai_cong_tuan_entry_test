@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Create Location</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('locations.store') }}">
            @csrf
            @include('locations._form', ['location' => null])
        </form>
    </div>
</div>
@endsection
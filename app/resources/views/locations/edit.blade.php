@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Edit Location</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('locations.update', $location) }}">
            @csrf
            @method('PUT')
            @include('locations._form', ['location' => $location])
        </form>
    </div>
</div>
@endsection
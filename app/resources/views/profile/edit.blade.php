
@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Profile</h5>
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Avatar</label>
                <div class="mb-2">
                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/images/placeholders/placeholder.jpg') }}"
                         class="rounded-circle" width="80" height="80" alt="avatar">
                </div>
                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                    @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('gender', $user->gender) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">New password <small class="text-muted">(để trống nếu không đổi)</small></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm new password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

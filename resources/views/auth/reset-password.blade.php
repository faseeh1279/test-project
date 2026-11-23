@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-soft">
                <div class="card-body p-4 p-lg-5">
                    <h1 class="h3 fw-bold mb-2">Set a new password</h1>
                    <p class="text-muted mb-4">Choose a new password for your account.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auth.password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New password</label>
                            <input id="password" type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm new password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="8" autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
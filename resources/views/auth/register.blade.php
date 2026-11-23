@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<style>
    :root {
        --brand-ink: #1c1f26;
        --brand-indigo: #4338ca;
        --brand-indigo-light: #6d5ef8;
        --brand-mist: #f6f7fb;
    }

    .auth-shell {
        min-height: 100vh;
        background: var(--brand-mist);
        display: flex;
        align-items: center;
    }

    .auth-card {
        border: none;
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 1.5rem 3rem rgba(28, 31, 38, 0.08);
    }

    .auth-side {
        background: linear-gradient(160deg, var(--brand-indigo) 0%, var(--brand-indigo-light) 100%);
        color: #fff;
        padding: 3rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100%;
    }

    .auth-side h3 {
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .auth-side .brand-mark {
        width: 44px;
        height: 44px;
        border-radius: 0.75rem;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .auth-form-col {
        padding: 3rem 2.75rem;
    }

    .auth-form-col h2 {
        font-weight: 700;
        color: var(--brand-ink);
        letter-spacing: -0.02em;
    }

    .auth-form-col .subtitle {
        color: #6b7280;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.6rem;
        border: 1px solid #e2e4ea;
    }

    .form-control:focus {
        border-color: var(--brand-indigo-light);
        box-shadow: 0 0 0 0.2rem rgba(109, 94, 248, 0.15);
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--brand-ink);
    }

    .btn-brand {
        background: var(--brand-indigo);
        border: none;
        color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 0.6rem;
        font-weight: 600;
        transition: background 0.15s ease;
    }

    .btn-brand:hover {
        background: #372ea8;
        color: #fff;
    }

    .divider-text {
        color: #9ca3af;
        font-size: 0.85rem;
    }
</style>

<div class="auth-shell">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="card auth-card">
                    <div class="row g-0">

                        {{-- Left brand panel --}}
                        <div class="col-md-5 d-none d-md-block">
                            <div class="auth-side">
                                <div>
                                    <div class="brand-mark mb-4">A</div>
                                    <h3 class="mb-2">Welcome back</h3>
                                    <p class="mb-0" style="color: rgba(255,255,255,0.85);">
                                        Sign in to pick up right where you left off.
                                    </p>
                                </div>
                                <p class="mb-0 small" style="color: rgba(255,255,255,0.6);">
                                    &copy; {{ date('Y') }} Your Company
                                </p>
                            </div>
                        </div>

                        {{-- Right form panel --}}
                        <div class="col-md-7">
                            <div class="auth-form-col">

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <h2 class="mb-1">Register Now</h2>
                                <p class="subtitle mb-4">Enter your details to access your account.</p>

                                <form action="{{ route('auth.store') }}" method="POST" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input
                                            type="text"
                                            id="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            name="username"
                                            placeholder="johndoe"
                                            value="">

                                        @error('username')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input
                                            type="email"
                                            id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            name="email"
                                            placeholder="you@example.com"
                                            value="">

                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label" for="password">Password</label>
                                        <input
                                            type="password"
                                            id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password"
                                            placeholder="Enter your password">

                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                                        <input
                                            type="password"
                                            id="password_confirmation"
                                            class="form-control"
                                            name="password_confirmation"
                                            placeholder="Re-enter your password">
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label small" for="remember">Remember me</label>
                                        </div>
                                        <a href="{{ route('login') }}" class="small text-decoration-none" style="color: var(--brand-indigo);">Already have an account? Log in</a>
                                    </div>
                                    <div class="text-center mb-4">
                                        <span class="small text-muted">
                                            Already have account?
                                        </span>

                                        <a href="{{ route('login') }}"
                                        class="small text-decoration-none"
                                        style="color: var(--brand-indigo); font-weight: 600;">
                                            Login
                                        </a>
                                    </div>
                                    <button type="submit" class="btn btn-brand w-100">Register</button>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.auth')

@section('title', 'Forgot Password')

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
        box-shadow: 0 1.5rem 3rem rgba(28,31,38,.08);
    }

    .auth-side {
        background: linear-gradient(160deg, var(--brand-indigo), var(--brand-indigo-light));
        color: #fff;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100%;
    }

    .brand-mark {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
    }

    .auth-form {
        padding: 3rem;
    }

    .auth-form h2 {
        font-weight: 700;
        color: var(--brand-ink);
    }

    .subtitle {
        color: #6b7280;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control {
        border-radius: .6rem;
        padding: .75rem 1rem;
    }

    .form-control:focus {
        border-color: var(--brand-indigo-light);
        box-shadow: 0 0 0 .2rem rgba(109,94,248,.15);
    }

    .btn-brand {
        background: var(--brand-indigo);
        color: white;
        border: none;
        padding: .75rem;
        border-radius: .6rem;
        font-weight: 600;
    }

    .btn-brand:hover {
        background: #372ea8;
        color: white;
    }
</style>

<div class="auth-shell">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-9">

                <div class="card auth-card">

                    <div class="row g-0">

                        {{-- Left Side --}}
                        <div class="col-md-5 d-none d-md-block">

                            <div class="auth-side">

                                <div>

                                    <div class="brand-mark mb-4">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>

                                    <h3>Password Recovery</h3>

                                    <p style="color:rgba(255,255,255,.85)">
                                        Forgot your password? Don't worry.
                                        We'll email you a secure password reset link.
                                    </p>

                                </div>

                                <small style="color:rgba(255,255,255,.6)">
                                    © {{ date('Y') }} GameStore Admin
                                </small>

                            </div>

                        </div>

                        {{-- Right Side --}}
                        <div class="col-md-7">

                            <div class="auth-form">

                                <h2>Forgot Password</h2>

                                <p class="subtitle mb-4">
                                    Enter your email address below and we'll send you a password reset link.
                                </p>

                                @if(session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('auth.password.email') }}">

                                    @csrf

                                    <div class="mb-4">

                                        <label class="form-label">
                                            Email Address
                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            value="{{ old('email') }}"
                                            placeholder="Enter your email"
                                            required
                                            autofocus>

                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-brand w-100">

                                        <i class="bi bi-envelope-paper me-2"></i>

                                        Send Password Reset Link

                                    </button>

                                </form>

                                <div class="text-center mt-4">

                                    <a
                                        href="{{ route('login') }}"
                                        class="text-decoration-none">

                                        <i class="bi bi-arrow-left me-1"></i>

                                        Back to Login

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
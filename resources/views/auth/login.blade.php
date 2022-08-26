@extends('layouts.app')

@section('content')
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }
    .form-signin .checkbox {
        font-weight: 400;
    }
    .form-signin .form-floating:focus-within {
        z-index: 2;
    }
    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
<div class="text-center">
<img src="{{ Url('') }}/assets/images/simewood-logo.jpeg" alt="Logo" width="400">
</div>
<div class="form-signin">

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <h1 class="h3 mb-4 mt-4 fw-normal text-center">LOGIN</h1>

        <div class="form-floating">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>
            <label for="floatingInput">{{ __('E-Mail Address') }}</label>
        </div>
        <div class="form-floating">
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Password" required autocomplete="current-password" name="password">
            <label for="floatingPassword">{{ __('Password') }}</label>
        </div>
        <div class="checkbox mb-3 text-center">
            <label>
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
            </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit"><i class="fas fa-sign-in-alt"></i> {{ __('Login') }}</button>
    </form>
</div>

@endsection

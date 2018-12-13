@extends('layouts.app')

@section('content')
<div class="center">
    <h1>{{ __('Login') }}</h1>
    <form method="POST" action="{{ route('login') }}" class="form">
        @csrf
        <div>
            <label for="email">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>

        <div>
            <label for="password">{{ __('Password') }}</label>
            <input id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>

        <div class="checkbox">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>

        <div>
        <button type="submit" class="btn btn-primary">
            {{ __('Login') }}
        </button>

        @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
        </div>

    </form>
</div>
@endsection

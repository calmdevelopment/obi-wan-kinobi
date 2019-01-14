@extends('layouts.app')

@section('content')
<div class="center">
    <h1>{{ __('Reset Password') }}</h1>
    <form method="POST" action="{{ route('password.email') }}" class="form">
        @csrf
        <div>
            <label for="email" >{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div>
            <button type="submit" class="btn btn-primary">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </form>
</div>
@endsection

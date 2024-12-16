@extends('layouts.app-login')

@section('content')
    <form action="{{ route('login') }}" method="POST" id="login-form" class="smart-form client-form">

    @csrf
        <header>
            Sign In
        </header>

        <fieldset>
            
            <section>
                <label class="label">E-mail</label>
                <label class="input"> <i class="icon-append fa fa-user"></i>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
            </section>

            <section>
                <label class="label">Password</label>
                <label class="input"> <i class="icon-append fa fa-lock"></i>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"> 
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>

                @if (Route::has('password.request'))
                <div class="note">
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
                @endif
            </section>
            <!-- 
            <section>
                <label class="checkbox">
                    <input type="checkbox" name="remember" checked="">
                    <i></i>Stay signed in</label>
            </section>
            -->
        </fieldset>
        <footer>
            <button type="submit" class="btn btn-primary">
                Sign in
            </button>
        </footer>
    </form>
@endsection

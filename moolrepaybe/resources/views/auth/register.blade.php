@extends('auth.layouts')

@section('content')

<div class="login-container" style="background-image: url({{app('url')->asset('public/assets/beam.webp')}}); background-size: cover;">
    <div class="card padding-3 form-card">
        <div class="img-area">
            <img width="160px" src="{{ app('url')->asset('public/assets/Mobile.svg') }}" alt="LOGO">
            <p class="no-margin padding-0">Moolre Payment Gateway - By Darko Awuah</p>
        </div>
        <h5 class="card-h5 center-text">Register</h5>
        <form action="{{ route('store') }}" method="post">
            @csrf
            <div >
                <input type="text" class="@error('name') is-invalid @enderror" id="name"
                    name="name" value="{{ old('name') }}" placeholder="Your name" required>
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
                <div >
                    <input type="email" class="@error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" placeholder="Email address" required>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
     
                <div >
                    <input type="password" class="@error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div >
                    <input type="password" class="@error('password_confirmation') is-invalid @enderror" id="confirm_password"
                        name="password_confirmation" placeholder="Retype password" required>
                    @if ($errors->has('confirm_password'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
            <div>
                <input type="submit"  value="Register">
            </div>
            <p>Already have an account? <a style="color: green" href="{{ route('login') }}"> login here</a> </p>
        </form>
    </div>
</div>
    
@endsection
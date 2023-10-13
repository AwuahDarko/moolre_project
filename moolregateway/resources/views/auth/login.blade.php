@extends('auth.layouts')

@section('content')
    <div class="login-container">
        <div class="card padding-3 form-card">
            <div class="img-area">
                <img width="160px" src="{{ app('url')->asset('public/assets/Mobile.svg') }}" alt="LOGO">
                <p class="no-margin padding-0">Moolre Payment Gateway - By Darko Awuah</p>
            </div>
            <h5 class="card-h5 center-text">Login</h5>
            <form action="{{ route('authenticate') }}" method="post">
                @csrf
          
        
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
                <div>
                    <input type="submit"  value="Login">
                </div>
                <p>Don't have an account? <a href="{{ route('register') }}"> register here</a> </p>

            </form>
        </div>
    </div>
@endsection

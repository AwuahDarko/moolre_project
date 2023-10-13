<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Merchant;
use \App\Models\Transaction;
use Auth;
use Hash;



class AuthLoginRegisterController extends Controller {
    /**
    * Instantiate a new LoginRegisterController instance.
    */

    public function __construct() {
        // $this->middleware( 'guest' )->except( [
        //     'logout', 'dashboard'
        // ] );
    }

    /**
    * Display a registration form.
    *
    * @return \Illuminate\Http\Response
    */

    public function register() {
        return view( 'auth.register' );
    }

    /**
    * Store a new user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $request->validate( [
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:merchants',
            'password' => 'required|min:8|confirmed',
        ] );

        Merchant::create( [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $request->password ),
            // 'phone' => '',
            // 'public_key' => '',
            // 'private_key' => ''
        ] );

        $credentials = $request->only( 'email', 'password' );
        Auth::attempt( $credentials );
        $request->session()->regenerate();
        return redirect()->route( 'dashboard' )
        ->withSuccess( 'You have successfully registered & logged in!' );
    }

    /**
    * Display a login form.
    *
    * @return \Illuminate\Http\Response
    */

    public function login() {
        return view( 'auth.login' );
    }


 

    /**
    * Authenticate the user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function authenticate( Request $request ) {
        $credentials = $request->validate( [
            'email' => 'required|email',
            'password' => 'required'
        ] );
        // auth('client')->login($add);
        // Auth::attempt( $credentials ) 
        // auth('client')->attempt($add);
        if (Auth::attempt( $credentials )) {
            $request->session()->regenerate();
            return redirect()->route( 'dashboard' )
            ->withSuccess( 'You have successfully logged in!' );
        }

        return back()->withErrors( [
            'email' => 'Your provided credentials do not match in our records.',
        ] )->onlyInput( 'email' );

    }

    /**
    * Display a dashboard to authenticated users.
    *
    * @return \Illuminate\Http\Response
    */

    public function dashboard() {
        
        if ( Auth::check() ) {
            $merchant = Merchant::find(Auth::user()->id);
            return view( 'auth.dashboard', compact('merchant') );
        }

        return redirect()->route( 'login' )
        ->withErrors( [
            'email' => 'Please login to access the dashboard.',
        ] )->onlyInput( 'email' );
    }

    /**
    * Log out the user from application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function logout( Request $request ) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route( 'login' )
        ->withSuccess( 'You have logged out successfully!' );
        ;
    }


       /**
    * Saves settings.
    *
    * @return \Illuminate\Http\Response
    */

    public function settings(Request $request){

        if (! Auth::check() )return redirect()->route( 'login' );
        // dd(Auth::user()->id);
        $request->validate( [
            'domain' => 'required|string|max:250',
            'phone' => 'required|max:250|unique:merchants',
            'callback' => 'required|unique:merchants'
        ] );

        $merchant = Merchant::find(Auth::user()->id);

        $merchant->website = $request->domain;
        $merchant->phone = $request->phone;
        $merchant->callback = $request->callback;
        $merchant->save();

        $merchant = Merchant::find(Auth::user()->id);
        return view( 'auth.dashboard', compact('merchant') );
    }


    public function generate_api_key(){
        if (! Auth::check() )return redirect()->route( 'login' );

        $merchant = Merchant::find(Auth::user()->id);

        $pkey = bin2hex(random_bytes(32));
        $skey = bin2hex(random_bytes(32));

        $merchant->public_key = 'pk_'.$pkey;
        $merchant->private_key = 'pk_'.$skey;

        $merchant->save();

        return response()->json(['message' => 'Sucesss', 'key' =>  $merchant->public_key]);

    }

    public function transactions(Request $request){

        if ( Auth::check() ) {
            // $merchant = Merchant::find(Auth::user()->id);
            $transactions = Transaction::where(['merchant_id' => Auth::user()->id])->paginate(10);
            return view( 'auth.transactions', compact('transactions') );
        }

        return redirect()->route( 'login' )
        ->withErrors( [
            'email' => 'Please login to access the dashboard.',
        ] )->onlyInput( 'email' );
    }

}
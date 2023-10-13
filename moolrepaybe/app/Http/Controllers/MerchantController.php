<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility\CryptoUtility;
use Illuminate\Support\Facades\Crypt;
use App\Utility\Logger;

class MerchantController extends Controller {

    public function initiateMomo( Request $request ) {

        try {
            $url = \env( 'GATEWAY_URL' ).'/momo/initialize';
            // return response()->json( csrf_token() );

            $fields = [
                'phone' => $request->phone,
                'provider' => $request->provider,
                'unique_id' => $request->unique_id,
                'amount' => $request->amount,

            ];

            $encrypted = Crypt::encrypt( $fields );
            ;

            $body = array(
                'data' => $encrypted
            );

            $fields_string = http_build_query( $body );

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$request->private_key,
                'Cache-Control: no-cache',
                'X-CSRF-TOKEN: ' . csrf_token()
            ) );

            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            //execute post
            $response = curl_exec( $ch );
            $err = curl_error( $ch );
            curl_close( $ch );

            if ( $err ) {
                $results = 'cURL Error #:' . $err;
            } else {
                $results = $response;
                // $respond = json_decode( $response );
            }

            Logger::createLog( $request->merchant_id, $request->path(), 'mobile money payment initiated', request()->getContent() );

            return response()->json( json_decode( $results ) );
        } catch( Exception $e ) {
            Logger::logError( json_encode( $e->getTrace() ), json_encode( $e->getMessage() ), $request->path(),  request()->getContent() );
          

            return response()->json( [ 'message' =>'Fatal error occurred', 'status' => 500 ], 500 );
        }

    }

    public function proceedMomo( Request $request ) {

        try {
            $url = \env( 'GATEWAY_URL' ).'/momo/proceed';
            // return response()->json( csrf_token() );

            $fields = [
                'transaction_id' => $request->transaction_id,
                'unique_id' => $request->unique_id,
                'status' => $request->status,
            ];

            $encrypted = Crypt::encrypt( $fields );
            ;

            $body = array(
                'data' => $encrypted
            );

            $fields_string = http_build_query( $body );

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$request->private_key,
                'Cache-Control: no-cache',
                'X-CSRF-TOKEN: ' . csrf_token()
            ) );

            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            //execute post
            $response = curl_exec( $ch );
            $err = curl_error( $ch );
            curl_close( $ch );

            if ( $err ) {
                // $results = 'cURL Error #:' . $err;
                return response()->json( [ 'message' =>'A fatal error occurred', 'status' => 500 ], 500 );
            }
            $respond = json_decode( $response );
            // return response()->json( $response);

            Logger::createLog( $request->merchant_id, $request->path(), 'mobile money confirmation', request()->getContent(), $response );

            if ( $respond->status == 200 and $request->status == 'success' ) {
                return response()->json( [ 'message' =>'Your payment was successful', 'status' => 200 ], 200 );
            } elseif ( $respond->status == 200 and $request->status == 'failed' ) {
                return response()->json( [ 'message' =>'Your payment failed', 'status' => 400 ], 400 );
            } else {
                return response()->json( [ 'message' =>'Transaction not found', 'status' => 400 ], 400 );
            }
            
        } catch( Exception $e ) {
            Logger::logError( json_encode( $e->getTrace() ), json_encode( $e->getMessage() ), $request->path(),  request()->getContent() );
            

            return response()->json( [ 'message' =>'Fatal error occurred', 'status' => 500 ], 500 );
        }

    }

    public function initiateCard( Request $request ) {
        try {

            $url = \env( 'GATEWAY_URL' ).'/card/initialize';

            $fields = [
                'email' => $request->email,
                'card' => $request->card,
                'unique_id' => $request->unique_id,
                'amount' => $request->amount,
                'expire' => $request->expire,
                'cvc' => $request->cvc,
                'status' => $request->status
            ];

            $encrypted = Crypt::encrypt( $fields );
            ;

            $body = array(
                'data' => $encrypted
            );

            $fields_string = http_build_query( $body );

            $ch = curl_init();

            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$request->private_key,
                'Cache-Control: no-cache',
                'X-CSRF-TOKEN: ' . csrf_token()
            ) );

            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            //execute post
            $response = curl_exec( $ch );
            $err = curl_error( $ch );
            curl_close( $ch );

            if ( $err ) {
                $results = 'cURL Error #:' . $err;
            } else {
                $results = $response;
                // $respond = json_decode( $response );
            }

            Logger::createLog( $request->merchant_id, $request->path(), 'Card payment', request()->getContent(), $response );
            return response()->json( json_decode( $results ) );

        } catch( Exception $e ) {
            Logger::logError( json_encode( $e->getTrace() ), json_encode( $e->getMessage() ), $request->path(),  request()->getContent() );

            return response()->json( [ 'message' =>'Fatal error occurred', 'status' => 500 ], 500 );
        }

    }

}

/*


try{
            
}catch( Exception $e ) {
    Logger::logError(json_encode( $e->getTrace() ), json_encode( $e->getMessage() ), $request->path(),  request()->getContent());

    return response()->json( [ 'message' =>'Fatal error occurred', 'status' => 500 ], 500 );
}
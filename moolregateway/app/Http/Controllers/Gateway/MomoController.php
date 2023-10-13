<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use \App\Utility\NotificationUtility;
use App\Utility\Logger;

class MomoController extends Controller {
    public function initialize( Request $request ) {

        try {
            // generate transaction id
            $transaction_id = rand( 111111111, 999999999 );
            // generate otp code
            $otp_code = rand( 1111, 9999 );

            $transaction = new Transaction;
            $transaction->transaction_id = $transaction_id;
            $transaction->unique_id = $request[ 'request' ][ 'unique_id' ];
            $transaction->merchant_id = $request[ 'merchant_id' ];
            $transaction->amount =  $request[ 'request' ][ 'amount' ];
            $transaction->payment_mode = $request[ 'request' ][ 'provider' ];
            $transaction->status = 'pending';
            $transaction->phone = $request[ 'request' ][ 'phone' ];

            $transaction->save();

            $msg = "Your OTP code is $otp_code. Do not share your code with anyone";
            $notification = new NotificationUtility;

            $sms = $notification->send_otp( $request[ 'request' ][ 'phone' ], $msg );

            Logger::createLog( $request[ 'merchant_id' ], '', 'mobile money payment initialized', request()->getContent() );

            return response()->json( [
                'transaction_id'=> $transaction_id,
                'otp_code'=> $otp_code,
                'message' => 'Confirm otp code to proceed with payment',
                'sms' => $sms,
                'unique_id'=> $request[ 'request' ][ 'unique_id' ],
                'status' => 200
            ] );
        } catch( Exception $e ) {
            Logger::logError( json_encode( $e->getTrace() ), json_encode( $e->getMessage() ), $request->path(),  request()->getContent() );

            return response()->json( [ 'message' =>'Fatal error occurred', 'status' => 500 ], 500 );
        }

    }

    public function finalize( Request $request ) {

        try {
            $transaction =  Transaction::where( [ 'transaction_id' => $request[ 'request' ][ 'transaction_id' ], 'unique_id' => $request[ 'request' ][ 'unique_id' ] ] )->first();

            if ( empty( $transaction ) ) {
                return \response()->json( [ 'status' => 400 ] );
            }

            $transaction->status = $request[ 'request' ][ 'status' ];
            $transaction->save();

            $msg = 'This email is to acknowledge that your payment was successful.\n'.
            'Amount paid: GHS'.$transaction->amount.'\n'.
            'Thank you';

            if ( $request[ 'request' ][ 'status' ] == 'success' ) {
                $notification = new NotificationUtility;
                $sms = $notification->send_otp( $transaction->phone, $msg );
            }

            Logger::createLog( $request[ 'merchant_id' ], '', 'mobile money payment completed', request()->getContent() );

            return \response()->json( [ 'status' => 200 ] );
        } catch( Exception $e ) {
            throw new Exception( 'Error send email notification' );
        }

    }

}
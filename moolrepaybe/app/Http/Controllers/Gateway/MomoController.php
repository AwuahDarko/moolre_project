<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;



class MomoController extends Controller
{
    public function initialize(Request $request){
        // generate transaction id
        $transaction_id = rand(111111111, 999999999);
        // generate otp code
        $otp_code = rand(1111, 9999);

        $transaction = new Transaction;
        $transaction->transaction_id = $transaction_id;
        $transaction->unique_id = $request->unique_id;
        $transaction->merchant_id = $request->merchant_id;
        $transaction->amount =  $request->amount;
        $transaction->payment_mode = $request->provider;
        $transaction->status = 'pending';
        $transaction->phone = $request->phone;

        $transaction->save();

        $msg = "Your OTP code is $otp_code. Do not share your code with anyone";
        $sms = $this->sendOTP($request->phone, $msg);

        return response()->json([
            'transaction_id'=> $transaction_id,
            'otp_code'=> $otp_code,
            'message' => 'Confirm otp code to proceed with payment',
            'sms' => $sms,
            'status' => 200
        ]);

    }

    private function sendOTP($phone, $msg){
        $url = "https://apps.hawksms.com/smsapi?key=75c99c389cd396022028&to=$phone&msg=$msg&sender_id=EverQuik";


        $curl = curl_init();

            curl_setopt_array( $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.env( 'PAYSTACK_SECRET_KEY' ),
                    'Cache-Control: no-cache',
                ),
            ) );

            $response = curl_exec( $curl );
            $err = curl_error( $curl );

            curl_close( $curl );

            // return true;
            if ( $err ) {
                return 'cURL Error #:' . $err;
            } else {
                return $response;
                // $respond = json_decode( $response );
            }
    }
}

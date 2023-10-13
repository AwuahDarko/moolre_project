<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Merchant;
use \App\Utility\NotificationUtility;
use App\Utility\Logger;


class CardController extends Controller
{
     public function initializeCard(Request $request){
        // generate transaction id
        $transaction_id = rand(111111111, 999999999);
        // generate otp code
        $otp_code = rand(1111, 9999);

        $transaction = new Transaction;
        $transaction->transaction_id = $transaction_id;
        $transaction->unique_id = $request['request']['unique_id'];
        $transaction->merchant_id = $request['merchant_id'];
        $transaction->amount =  $request['request']['amount'];
        $transaction->payment_mode = 'card';
        $transaction->status = $request['request']['status'];
        $transaction->email = $request['request']['email'];
        
        $transaction->save();

        
        // TODO === call the callback url
        $merchant = Merchant::find($request['merchant_id']);
        $this->callCallback($merchant, $transaction, $request);
        
        // TODO === send email invoice
         if ( $request[ 'request' ][ 'status' ] == 'success' ) {
        $this->sendNotification($transaction);
         }


        
        Logger::createLog($request['merchant_id'], '', 'Card payment made', request()->getContent());

        return response()->json([
            'transaction_id'=> $transaction_id,
            'unique_id'=> $request['request']['unique_id'],
            'message' => $request['request']['status'] == 'failed'? 'Sorry transaction failed': 'Payment was successful',
            'status' => $request['request']['status'] == 'failed'? 400: 200,
        ]);
    }

    private function sendNotification($transaction){
         $notification = new NotificationUtility;
        $to = $transaction->email;
        $from = 'google.com';
        $sub = "Payment Receipt";
        
        $body = '<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Payment</title>
                    </head>
                    <body>
                        <h4>This email is to acknowledge that your payment was successful</h4>
                        <p>Amount paid: GHS'.$transaction->amount.'</p>
                        <p>Thank you</p>
                    </body>
                    </html>';
                    
        $sms = $notification->send_email($to, $from, $sub, $body);
    }


    private function callCallback($merchant, $transaction, $request){
        $url = $merchant->callback;
        // return response()->json( csrf_token() );

        $fields = [
            'transaction_id' => $transaction->transaction_id,
            'unique_id' => $request['request']['unique_id'],
            'amount' => $request['request']['amount'],
            'status' => $request[ 'request' ][ 'status' ]
        ];

        $fields_string = http_build_query( $fields );

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$request['private_key'],
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

        Logger::createLog($request['merchant_id'], '', 'Callback made to '.$url, request()->getContent(),  $response);

        return true;
    }
    
}
<?php

namespace App\Utility;

// require_once $_SERVER['DOCUMENT_ROOT']. "/vendor/autoload.php";
use Mailgun\Mailgun;
use \App\Utility\Logger;


class NotificationUtility{

    private $sms_sender_id;
    private $sms_key;
    private $mailgun_key;
    


    function __construct(){
        $this->sms_sender_id = \env('SMS_SENDER_ID');
        $this->sms_key = \env('SMS_KEY');
        $this->mailgun_key = \env('MAILGUN_KEY');
    }


    public function send_otp($phone, $msg){
        
        
        $url = "https://apps.hawksms.com/smsapi?key=".$this->sms_key."&to=".$phone."&msg=".urlencode($msg)."&sender_id=".$this->sms_sender_id;
        
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
                    'Content-Type: application/json '
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
            }
    }


     public function send_email($to, $from, $subject, $body)
    {
        $attachments = array();
        
        try{
            $mg = Mailgun::create($this->mailgun_key); // For US servers

        $result = $mg->messages()->send(env('MAILGUN_DOMAIN'), [
        'from'    => env('MAILGUN_FROM'),
        'to'      => $to,
        'subject' => $subject,
        'html'    => $body,
        'attachment' => $attachments
        ]);
        }catch(Exception $e){
         Logger::logError( json_encode( $e->getTrace() ), json_encode( $e->getMessage() ), '',  '' );
        }

        return true;
    }
}
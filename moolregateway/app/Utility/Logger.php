<?php

namespace App\Utility;


use \App\Models\GatewayLog;
use \App\Models\ErrorLog;

class Logger{
    
    public static function createLog($merchant_id, $route, $activity, $payload, $others=null){
        $log = new GatewayLog;
        $log->merchant_id = $merchant_id;
        $log->route = $route;
        $log->activity=$activity;
        $log->payload = $payload;
        $log->others = $others;
        $log->save();
    }

    public static function logError($trace, $message, $route, $payload){
        $log = new ErrorLog;
        $log->trace = $trace;
        $log->message = $message;
        $log->route = $route;
        $log->payload = $payload;

        $log->save();
    }
}
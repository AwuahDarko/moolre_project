<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Models\Merchant;



class GatewayAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');
        if(!isset($header)){
            return response()->json(['message' => 'Unauthorized gateway access identified'], 401);
        }

        $list = explode(' ', $header);

        if(count($list) < 2){
            return response()->json(['message' => 'Fatal error occurred in gateway, please try again'], 500);
        }

        $merchant = Merchant::where(['private_key' => $list[1]])->first();
        // return response()->json($merchant);

        if(empty($merchant)){
            return response()->json(['message' => 'You do not have access to gateway'], 403);
        }

        $request->request->add(['merchant_id' => $merchant->id]);

        return $next($request);
    }
}
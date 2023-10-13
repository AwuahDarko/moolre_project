<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Merchant;

class ApikeyAuth
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
        $requestHost = parse_url($request->headers->get('origin'),  PHP_URL_HOST);

        if(!isset($header)){
            return response()->json(['message' => 'Unauthorized access identified'], 401);
        }

        $list = explode(' ', $header);

        if(count($list) < 2){
            return response()->json(['message' => 'Fatal error occurred, please try again'], 500);
        }

        $merchant = Merchant::where(['public_key' => $list[1]])->first();

        $request->request->add(['public_key' => $list[1]]);

        // return response()->json($requestHost.' '.$merchant->website);

        if(empty($merchant) or $requestHost == null or $requestHost !== $merchant->website){
            // return response()->json(['message' => 'You do not have access to this service'], 403);
        }

        $request->request->add(['private_key' => trim($merchant->private_key)]);
        $request->request->add(['merchant_id' => trim($merchant->id)]);
        return $next($request);
    }
}
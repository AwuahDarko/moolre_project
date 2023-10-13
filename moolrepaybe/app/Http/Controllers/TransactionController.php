<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::paginate(2);
        return response()->json($transactions);

        // $requestHost = parse_url($request->headers->get('origin'),  PHP_URL_HOST);
        // return response()->json(['wer'=>$requestHost ]);
    }


    public function store(Request $request){
        // TODO ======= perform sth ====

        $transactions = new Transaction;
        $transactions->transaction_id = $request->transaction_id;
        $transactions->unique_id = $request->unique_id;
        $transactions->merchant_id = $request->merchant_id;
        $transactions->amount = $request->amount;
        $transactions->payment_mode = $request->payment_mode;
        $transactions->status = 'success';
        $transactions->save();

        return response()->json([
            'message' => 'Trabsaction was success'
        ], 200);
    }


    public function show($id){
        $transactions = Transaction::find($id);
        if(!empty( $transactions)){
            return response()->json( $transactions);
        }

        return response()->json([
            'message' => 'Transaction not found'
        ], 404);
    }
}

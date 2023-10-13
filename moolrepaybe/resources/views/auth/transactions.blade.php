@extends('auth.layouts')

@section('content')
    <div class="dashboard-container" style="background-image: url({{app('url')->asset('public/assets/imh.jpg')}}); background-size: cover;background-position: center;background-repeat: no-repeat; padding: 10px;">
       
        <div class="inner-container">
 <div class="board-header card padding-3">
            <img src="{{ app('url')->asset('public/assets/Mobile.svg') }}" alt="">
            <div style="display: flex; align-items: center; gap: 5px;">
                <h4 class="no-margin">Merchant Dashboard</h4>
                <a class="logout" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>
        <div class="board-body">
            <div class="sidebar ">
                <ul class="card padding-5 no-margin">
                    <a class="li-item" href="{{ route('dashboard') }}">View Settings</a>
                    <a class="li-item" href="{{ route('transactions') }}">View Transactions</a>
                </ul>
            </div>
            <div class="main-box">
                <div class="card padding-2 ">
                    <p class="bold-text">Here is all transactions received from you</p>
                    <hr>
                    <section class="devjack-merchant-container">

                        <div class="devjack-body devjack-card">
                            <div class="responsive-table">
                                <table class="devjack-table aiz-tables">
                                    <thead>
                                        <th>Date</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Amount</th>
                                        <th>Payment method</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $trans )
                                             <tr>
                                            <td> {{ date_format(date_create($trans->created_at), "D, d M Y")  }} </td>
                                            <td> {{ $trans->email }} </td>
                                            <td> {{ $trans->phone }} </td>
                                            <td> {{ $trans->amount }} </td>
                                            <td> {{ $trans->payment_mode }} </td>
                                            <td> {{ $trans->transaction_id }}</td>
                                            <td> {{ $trans->status }} </td>
                                        </tr>
                                        @endforeach
                                       

                                    </tbody>
                                </table>
                            </div>
                            <div style="margin-top: 10px"> {{ $transactions->links() }} </div>

                        </div>
                    </section>

                </div>
            </div>
        </div>
        </div>
       


        

        <div style="height: 100px"></div>
    </div>
@endsection

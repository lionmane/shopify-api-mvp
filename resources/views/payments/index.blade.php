@extends('layouts.frontend')
@section('head')
    <title>Products Index</title>
@stop
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Charge ID</th>
                        <th>Customer ID</th>
                        <th>Cart ID</th>
                        <th>Currency</th>
                        <th>Total</th>
                        <th>Receipt Link</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->charge_id }}</td>
                            <td>{{ $payment->customer_id }}</td>
                            <td>{{ $payment->cart_id }}</td>
                            <td>{{ strtoupper($payment->currency) }}</td>
                            <td>{{ $payment->total }}</td>
                            <td><a target="_blank" href="{{ $payment->receipt_url }}">View receipt</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

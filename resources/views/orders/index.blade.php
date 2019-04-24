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
                        <th>#</th>
                        <th>ID</th>
                        <th>Order Name</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $index => $order)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $order['id'] }}</td>
                            <td>{{ $order['name'] }}</td>
                            <td>{{ $order['status'] }}</td>
                            <td>{{ $order['total_price'] }}</td>
                            <td>{{ $order['customer']['first_name'] }}</td>
                            <td>{{ $order['customer']['last_name'] }}</td>
                            <td>{{ $order['customer']['email'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

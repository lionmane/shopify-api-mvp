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
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Last Order</th>
                        <th>Total Spent</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $index => $customer)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $customer['id'] }}</td>
                            <td>{{ $customer['first_name'] }}</td>
                            <td>{{ $customer['last_name'] }}</td>
                            <td>{{ $customer['email'] }}</td>
                            <td>{{ $customer['last_order_name'] }}</td>
                            <td>{{ $customer['total_spent'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

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
                        <th width="10%">Vendor</th>
                        <th width="50%">Name</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Image</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product['variant_id'] }}</td>
                            <td>{{ $product['vendor'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['variant_name'] }}</td>
                            <td>{{ $product['price'] }}</td>
                            <td>
                                <img style="max-height:84px;" src="{{ $product['image'] }}" alt="$name - $variant_name">
                            </td>
                            <td style="vertical-align: middle;">
                                <a href="#"
                                   class="add-to-cart"
                                   data-product-id="{{ $product['variant_id'] }}"
                                   style="text-decoration: none;"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('modals.choose-cart', ['carts' => $carts])
@stop
@section('scripts')
    <script type="text/javascript">
        $(function() {
            $('a.add-to-cart').click(function(e) {
                e.preventDefault();
                $('a.add-to-cart').removeAttr('data-active');
                $(this).attr('data-active', 'true');

                $('#choose-cart-modal').modal();
            })
        });
    </script>
@stop

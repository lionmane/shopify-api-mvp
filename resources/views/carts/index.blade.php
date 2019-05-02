@extends('layouts.frontend')
@section('head')
    <title>Carts Index</title>
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
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($carts as $index => $cart)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $cart->id }}</td>
                            <td>{{ $cart->customer_first_name }}</td>
                            <td>{{ $cart->customer_last_name }}</td>
                            <td>{{ $cart->customer_email }}</td>
                            <td>{{ ucfirst($cart->status) }}</td>
                            <td>{{ $cart->total() }}</td>
                            <td>
                                <a href="#"
                                   title="View cart items"
                                   data-cart-id="{{ $cart->id }}"
                                   class="view-cart-items">
                                    <i class="glyphicon glyphicon-info-sign"></i>
                                </a>

                                @if ($cart->status == 'open')
                                    <a href="#"
                                       title="Checkout this cart"
                                       data-cart-id="{{ $cart->id }}"
                                       class="checkout-cart">
                                        <i class="glyphicon glyphicon-credit-card"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('modals.cart-items')
    @include('modals.checkout')
@stop
@section('scripts')
    <script type="text/javascript">
        function open_items_modal(cart_id) {
            var modal = $('#view-cart-modal');
            $('.cart-items tbody tr', modal).remove();
            $.ajax({
                url: '/cart/' + cart_id + '/items',
                success: function (response) {
                    show_items(response, modal);
                    modal.modal();
                }
            });
        }

        function open_checkout_modal(cart_id) {
            var modal = $('#checkout-modal');
            $('.cart-items tbody tr', modal).remove();
            $.ajax({
                url: '/cart/' + cart_id + '/info',
                success: function (response) {
                    $('.cart-id', modal).html(response.id);
                    $('.customer', modal).html(response.customer);
                    $('.total', modal).html(response.total);
                    $('.actual_total', modal).html(response.total);
                    
                    $('[name=first_name]').val(response.cart.customer_first_name);
                    $('[name=last_name]').val(response.cart.customer_last_name);

                    $('form').attr('action', 'charge/' + cart_id);
                    modal.modal();
                }
            });
        }

        function show_items(items, modal) {
            var modal = $(modal);
            $('table tbody tr', modal).remove();
            var quantity = 0;
            var total = 0;
            for (var i = 0; i < items.length; ++i) {
                console.log(items[i]);
                quantity += items[i].quantity;
                total += items[i].total_price;
                $('table tbody', modal).append(tr(items[i]));
            }

            var empty_item = {variant_id: '&nbsp;', product_name: '', unit_price: '', quantity: '', total_price: ''};
            var total_item = {
                variant_id: '',
                product_name: '',
                unit_price: '',
                quantity: '<strong>TOTAL</strong>',
                total_price: total
            };
            $('table tbody', modal).append(tr(empty_item));
            $('table tbody', modal).append(tr(total_item));
        }

        function tr(item) {
            var tr = $('<tr>');
            $('<td>').html(item.variant_id).appendTo(tr);
            $('<td>').html(item.product_name).appendTo(tr);
            $('<td>').html(item.unit_price).appendTo(tr);
            $('<td>').html(item.quantity).appendTo(tr);
            $('<td>').html(item.total_price).appendTo(tr);
            return tr;
        }

        $(function () {
            $('a.view-cart-items').click(function (e) {
                e.preventDefault();
                open_items_modal($(this).data('cart-id'));
            });

            $('a.checkout-cart').click(function (e) {
                e.preventDefault();
                $('a.checkout-cart').removeAttr('data-active');
                $(this).attr('data-active', true);
                open_checkout_modal($(this).data('cart-id'));
            });

            $('.shipping_radio').click(function(){
                var radioValue = $("input[name='rate']:checked"). val();
                var oldtotal = $('.actual_total').html();
                var newTotal = parseFloat(oldtotal) + parseFloat(radioValue.replace("$",""));
                 $('.total').html(newTotal);
            })
            // Create a Stripe client.
            var stripe = Stripe('{{ env('STRIPE_KEY') }}');
            window.stripe = stripe;

            // Create an instance of Elements.
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {style: style});
            window.card = card;

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        });
    </script>
@stop

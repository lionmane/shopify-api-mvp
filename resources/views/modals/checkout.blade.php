<div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Checkout Cart</h4>
            </div>
            <div class="modal-body">
                <form action="/charge" method="post" id="payment-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div style="padding: 4px; background-color: gainsboro; text-align: center;">
                                <h1 class="cart-id"></h1>
                                <br>
                                <label for="">Cart ID</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 4px; background-color: gainsboro; text-align: center;">
                                <h1 class="customer"></h1>
                                <br>
                                <label for="">Customer</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="padding: 4px; background-color: gainsboro; text-align: center;">
                                <h1 class="total"></h1>
                                <br>
                                <label for="">Total</label>
                            </div>
                        </div>
                    </div>

                    <br>
                    <em>IMPORTANT: Once this Draft Order is created, the cart will become closed and no longer
                        editable.</em>
                    <br>

                    <hr>

                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Order Name</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Type in an order name to help identify this draft"/>
                        </div>
                        <div class="col-md-6">
                            <label for="">Notes</label>
                            <textarea name="notes" id="" rows="3" class="form-control"
                                      placeholder="Notes for this order"></textarea>
                        </div>
                    </div>

                    <br>
                    <br>

                    <div class="row">
                        {{-- Aackas: Place checkout fields here--}}
                        <div class="col-md-5">
                            <label for="">First name</label>
                            <input class="form-control" type="text" name="first_name">
                        </div>
                        <div class="col-md-2">
                            <label for="">Middle</label>
                            <input class="form-control" type="text" name="middle_initial">
                        </div>
                        <div class="col-md-5">
                            <label for="">Last name</label>
                            <input class="form-control" type="text" name="last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="">Email</label>
                            <input class="form-control" type="text" name="email">
                        </div>
                        <div class="col-md-5">
                            <label for="">Shipping Address</label>
                            <input class="form-control" type="text" name="shipping_address">
                        </div>

                    </div>
                    <br>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="card-element">
                                Credit or debit card
                            </label>
                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>

                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary checkout" data-dismiss="modal">Create Order</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    // Handle form submission.
    //    var form = document.getElementById('payment-form');
    //    form.addEventListener('submit', function (event) {
    //        event.preventDefault();
    //
    //        stripe.createToken(card).then(function (result) {
    //            if (result.error) {
    //                // Inform the user if there was an error.
    //                var errorElement = document.getElementById('card-errors');
    //                errorElement.textContent = result.error.message;
    //            } else {
    //                // Send the token to your server.
    //                stripeTokenHandler(result.token);
    //            }
    //        });
    //    });

    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = $('#payment-form');
        $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'stripeToken')
            .attr('value', token.id)
            .appendTo(form);

        // Submit the form
        var fd = new FormData();
        $('input,textarea,select', form).each(function (index, input) {
            fd.append($(input).attr('name'), $(input).val());
        });
        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', form.attr('action'), true );
        xhr.overrideMimeType('application/json');
        xhr.responseType = 'json';
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 /* DONE */) {
                responseHandler(xhr.response);
            }
        };
        xhr.send( fd );
    }

    function responseHandler(response) {
        alert(response.message);
    }

    $('#checkout-modal .checkout').click(function (e) {
        e.preventDefault();
        var modal = $('#checkout-modal');
        var cart_id = $('a.checkout-cart[data-active=true]').data('cart-id');

        $('form', modal).attr('action', '/charge/' + cart_id);
        $('form', modal).unbind('submit').submit(function(e) {
            e.preventDefault();

            // Stripe is an external variable
            window.stripe.createToken(window.card).then(function (result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });
        $('form', modal).submit();
    });

</script>
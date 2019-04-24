<div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Checkout Cart</h4>
            </div>
            <div class="modal-body">
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
                <br>
                <h4>Checkout data</h4>
                <br>

                <form action="/charge" method="post" id="payment-form">
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
                    <br><br>
                    <button class="btn btn-primary btn-md">Submit Payment</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
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
                

<!-- Bootstrap inspired Braintree Hosted Fields example -->
<div class="panel panel-default bootstrap-basic tab-content">
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#stripe">Stripe Payment</a></li>
  <li><a data-toggle="tab" href="#braintree">Braintree</a></li>
</ul>
<div id="braintree" class="tab-pane fade">
  <form class="panel-body" id="payment-form1" >
    <div class="row">
      <div class="form-group col-sm-6">
        <label class="control-label">First Name</label>
        <!--  Hosted Fields div container -->
        <input class="form-control" type="text" name="first_name">
      </div>
      <div class="form-group col-sm-6">
        <label class="control-label">Last Name</label>
        <!--  Hosted Fields div container -->
        <input class="form-control" type="text" name="last_name">
      </div>
    </div>
    <div class="row">
    <div class="form-group col-sm-6">
        <label class="control-label">Email</label>
        <!--  Hosted Fields div container -->
        <input class="form-control" type="text" name="email">
      </div>
  </div>
  <div id="dropin-container"></div>
    <button value="submit" id="submit-button" class="btn btn-success btn-lg center-block">Pay with <span id="card-type">Card</span></button>
  </form>
</div>
<div id="stripe" class="tab-pane fade in active">
                <form action="/charge" method="post" class="panel-body" id="payment-form">
                    <div class="row">
                        {{-- Aackas: Place checkout fields here--}}
                        <div class="form-group col-md-5">
                            <label for="">First name</label>
                            <input class="form-control" type="text" name="first_name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Middle</label>
                            <input class="form-control" type="text" name="middle_initial">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="">Last name</label>
                            <input class="form-control" type="text" name="last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="">Email</label>
                            <input class="form-control" type="text" name="email">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="">Shipping Address</label>
                            <input class="form-control" type="text" name="shipping_address">
                        </div>
                        
                    </div>
                    <br>
                    <br>

                    <div class="row">
                        <div class="form-group col-md-12">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
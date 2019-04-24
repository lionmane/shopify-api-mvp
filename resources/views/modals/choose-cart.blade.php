<div class="modal fade" id="choose-cart-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Choose Cart</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Choose an open Cart</label>
                        <select class="form-control" name="open-cart" id="">
                            <option value=""></option>
                            @foreach($carts as $cart)
                                <option value="{{ $cart->id }}">{{ $cart->customer_first_name }} {{ $cart->customer_last_name }} [ {{ \Carbon\Carbon::parse($cart->created_at)->toFormattedDateString() }} ]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Create a new Cart</label>
                        <select class="form-control" name="new-cart">
                            <option value=""></option>
                            @foreach($customers as $customer)
                                @if (strlen($customer['first_name']))
                                    <option value="{{ $customer['id'] }}">{{ $customer['first_name'] }} {{ $customer['last_name'] }} ({{ $customer['email'] }})</option>
                                @else
                                    <option value="{{ $customer['id'] }}">{{ $customer['email'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button class="btn btn-default create-cart">Create</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Quantity</label>
                        <input name="quantity" type="number" class="form-control" value="1" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add-to-cart">OK</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('#choose-cart-modal .create-cart').click(function (e) {
            e.preventDefault();
            var new_cart = $('select[name=new-cart]').val();
            if (!new_cart) {
                alert("ERROR: please select a Customer to create Cart for");
                return;
            }
            $.ajax({
                url: '/cart/' + new_cart,
                method: 'POST',
                success: function(response) {
                    $('<option>')
                        .attr('value', response.cart_id)
                        .text(response.cart_name)
                        .appendTo($('select[name=open-cart]'));

                    $('select[name=open-cart]').val(response.cart_id);
                }
            })
        });

        $('#choose-cart-modal .add-to-cart').click(function(e) {
            e.preventDefault();
            var cart_id = $('#choose-cart-modal [name=open-cart]').val();
            if (!cart_id) {
                alert("Please select an open cart");
                return;
            }
            var product_id = $('a.add-to-cart[data-active=true]').attr('data-product-id');
            console.log(product_id);
            var quantity = $('#choose-cart-modal [name=quantity]').val() || 1;
            $.ajax({
                url: 'cart/' + cart_id + '/product/' + product_id + '/quantity/' + quantity,
                method: 'POST',
                success: function(response) {
                    alert('Product added to cart successfully');
                    $('#choose-cart-modal').modal('hide');
                },
                error: function(xhr, statusCode, errorThrown) {
                    alert('Sorry, something went wrong');
                }
            })
        });
    });
</script>
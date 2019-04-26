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
                <em>IMPORTANT: Once this Draft Order is created, the cart will become closed and no longer editable.</em>
                <br>

                <hr>

                <br>

                <div class="row">
                    <div class="col-md-6">
                        <label for="">Order Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Type in an order name to help identify this draft" />
                    </div>
                    <div class="col-md-6">
                        <label for="">Notes</label>
                        <textarea name="notes" id="" rows="3" class="form-control" placeholder="Notes for this order"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary checkout" data-dismiss="modal">Create Order</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $('#checkout-modal .checkout').click(function(e) {
        e.preventDefault();
        var modal = $('#checkout-modal');
        var cart_id = $('a.checkout-cart[data-active=true]').data('cart-id');
        var name = $('input[name=name]', modal).val();
        var notes = $('input[name=notes]', modal).val();
        $.ajax({
            url: '/cart/' + cart_id + '/checkout',
            method: 'POST',
            data: {
                name: name,
                notes: notes
            },
            success: function(response) {
                alert(response.message);
                console.log(response);
                modal.modal('hide');
            },
            error: function(xhr, statusCode, errorThrown) {
                alert('Sorry, something went wrong');
            }
        })
    });

</script>
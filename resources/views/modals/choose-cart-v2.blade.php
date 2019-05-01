<div class="modal fade" id="choose-cart-modal-v2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add to Cart (v2)</h4>
            </div>
            <div class="modal-body">
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
        $('#choose-cart-modal-v2 .add-to-cart').click(function(e) {
            e.preventDefault();
            var product_id = $('a.add-to-cart[data-active=true]').attr('data-product-id');
            var quantity = $('#choose-cart-modal-v2 [name=quantity]').val() || 1;
            $.ajax({
                url: 'cart/add-product/' + product_id + '/quantity/' + quantity,
                method: 'POST',
                success: function(response) {
                    alert('Product added to cart successfully');
                    $('#choose-cart-modal-v2').modal('hide');
                },
                error: function(xhr, statusCode, errorThrown) {
                    alert('Sorry, something went wrong');
                }
            })
        });
    });
</script>
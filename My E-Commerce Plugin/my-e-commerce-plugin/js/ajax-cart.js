jQuery(document).ready(function($) {
    $('.add-to-cart-button').click(function(e) {
        e.preventDefault();
        var product_id = $(this).data('product-id');

        $.ajax({
            type: 'POST',
            url: ajax_cart_params.ajax_url,
            data: {
                action: 'add_to_cart',
                product_id: product_id
            },
            success: function(response) {
                alert('Product added to cart!');
                // Optionally update cart display here
            }
        });
    });
});

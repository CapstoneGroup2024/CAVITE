
    $(document).ready(function () {
        function updateTotalPrice() {
            var subtotal = 0;

            $('.cart_data').each(function() {
                var qty = parseInt($(this).find('.input-qty').val(), 10);
                var price = parseFloat($(this).find('.iprice').text().replace('₱', ''));
                var total = qty * price;
                total = Math.round(total);
                subtotal += total;
                $(this).find('.itotal').text('₱' + total);
            });

            var deliveryFee = 10;
            var grandTotal = subtotal + deliveryFee;

            $('.subtotal-price').text('₱' + subtotal.toFixed(2));
            $('.delivery-fee').text('₱' + deliveryFee.toFixed(2));
            $('.grand-total').text('₱' + grandTotal.toFixed(2));
        }

        function updateQuantity(cartId, qty) {
            $.ajax({
                url: 'update_quantity.php',
                method: 'POST',
                data: { cart_id: cartId, quantity: qty },
                success: function(response) {
                    updateTotalPrice();
                }
            });
        }

        $('.increment-btn').click(function (e) {
            var qtyInput = $(this).siblings('.input-qty');
            var qty = parseInt(qtyInput.val(), 10);
            qty = isNaN(qty) ? 0 : qty;
            var cartId = $(this).closest('.cart_data').find('input[name="cart_id"]').val();

            if (qty < 100) {
                qty++;
            } else {
                qty = 1;
            }
            qtyInput.val(qty);
            updateQuantity(cartId, qty);
        });

        $('.decrement-btn').click(function (e) {
            e.preventDefault();
            var qtyInput = $(this).siblings('.input-qty');
            var qty = parseInt(qtyInput.val(), 10);
            qty = isNaN(qty) ? 0 : qty;
            var cartId = $(this).closest('.cart_data').find('input[name="cart_id"]').val();

            if (qty > 1) {
                qty--;
            }
            qtyInput.val(qty);
            updateQuantity(cartId, qty);
        });

        $('.input-qty').on('change', function() {
            var qty = parseInt($(this).val(), 10);
            var cartId = $(this).closest('.cart_data').find('input[name="cart_id"]').val();
            updateQuantity(cartId, qty);
        });

        updateTotalPrice(); // Initial call to set values
    });


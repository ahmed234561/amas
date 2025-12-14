"use strict";

// Add to cart functionality
function addToCart(product_id, variant = null, force_add = false, el = null) {
    if (force_add == false) {
        $('#addToCart-modal-body').html(null);
    }

    if (el) {
        $('#addToCart-modal-body').html('<div class="c-preloader text-center absolute-center"><i class="las la-spinner la-spin la-3x opacity-70"></i></div>');
    }

    // Get price info from the product's price div
    const priceDiv = document.querySelector(`.product-price-${product_id}`);
    const specialPrice = priceDiv ? priceDiv.dataset.specialPrice : '';
    const regularPrice = priceDiv ? priceDiv.dataset.regularPrice : '';

    // Determine which price to use
    const price = specialPrice || regularPrice;

    console.log('Adding to cart:', {
        product_id,
        price,
        specialPrice,
        regularPrice
    });

    $.ajax({
        type: "POST",
        url: AIZ.data.appUrl + '/cart/addToCart',
        data: {
            id: product_id,
            variant: variant,
            quantity: 1,
            force_add: force_add,
            price: price,  // Send the selected price
            _token: AIZ.data.csrf
        },
        success: function (response) {
            if (response.status == 0) {
                AIZ.plugins.notify('warning', response.message);
                return false;
            }
            updateNavCart(response.nav_cart_view, response.cart_count);
            $('#addToCart-modal-body').html(response.modal_view);

            // Successfully added the product to cart
            AIZ.plugins.notify('success', response.message);

            // Hide the success message after 5 seconds
            setTimeout(function() {
                $('#cart_added_msg').fadeOut();
            }, 5000);
        }
    });
}

// Update cart nav
function updateNavCart(view, count) {
    $('.cart-count').html(count);
    $('#cart_items').html(view);
}

// Cart item quantity buttons
function cartQuantityInitialize() {
    $('.btn-number').click(function (e) {
        e.preventDefault();

        const fieldName = $(this).attr('data-field');
        const type = $(this).attr('data-type');
        const input = $("input[name='" + fieldName + "']");
        const currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (type == 'minus') {
                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }
            } else if (type == 'plus') {
                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }
            }
        } else {
            input.val(0);
        }
    });

    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });

    $('.input-number').change(function () {
        const minValue = parseInt($(this).attr('min'));
        const maxValue = parseInt($(this).attr('max'));
        const valueCurrent = parseInt($(this).val());

        const name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled');
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled');
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }
    });

    $('.input-number').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}

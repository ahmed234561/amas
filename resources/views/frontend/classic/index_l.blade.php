@extends('frontend.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Search Box -->
            <div class="mb-4">
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control"
                           placeholder="{{ __('Search products...') }}"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" id="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="search-results mt-2"></div>
            </div>

            <!-- Products Table -->
            <div id="products-table">
                @include('frontend.classic.partials.search_products', ['products' => $products])
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .table {
        font-size: 14px;
    }
    .table th {
        text-align: center;
    }
    .btn-sm {
        font-size: 12px;
        padding: 5px 10px;
    }
    .quantity-input {
        width: 70px;
        text-align: center;
    }
    .search-results {
        min-height: 100px;
    }
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 10px;
    }
</style>
@endpush

@section('script')
<script>
$(document).ready(function() {
    // Quantity controls
    $(".increment").click(function() {
        let input = $(this).closest('.quantity-wrapper').find(".quantity-input");
        let max = parseInt(input.attr("max")) || 100;
        let value = parseInt(input.val()) || 1;
        if (value < max) input.val(value + 1).trigger('change');
    });

    $(".decrement").click(function() {
        let input = $(this).closest('.quantity-wrapper').find(".quantity-input");
        let min = parseInt(input.attr("min")) || 1;
        let value = parseInt(input.val()) || 1;
        if (value > min) input.val(value - 1).trigger('change');
    });

    // Search functionality
    let searchTimer;
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(performSearch, 500);
    });

    $('#search-button').click(performSearch);

    function performSearch() {
        const searchTerm = $('#search-input').val().trim();

        if (searchTerm.length > 0) {
            showLoading();

            $.ajax({
                url: "{{ route('home') }}",
                type: "GET",
                data: { search: searchTerm },
                success: function(response) {
                    if (response.status === 'empty') {
                        $('.search-results').html('<div class="alert alert-info">' + response.message + '</div>');
                    } else if (response.status === 'success') {
                        $('#products-table').html(response.html);
                    }
                },
                error: function(xhr) {
                    $('.search-results').html('<div class="alert alert-danger">Error occurred while searching</div>');
                },
                complete: function() {
                    hideLoading();
                }
            });
        } else {
            // If search is empty, reload initial products
            location.href = "{{ route('home') }}";
        }
    }

    function showLoading() {
        $('.search-results').html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    }

    function hideLoading() {
        $('.loading-spinner').remove();
    }


    $('#search').on('keyup', function(){

    ajaxSearch();
    });

    $('#search').on('focus', function(){
    ajaxSearch();
    });

    function ajaxSearch(){
    var searchKey = $('#search').val();

    if(searchKey.length > 0){
        $('.search-preloader').removeClass('d-none');

        $.ajax({
            url: "{{ route('home') }}",
            type: "GET",
            data: {
                search: searchKey
            },
            success: function(data) {
                $('.search-preloader').addClass('d-none');

                if (data == '0') {
                    $('#search-content').html('<p class="text-center">Sorry, no results found.</p>');
                } else {
                    $('#search-content').html(data); // Load new search results
                }
            }
        });
    } else {
        $('#search-content').html(''); // Clear results when input is empty
    }
    }
});
    // إضافة المنتج إلى السلة
//     $(".add-to-cart").click(function() {
//         let productId = $(this).data("id"); // الحصول على معرف المنتج
//         let quantity = $(this).closest("tr").find(".quantity-input").val(); // الحصول على الكمية

//         // إرسال طلب AJAX
//         $.ajax({
//             url: "{{ route('cart.addToCart') }}", // رابط إضافة المنتج إلى السلة
//             method: "POST",
//             data: {
//                 _token: "{{ csrf_token() }}", // رمز CSRF
//                 id: productId, // معرف المنتج
//                 quantity: quantity // الكمية
//             },
//             success: function(response) {
//                 if (response.status === 'success') {
//                     alert("تمت إضافة المنتج إلى السلة بنجاح!");
//                 } else {
//                     alert("حدث خطأ أثناء إضافة المنتج إلى السلة.");
//                 }
//             },
//             error: function(xhr) {
//                 alert("حدث خطأ أثناء الاتصال بالخادم.");
//             }
//         });
//     });
// });

// عرض تفاصيل المنتج في المودال
</script>
<script>

    // Add to cart functionality
    // function addToCart2(productId) {
    //     @if (Auth::check() && Auth::user()->user_type != 'customer')
    //         AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
    //         return false;
    //     @endif

    //     var row = $('#product-row-' + productId);
    //     var quantityInput = row.find('.quantity-input');
    //     var pointsType = row.find('.target_points');
    //     var client_id = row.find('.client_id');
    //     var quantity = parseInt(quantityInput.val()) || 1;

    //     // Get special price if it exists
    //     @if(Auth::check())
    //         var specialPrice = row.find('.special-price').data('price');
    //     console.log('Special Price:', specialPrice);
    //     @endif

    //     if(isNaN(quantity) || quantity < 1) {
    //         AIZ.plugins.notify('warning', "{{ translate('Please enter a valid quantity') }}");
    //         return false;
    //     }

    //     $('.c-preloader').show();

    //     $.ajax({
    //         type: "POST",
    //         url: '{{ route('cart.addToCart') }}',
    //         data: {
    //             _token: '{{ csrf_token() }}',
    //             id: productId,
    //             quantity: quantity,
    //             client_id: client_id.val(),
    //             target_points: pointsType.val(),
    //             special_price: $('input[name="special_price_' + productId + '"]').val()

    //         },
    //         success: function(data) {
    //             $('.c-preloader').hide();
    //             if(data.status == 1) {
    //                 if(data.modal_view) {
    //                     $('#addToCart-modal-body').html(data.modal_view);
    //                 }
    //                 if(data.nav_cart_view) {
    //                     updateNavCart(data.nav_cart_view, data.cart_count);
    //                 }
    //                 AIZ.plugins.notify('success', "{{ translate('Product added to cart successfully') }}");
    //             } else {
    //                 if(data.modal_view) {
    //                     $('#addToCart-modal-body').html(data.modal_view);
    //                     $('#addToCart').modal('show');
    //                 }
    //                 AIZ.plugins.notify('danger', data.message || "{{ translate('Something went wrong') }}");
    //             }
    //         },
    //         error: function(xhr) {
    //             $('.c-preloader').hide();
    //             var errorMessage = xhr.responseJSON && xhr.responseJSON.message
    //                 ? xhr.responseJSON.message
    //                 : "{{ translate('Something went wrong') }}";
    //             AIZ.plugins.notify('danger', errorMessage);
    //         }
    //     });
    // }
  function addToCart2(productId) {
    @if (Auth::check() && Auth::user()->user_type != 'customer')
        // AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
        // return false;
    @endif

    var row = $('#product-row-' + productId);
    var quantityInput = row.find('.quantity-input');
    var pointsType = row.find('.target_points');
    var client_id = row.find('.client_id');
    var quantity = parseInt(quantityInput.val()) || 1;

    if(isNaN(quantity) || quantity < 1) {
        AIZ.plugins.notify('warning', "{{ translate('Please enter a valid quantity') }}");
        return false;
    }

    $('.c-preloader').show();

    $.ajax({
        type: "POST",
        url: '{{ route('cart.addToCart') }}',
        data: {
            _token: '{{ csrf_token() }}',
            id: productId,
            quantity: quantity,
            client_id: client_id.val(),
            target_points: pointsType.val()
            // لا ترسل special_price من هنا
        },
        success: function(data) {
            $('.c-preloader').hide();
            if(data.status == 1) {
                if(data.modal_view) {
                    $('#addToCart-modal-body').html(data.modal_view);
                }
                if(data.nav_cart_view) {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                }
                AIZ.plugins.notify('success', "{{ translate('Product added to cart successfully') }}");
            } else {
                if(data.modal_view) {
                    $('#addToCart-modal-body').html(data.modal_view);
                    $('#addToCart').modal('show');
                }
                AIZ.plugins.notify('danger', data.message || "{{ translate('Something went wrong') }}");
            }
        },
        error: function(xhr) {
            $('.c-preloader').hide();
            var errorMessage = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : "{{ translate('Something went wrong') }}";
            AIZ.plugins.notify('danger', errorMessage);
        }
    });
}


</script>
@endsection

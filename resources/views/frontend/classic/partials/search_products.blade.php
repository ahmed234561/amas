
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 15%;">الكمية</th>
                        <th class="text-center" style="width: 20%;">اسم المنتج</th>
                        <th class="text-center" style="width: 15%;">السعر</th>
                        <th class="text-center" style="width: 10%;">النقاط السعودي</th>
                        <th class="text-center" style="width: 10%;">النقاط الماليزي</th>
                        <th class="text-center" style="width: 15%;">تفاصيل</th>
                        <th class="text-center" style="width: 15%;">إضافة للسلة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr id="product-row-{{ $product->id }}">
                        <!-- الكمية -->
                        <td class="text-center">
                            <div class="input-group quantity-wrapper">
                                <button class="btn btn-sm btn-danger decrement">-</button>
                                <input type="text" style="max-width: 50px" class="form-control text-center quantity-input"
                                        value="1" min="1" readonly>
                                <button class="btn btn-sm btn-success increment">+</button>
                            </div>
                        </td>

                        <!-- اسم المنتج -->
                        <td>
                            <a href="{{ route('product', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </td>

                        <!-- السعر -->
                        <td class="text-primary fw-bold text-center">
                            {{ home_discounted_price($product) }}
                        </td>

                        <!-- نقاط المنتج -->
                        <td class="text-center text-muted">
                            {{ $product->earn_point  }}
                        </td>
                        <td class="text-center text-muted">
                            {{ $product->malaysian_points  }}
                        </td>
                        <!-- زر تفاصيل المنتج -->
                        <td class="text-center">
                            <button href="javascript:void(0)" class="btn btn-sm btn-success add-to-cart"
                                    onclick="showAddToCartModal({{ $product->id }})" data-id="{{ $product->id }}">
                                تفاصيل المنتج
                            </button>
                        </td>

                        <!-- زر إضافة إلى السلة -->
                        <td class="text-center">
                            @if (count(json_decode($product->choice_options)) == 0)
                            <button class="btn btn-sm btn-success add-to-cart"
                            @if (Auth::check()) onclick="addToCart2({{ $product->id }})" @else onclick="showLoginModal()" @endif>
                                إضافة للسلة
                            </button>
                            @else
                            <button href="javascript:void(0)" style="cursor: not-allowed" class="btn btn-sm btn-warning btn-disabled">
                                يجب تحديد الخيارات
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>




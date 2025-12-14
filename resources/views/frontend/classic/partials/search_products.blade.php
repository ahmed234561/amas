
<div class="row">
    @php
        $clients = \Illuminate\Support\Facades\DB::table('clients')->get();
    @endphp
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 15%;">الكمية</th>
                        <th class="text-center" style="width: 20%;">اسم المنتج</th>
                        <th class="text-center" style="width: 15%;">السعر</th>
                        @if (Auth::check() && Auth::user()->postal_code != '')
                        <th class="text-center" style="width: 10%;">النقاط السعودي</th>
                        <th class="text-center" style="width: 10%;">النقاط الماليزي</th>
                        @endif
                        <th class="text-center" style="width: 10%;">تفاصيل</th>
                         @if (Auth::check() && Auth::user()->postal_code != '')
                        <th class="text-center" style="width: 10%;">نوع النقاط</th>
                        <th class="text-center" style="width: 10%;">الزبون</th>
                        @endif
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
                            @php
                                $special_price = null;
                                if (Auth::check()) {
                                    $special_price = \App\Models\UserSpecialPrice::where('user_id', Auth::id())
                                        ->where('product_id', $product->id)
                                        ->where('special_price', '>', 0)
                                        ->first();
                                }

                                // Store the price in data attribute for JavaScript
                                $display_price = $special_price ? $special_price->special_price : $product->unit_price;
                            @endphp
                            <div class="product-price-{{ $product->id }}"
                                 data-special-price="{{ $special_price ? $special_price->special_price : '' }}"
                                 data-regular-price="{{ $product->unit_price }}">
                                @if ($special_price)
                                    <span class="text-success special-price">
                                        {{ single_price($special_price->special_price) }}
                                    </span>
                                    <del class="text-muted small">{{ home_base_price($product) }}</del>
                                @else
                                    <span class="regular-price">
                                        {{ home_discounted_price($product) }}
                                    </span>
                                @endif
                            </div>
                        </td>
 @if (Auth::check() && Auth::user()->postal_code != '')
                        <!-- نقاط المنتج -->
                        <td class="text-center text-muted">
                            {{ $product->earn_point  }}
                        </td>
                        <td class="text-center text-muted">
                            {{ $product->malaysian_points  }}
                        </td>
@endif
                        <!-- زر تفاصيل المنتج -->
                        <td class="text-center">
                            <button href="javascript:void(0)" class="btn btn-sm btn-success add-to-cart"
                                    onclick="showAddToCartModal({{ $product->id }})" data-id="{{ $product->id }}">
                                تفاصيل المنتج
                            </button>
                        </td>
                         @if (Auth::check() && Auth::user()->postal_code != '')
                        <td class="text-center">
                            <select name="target_points" class="form-control target_points">
                                <option value="saudi">نقاط سعودي</option>
                                <option value="malaysian">نقاط ماليزي</option>
                            </select>
                        </td>
                        @endif
                         @if (Auth::check() && Auth::user()->postal_code != '')
                        <td class="text-center">
                            <select name="client_id" class="form-control client_id">
                                <option value="" selected>انا شخصيا</option>
                                @foreach ($clients as $client)
                                @if (auth()->check() && auth()->user()->id == $client->user_id)
                                    <option value="{{ $client->id }}" >{{ $client->name }}</option>

                                @endif
                                @endforeach
                                </select>
                        </td>
                        @endif
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




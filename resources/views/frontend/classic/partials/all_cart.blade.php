<!-- checkout.blade.php -->

<div class="container">
    <!-- ترويسة الخطوات -->
    <div class="row gutters-5 my-4">
        @foreach(['cart', 'shipping', 'delivery', 'payment'] as $key => $step)
        <div class="col text-center">
            <div class="border p-2 {{ $current_step > $key+1 ? 'bg-success text-white' : '' }}">
                الخطوة {{ $key+1 }}: {{ translate(ucfirst($step)) }}
            </div>
        </div>
        @endforeach
    </div>

    <!-- محتوى الخطوات -->
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="current_step" value="{{ $current_step }}">

        @if($current_step == 1)
            <!-- الخطوة 1: عربة التسوق -->
            <div class="cart-step">
                @include('partials.cart_items')
                <button type="submit" class="btn btn-primary next-step">المتابعة إلى الشحن</button>
            </div>

        @elseif($current_step == 2)
            <!-- الخطوة 2: عناوين الشحن -->
            <div class="shipping-step">
                @include('partials.shipping_addresses')
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary prev-step">السابق</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-primary next-step">المتابعة إلى التوصيل</button>
                    </div>
                </div>
            </div>

        @elseif($current_step == 3)
            <!-- الخطوة 3: خيارات التوصيل -->
            <div class="delivery-step">
                @include('partials.delivery_options')
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary prev-step">السابق</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-primary next-step">المتابعة إلى الدفع</button>
                    </div>
                </div>
            </div>

        @elseif($current_step == 4)
            <!-- الخطوة 4: خيارات الدفع -->
            <div class="payment-step">
                @include('partials.payment_methods')
                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary prev-step">السابق</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-success submit-order">تأكيد الطلب</button>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // معالجة زر السابق
    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.getElementById('checkoutForm');
            form.current_step.value = parseInt(form.current_step.value) - 1;
            form.submit();
        });
    });
});
</script>
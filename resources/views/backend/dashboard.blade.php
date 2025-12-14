@extends('backend.layouts.app')

@section('content')
@if(auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
    <div class="">
        <div class="alert alert-info d-flex align-items-center">
            {{translate('Please Configure SMTP Setting to work all email sending functionality')}},
            <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif

@php
    $riyadh = json_decode(get_setting('shipping_prices_riyadh'), true) ?: [];
    $other = json_decode(get_setting('shipping_prices_other'), true) ?: [];
@endphp

<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">{{ translate('Shipping Price Options') }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="mb-2">{{ translate('Riyadh Areas') }}</h6>
                @if(count($riyadh))
                    <ul class="list-group">
                        @foreach($riyadh as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item['area'] ?? '-' }}
                                <span class="badge badge-primary badge-pill">{{ single_price($item['price'] ?? 0) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">{{ translate('No settings found') }}</p>
                @endif
            </div>
            <div class="col-md-6">
                <h6 class="mb-2">{{ translate('Other Provinces') }}</h6>
                @if(count($other))
                    <ul class="list-group">
                        @foreach($other as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item['area'] ?? '-' }}
                                <span class="badge badge-primary badge-pill">{{ single_price($item['price'] ?? 0) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">{{ translate('No settings found') }}</p>
                @endif
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ url('/admin/settings/shipping-prices') }}" class="btn btn-sm btn-primary">{{ translate('Configure Shipping Prices') }}</a>
        </div>
    </div>
</div>

@can('admin_dashboard')
<div class="row gutters-10">
    <div class="col-lg-6">
        <div class="row gutters-10">
            <div class="col-6">
                <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Customer') }}
                        </div>
                        <div class="h3 fw-700 mb-3">
                            {{ \App\Models\User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->count() }}
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Order') }}
                        </div>
                        <div class="h3 fw-700 mb-3">{{ \App\Models\Order::count() }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Product category') }}
                        </div>
                        <div class="h3 fw-700 mb-3">{{ \App\Models\Category::count() }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Product brand') }}
                        </div>
                        <div class="h3 fw-700 mb-3">{{ \App\Models\Brand::count() }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="row gutters-10">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">{{ translate('Products') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-1" class="w-100" height="305"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6" style="display: none">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">{{ translate('Sellers') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-2" class="w-100" height="305"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gutters-10">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fs-14">{{ translate('Category wise product sale') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="graph-1" class="w-100" height="500"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fs-14">{{ translate('Category wise product stock') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="graph-2" class="w-100" height="500"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">{{ translate('Top 12 Products') }}</h6>
    </div>
    <div class="card-body">
        <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows='true'>
            @foreach (filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get() as $key => $product)
                <div class="carousel-box">
                    <div class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                        <div class="position-relative">
                            <a href="{{ route('product', $product->slug) }}" class="d-block">
                                <img
                                    class="img-fit lazyload mx-auto h-210px"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                    alt="{{  $product->getTranslation('name')  }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                >
                            </a>
                        </div>
                        <div class="p-md-3 p-2 text-left">
                            <div class="fs-15">
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                @endif
                                <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                            </div>
                            <div class="rating rating-sm mt-1">
                                {{ renderStarRating($product->rating) }}
                            </div>
                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endcan

@endsection
@section('script')
<script type="text/javascript">
    AIZ.plugins.chart('#pie-1',{
        type: 'doughnut',
        data: {
            labels: [
                '{{translate('Total published products')}}',
                '{{translate('Total sellers products')}}',
                '{{translate('Total admin products')}}'
            ],
            datasets: [
                {
                    data: [
                        {{ \App\Models\Product::where('published', 1)->count() }},
                        {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                        {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });

    AIZ.plugins.chart('#pie-2',{
        type: 'doughnut',
        data: {
            labels: [
                '{{translate('Total sellers')}}',
                '{{translate('Total approved sellers')}}',
                '{{translate('Total pending sellers')}}'
            ],
            datasets: [
                {
                    data: [
                        {{ \App\Models\Shop::count() }},
                        {{ \App\Models\Shop::where('verification_status', 1)->count() }},
                        {{ \App\Models\Shop::where('verification_status', 0)->count() }}
                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Montserrat',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });
    AIZ.plugins.chart('#graph-1',{
        type: 'bar',
        data: {
            labels: [
                @foreach ($root_categories as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of sale') }}',
                data: [
                    {{ $cached_graph_data['num_of_sale_data'] }}
                ],
                backgroundColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(55, 125, 255, 0.4)',
                    @endforeach
                ],
                borderColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(55, 125, 255, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
    AIZ.plugins.chart('#graph-2',{
        type: 'bar',
        data: {
            labels: [
                @foreach ($root_categories as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of Stock') }}',
                data: [
                    {{ $cached_graph_data['qty_data'] }}
                ],
                backgroundColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(253, 57, 149, 0.4)',
                    @endforeach
                ],
                borderColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(253, 57, 149, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
</script>

<!-- إضافة: إدراج رابط "أسعار الشحن" في السايدبار عند تحميل الصفحة -->
<script>
(function(){
    // عناصر السايدبار المحتملة في القالب
    var selectors = ['.aiz-side-nav', '.aiz-side-nav-list', '#sidebar', '.sidebar-menu', '.menu-vertical'];
    var container = null;
    for (var i = 0; i < selectors.length; i++) {
        var el = document.querySelector(selectors[i]);
        if (el) { container = el; break; }
    }
    if (!container) return; // لا نفعل شيئًا إن لم نعثُر على السايدبار

    // تأكد أن الرابط غير مكرر
    if (container.querySelector('a[href="/admin/settings/shipping-prices"]')) return;

    // عنصر القائمة الجديد مع قائمة فرعية لـ Riyadh و Other
    var li = document.createElement('li');
    li.className = 'aiz-side-nav-item';
    li.innerHTML = '<a href="/admin/settings/shipping-prices" class="aiz-side-nav-link">\
        <i class="las la-truck aiz-side-nav-icon"></i>\
        <span class="aiz-side-nav-text">{{ __("Shipping Prices") }}</span>\
        <span class="aiz-side-nav-arrow" style="float:right; margin-left:8px;">▼</span>\
    </a>\
    <ul class="aiz-side-nav-list" style="display:none; margin-top:6px; padding-left:12px;">\
        <li class="aiz-side-nav-item"><a href="/admin/settings/shipping-prices?section=riyadh" class="aiz-side-nav-link"><span class="aiz-side-nav-text">{{ __("Riyadh Areas") }}</span></a></li>\
        <li class="aiz-side-nav-item"><a href="/admin/settings/shipping-prices?section=other" class="aiz-side-nav-link"><span class="aiz-side-nav-text">{{ __("Other Provinces") }}</span></a></li>\
    </ul>';

    // إدراج العنصر في القائمة
    if (container.firstElementChild) {
        container.insertBefore(li, container.firstElementChild.nextSibling);
    } else {
        container.appendChild(li);
    }

    // إضافة سلوك تبديل العرض للقائمة الفرعية عند النقر على العنصر الرئيسي
    try {
        var mainLink = li.querySelector('a.aiz-side-nav-link');
        var submenu = li.querySelector('ul.aiz-side-nav-list');
        var arrow = li.querySelector('.aiz-side-nav-arrow');

        mainLink.addEventListener('click', function(e){
            e.preventDefault();
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                if (arrow) arrow.textContent = '▲';
            } else {
                submenu.style.display = 'none';
                if (arrow) arrow.textContent = '▼';
            }
        });
    } catch (err) {
        // تجاهل أي أخطاء بسيطة لضمان عدم كسر الصفحة
        console.error(err);
    }
})();
</script>
@endsection

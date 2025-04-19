@extends('frontend.layouts.app')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                @if (addon_is_activated('club_point'))
                    <div class="col">
                        <div class="p-4 bg-secondary-base">
                            <div class="d-flex align-items-center pb-4 ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <g id="Group_25000" data-name="Group 25000" transform="translate(-926 -614)">
                                        <rect id="Rectangle_18646" data-name="Rectangle 18646" width="48" height="48"
                                            rx="24" transform="translate(926 614)" fill="rgba(255,255,255,0.5)" />
                                        <g id="Group_24786" data-name="Group 24786" transform="translate(701.466 93)">
                                            <path id="Path_2961" data-name="Path 2961"
                                                d="M221.069,0a8,8,0,1,0,8,8,8,8,0,0,0-8-8m0,15a7,7,0,1,1,7-7,7,7,0,0,1-7,7"
                                                transform="translate(27.466 537)" fill="#fff" />
                                            <path id="Union_11" data-name="Union 11"
                                                d="M16425.393,420.226l-3.777-5.039a.42.42,0,0,1-.012-.482l1.662-2.515a.416.416,0,0,1,.313-.186l0,0h4.26a.41.41,0,0,1,.346.19l1.674,2.515a.414.414,0,0,1-.012.482l-3.777,5.039a.413.413,0,0,1-.338.169A.419.419,0,0,1,16425.393,420.226Zm-2.775-5.245,3.113,4.148,3.109-4.148-1.32-1.983h-3.592Z"
                                                transform="translate(-16177.195 129)" fill="#fff" />
                                        </g>
                                    </g>
                                </svg>
                                <div class="ml-3 d-flex flex-column justify-content-between">
                                    <span class="fs-14 fw-400 text-white mb-1">{{ translate('Total Saudi Points') }}</span>
                                    <span class="fs-20 fw-700 text-white">{{ get_user_total_club_point() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('earnng_point_for_user') }}" class="fs-12 text-white">
                                {{ translate('Convert Club Points') }}
                                <i class="las la-angle-right fs-14"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-4 bg-secondary-base">
                            <div class="d-flex align-items-center pb-4 ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <g id="Group_25000" data-name="Group 25000" transform="translate(-926 -614)">
                                        <rect id="Rectangle_18646" data-name="Rectangle 18646" width="48" height="48"
                                            rx="24" transform="translate(926 614)" fill="rgba(255,255,255,0.5)" />
                                        <g id="Group_24786" data-name="Group 24786" transform="translate(701.466 93)">
                                            <path id="Path_2961" data-name="Path 2961"
                                                d="M221.069,0a8,8,0,1,0,8,8,8,8,0,0,0-8-8m0,15a7,7,0,1,1,7-7,7,7,0,0,1-7,7"
                                                transform="translate(27.466 537)" fill="#fff" />
                                            <path id="Union_11" data-name="Union 11"
                                                d="M16425.393,420.226l-3.777-5.039a.42.42,0,0,1-.012-.482l1.662-2.515a.416.416,0,0,1,.313-.186l0,0h4.26a.41.41,0,0,1,.346.19l1.674,2.515a.414.414,0,0,1-.012.482l-3.777,5.039a.413.413,0,0,1-.338.169A.419.419,0,0,1,16425.393,420.226Zm-2.775-5.245,3.113,4.148,3.109-4.148-1.32-1.983h-3.592Z"
                                                transform="translate(-16177.195 129)" fill="#fff" />
                                        </g>
                                    </g>
                                </svg>
                                <div class="ml-3 d-flex flex-column justify-content-between">
                                    <span
                                        class="fs-14 fw-400 text-white mb-1">{{ translate('Total Malaysian Points') }}</span>
                                    <span class="fs-20 fw-700 text-white">{{ get_user_total_malaysian_point() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('earnng_point_for_user') }}" class="fs-12 text-white">
                                {{ translate('Convert Club Points') }}
                                <i class="las la-angle-right fs-14"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <br><br>


            <div class="d-flex align-items-start">
                @include('frontend.inc.user_side_nav')

                <div class="aiz-user-panel">
                    <div class="aiz-titlebar mt-2 mb-4" style="display: none">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3">{{ translate('My Points') }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display:none;">
                        <div class="col-md-7 mx-auto">
                            <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                                <div class="px-3 pt-3 pb-3">
                                    <div class="h3 fw-700 text-center">{{ get_setting('club_point_convert_rate') }}
                                        {{ translate(' Points') }} = {{ single_price(1) }}
                                        {{ translate('Wallet Money') }}</div>
                                    <div class="opacity-50 text-center">{{ translate('Exchange Rate') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--                    <br> --}}

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Point Earning history') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <form action="{{ route('club_points.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control PointsWantConverted"  name="points"
                                                placeholder="{{ translate('Points Want Converted') }}"
                                                value="{{ request()->input('points') }}">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button"
                                                class="btn btn-primary btn-block" onclick="convert_point({{ $club_point->id }})" >{{ translate('convert') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                           {{--  <table class="table aiz-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ translate('Order Code') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Points') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Converted') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Date') }}</th>
                                        <th class="text-right">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($club_points as $key => $club_point)
                                        @php
                                            $convertible_club_point = $club_point->club_point_details
                                                ->where('refunded', 0)
                                                ->sum('point');
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if ($club_point->order != null)
                                                    {{ $club_point->order->code }}
                                                @else
                                                    {{ translate('Order not found') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($convertible_club_point > 0)
                                                    {{ $convertible_club_point }} {{ translate(' pts') }}
                                                @else
                                                    {{ translate('Refunded') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($club_point->convert_status == 1)
                                                    <span
                                                        class="badge badge-inline badge-success">{{ translate('Yes') }}</strong></span>
                                                @else
                                                    <span
                                                        class="badge badge-inline badge-info">{{ translate('No') }}</strong></span>
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($club_point->created_at)) }}</td>

                                            <td class="text-right">

                                                @if ($club_point->convert_status == 0 && $convertible_club_point > 0)
                                                    <button onclick="convert_point({{ $club_point->id }})"
                                                        class="btn btn-sm btn-styled btn-primary">{{ translate('Convert Now') }}</button>
                                                @elseif($convertible_club_point == 0)
                                                    <span
                                                        class="badge badge-inline badge-warning">{{ translate('Refunded') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-inline badge-success">{{ translate('Done') }}</span>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                {{ $club_points->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        function convert_point(el) {
            $.post('{{ route('convert_point_into_wallet') }}', {
                _token: '{{ csrf_token() }}',
                el: el
                points: $('.PointsWantConverted').val()
            }, function(data) {
                if (data == 1) {
                    location.reload();
                    AIZ.plugins.notify('success',
                        '{{ translate('Convert has been done successfully Check your Wallets') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection

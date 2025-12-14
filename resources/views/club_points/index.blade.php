@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{translate('User code')}}</th>
                                <th>{{translate('Transaction Type')}}</th>
                                <th>{{translate('Client code')}}</th>
                                <th>{{translate('Points Type')}}</th>
                                <th>{{translate('Points')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($club_points as $club_point)
                                <tr>
                                    <td>{{$club_point->user->postal_code}}</td>
                                    <td>{{$club_point->type}}</td>
                                    <td>{{$club_point->client != null ? $club_point->client->zipcode : ''}}</td>
                                    <td>{{$club_point->points_type}}</td>
                                    <td>{{$club_point->amount}}</td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $club_points->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

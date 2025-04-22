@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-20 fw-700 text-dark">{{ translate('Clients') }}</h1>
            </div>
        </div>
    </div>

    <!-- Create a Ticket -->
    <div class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light" data-toggle="modal" data-target="#ticket_modal">
        <i class="las la-plus la-3x mb-2"></i>
        <div class="fs-14 fw-600 text-dark">{{ translate('Create a Client') }}</div>
    </div>

    <!-- Tickets -->
    <div class="card rounded-0 shadow-none border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark text-center text-md-left">{{ translate('Clients')}}</h5>
        </div>
          <div class="card-body py-0">
              <table class="table aiz-table mb-4">
                  <thead class="text-gray fs-12">
                    <tr>
                        <th>{{ translate('ID') }}</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('zipcode') }}</th>
                        <th>{{ translate('saudi_points') }}</th>
                        <th>{{ translate('malaysian_points') }}</th>
                    </tr>
                  </thead>
                  <tbody class="fs-14">
                      @foreach ($clients as $client)

                        <tr>
                            <td>#{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->zipcode }}</td>
                            <td>{{ $client->saudi_points }}</td>
                            <td>{{ $client->malaysian_points }}</td>

                        </tr>
                      @endforeach
                  </tbody>
              </table>
              <!-- Pagination -->
              <div class="aiz-pagination">
                  {{ $clients->links() }}
              </div>
          </div>
    </div>
@endsection

@section('modal')
    <!-- Ticket modal -->
    <div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{ translate('Create a Client')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-3 pt-3">
                    <form class="" action="{{ route('clients.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Name')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Name')}}" name="name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('zipcode')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('zipcode')}}" name="zipcode" required>
                            </div>
                        </div>


                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-secondary rounded-0 w-150px" data-dismiss="modal">{{ translate('cancel')}}</button>
                            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{ translate('Add')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

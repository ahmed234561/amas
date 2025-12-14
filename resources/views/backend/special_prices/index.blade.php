@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Special Prices Management')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Add New Special Price')}}</h5>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="{{ route('special-prices.store') }}" method="POST">
            @csrf
            <div class="form-group row">
                <label class="col-sm-3 col-from-label">{{translate('Select Product')}}</label>
                <div class="col-sm-9">
                    <select name="product_id" id="product_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                        <option value="">{{ translate('Select Product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->getTranslation('name') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label">{{translate('Select User')}}</label>
                <div class="col-sm-9">
                    <select name="user_id" id="user_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                        <option value="">{{ translate('Select User') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label">{{translate('Special Price')}}</label>
                <div class="col-sm-9">
                    <input type="number" step="0.01" min="0" name="special_price" class="form-control" required>
                </div>
            </div>
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('All Special Prices') }}</h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Product')}}</th>
                    <th>{{translate('Client')}}</th>
                    <th>{{translate('Special Price')}}</th>
                    <th>{{translate('Added By')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specialPrices as $key => $price)
                <tr>
                    <td>{{ ($key+1) + ($specialPrices->currentPage() - 1)*$specialPrices->perPage() }}</td>
                    <td>{{ $price->product->getTranslation('name') }}</td>
                    <td>{{ $price->user->name }}</td>
                    <td>{{ single_price($price->special_price) }}</td>
                    <td>{{ $price->addedBy ? $price->addedBy->name : '' }}</td>
                    <td class="text-right">
                        @if(auth()->user()->hasRole('admin') || $price->user_id == auth()->id())
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                           data-href="{{ route('special-prices.destroy', $price->id) }}">
                            <i class="las la-trash"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $specialPrices->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

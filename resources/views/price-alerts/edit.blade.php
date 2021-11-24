@extends('layouts.master')

@section('content')

    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit {{ $alert -> gecko_name}}</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ $alert -> gecko_name}}</h6>
            </div>
            <div class="card-body">


                @if ($errors->any())
                    <div class="alert alert-warning" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                    {{--    'symbol' => 'required',--}}
                    {{--    'contract_address' => 'required',--}}
                    {{--    'threshold' => 'required',--}}
                    {{--    'condition' => 'required',--}}
                    {{--    'price_source' => 'required',--}}

                <form action="{{ route('price-alerts.edit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>symbol</label>
                        <input type="text" name="symbol" value="{{ $alert-> symbol}}" class="form-control" placeholder="symbol">
                    </div>
                    <div class="form-group">
                        <label>contract_address</label>
                        <input type="text" name="contract_address" value="{{ $alert->contract_address}}" class="form-control" placeholder="contract_address">
                    </div>

                    <div class="form-group">
                        <label>threshold</label>
                        <input type="text" name="threshold" value="{{ $alert->threshold}}" class="form-control" placeholder="threshold">
                    </div>

                    <div class="form-group">
                        <label>condition</label>
                        <input type="text" name="condition" value="{{ $alert->condition}}" class="form-control" placeholder="condition">
                    </div>

                    <div class="form-group">
                        <label>price_source</label>
                        <input type="text" name="price_source" value="{{ $alert->price_source}}" class="form-control" placeholder="price_source">
                    </div>

                    <div class="form-group">
                        <label>created_at</label>
                        <div>{{ $alert-> created_at}}</div>
                    </div>

                    <div class="form-group">
                        <label>updated_at</label>
                        <div>{{ $alert-> updated_at}}</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('price-alerts.index') }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>

        </div>
    </div>


@endsection
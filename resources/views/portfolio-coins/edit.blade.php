@extends('layouts.master')

@section('content')

    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit {{ $portfolio_coin -> gecko_name}}</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ $portfolio_coin -> gecko_name}}</h6>
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

                <form action="{{ route('portfolio-coins.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>gecko_id</label>
                        <input type="text" name="gecko_id" value="{{ $portfolio_coin-> gecko_id}}" class="form-control" placeholder="Gecko ID">
                    </div>
                    <div class="form-group">
                        <label>symbol</label>
                        <input type="text" name="symbol" value="{{ $portfolio_coin->symbol}}" class="form-control" placeholder="Ticker symbol">
                    </div>

                    <div class="form-group">
                        <label>gecko_name</label>
                        <input type="text" name="gecko_name" value="{{ $portfolio_coin->gecko_name}}" class="form-control" placeholder="CoinGecko Name">
                    </div>

                    <div class="form-group">
                        <label>platforms</label>
                        <input type="text" name="platforms" value="{{ $portfolio_coin->platforms}}" class="form-control" placeholder="Platforms in JSON">
                    </div>

                    <div class="form-group">
                        <label>cg_url</label>
                        <input type="text" name="cg_url" value="{{ $portfolio_coin->cg_url}}" class="form-control" placeholder="CoinGecko URL">
                    </div>

                    <div class="form-group">
                        <label>trade_url</label>
                        <input type="text" name="trade_url" value="{{ $portfolio_coin->trade_url}}" class="form-control" placeholder="Trading URL">
                    </div>

                    <div class="form-group">
                        <label>img_url</label>
                        <input type="text" name="img_url" value="{{ $portfolio_coin->img_url}}" class="form-control" placeholder="URL to image">
                    </div>

                    <div class="form-group">
                        <label>chart_url</label>
                        <input type="text" name="chart_url" value="{{ $portfolio_coin->chart_url}}" class="form-control" placeholder="URL to charts">
                    </div>

                    <div class="form-group">
                        <label>price_source</label>
                        <input type="text" name="price_source" value="{{ $portfolio_coin->price_source}}" class="form-control" placeholder="Price source ID">
                    </div>

                    <div class="form-group">
                        <label>created_at</label>
                        <div>{{ $portfolio_coin-> created_at}}</div>
                    </div>

                    <div class="form-group">
                        <label>updated_at</label>
                        <div>{{ $portfolio_coin-> updated_at}}</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('portfolio-coins.index') }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>

        </div>
    </div>


@endsection
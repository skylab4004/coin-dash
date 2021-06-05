@extends('layouts.master')


{{--$table->id();--}}
{{--$table->string('gecko_id')->unique(); // "id": "dexfin",--}}
{{--$table->string('symbol')->unique(); // "symbol": "dxf",--}}
{{--$table->string('gecko_name'); //    "name": "Dexfin"--}}
{{--$table->json('platforms')->nullable();--}}
{{--$table->string('cg_url', 2048)->nullable();--}}
{{--$table->string('trade_url', 2048)->nullable();--}}
{{--$table->string('img_url', 2048)->nullable();--}}
{{--$table->string('chart_url', 2048)->nullable();--}}
{{--$table->integer('price_source')->nullable(); // null,0 -> coingecko; 1 -> uniswap;--}}
{{--$table->timestamps();--}}


@section('content')
    <div>
        <h1>Edit {{ $portfolio_coin -> gecko_name}}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('portfolio-coins.update', $portfolio_coin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table-auto border border-gray-800">
                <thead>
                <tr>
                    <td>Attribute</td>
                    <td>Value</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>id</td>
                    <td>{{ $portfolio_coin-> id}}</td>
                </tr>
                <tr>
                    <td>gecko_id</td>
                    <td><input type="text" name="gecko_id" value="{{ $portfolio_coin-> gecko_id}}" class="form-control" placeholder="Gecko ID"></td>
                </tr>
                <tr>
                    <td>symbol</td>
                    <td><input type="text" name="symbol" value="{{ $portfolio_coin->symbol}}" class="form-control" placeholder="Ticker symbol"></td>
                </tr>
                <tr>
                    <td>gecko_name</td>
                    <td><input type="text" name="gecko_name" value="{{ $portfolio_coin->gecko_name}}" class="form-control" placeholder="CoinGecko Name"></td>
                </tr>
                <tr>
                    <td>platforms</td>
                    <td><input type="text" name="platforms" value="{{ $portfolio_coin->platforms}}" class="form-control" placeholder="Platforms in JSON"></td>
                </tr>
                <tr>
                    <td>cg_url</td>
                    <td><input type="text" name="cg_url" value="{{ $portfolio_coin->cg_url}}" class="form-control" placeholder="CoinGecko URL"></td>

                </tr>
                <tr>
                    <td>trade_url</td>
                    <td><input type="text" name="trade_url" value="{{ $portfolio_coin->trade_url}}" class="form-control" placeholder="Trading URL"></td>
                </tr>
                <tr>
                    <td>img_url</td>
                    <td><input type="text" name="img_url" value="{{ $portfolio_coin->img_url}}" class="form-control" placeholder="URL to image"></td>
                </tr>
                <tr>
                    <td>chart_url</td>
                    <td><input type="text" name="chart_url" value="{{ $portfolio_coin->chart_url}}" class="form-control" placeholder="URL to charts"></td>
                </tr>
                <tr>
                    <td>price_source</td>
                    <td><input type="text" name="price_source" value="{{ $portfolio_coin->price_source}}" class="form-control" placeholder="Price source ID"></td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td>{{ $portfolio_coin-> created_at}}</td>
                </tr>
                <tr>
                    <td>updated_at</td>
                    <td>{{ $portfolio_coin-> updated_at}}</td>
                </tr>
                </tbody>
            </table>
            <button type="submit">Submit</button>
        </form>

    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('portfolio-coins.index') }}" title="Go back">Go back</a>
    </div>

@endsection
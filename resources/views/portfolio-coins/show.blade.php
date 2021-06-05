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
        <h1>{{ $coin-> gecko_name}}</h1>
        <img src="{{ $coin-> img_url}}"/>
        <h2>{{ $coin-> symbol}}</h2>
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
                <td>{{ $coin-> id}}</td>
            </tr>
            <tr>
                <td>gecko_id</td>
                <td>{{ $coin-> gecko_id}}</td>
            </tr>
            <tr>
                <td>symbol</td>
                <td class="uppercase">{{ $coin-> symbol}}</td>
            </tr>
            <tr>
                <td>gecko_name</td>
                <td>{{ $coin-> gecko_name}}</td>
            </tr>
            <tr>
                <td>platforms</td>
                <td>{{ $coin-> platforms}}</td>
            </tr>
            <tr>
                <td>cg_url</td>
                <td>{{ $coin-> cg_url}}</td>
            </tr>
            <tr>
                <td>trade_url</td>
                <td>{{ $coin-> trade_url}}</td>
            </tr>
            <tr>
                <td>img_url</td>
                <td>{{ $coin-> img_url}}</td>
            </tr>
            <tr>
                <td>chart_url</td>
                <td>{{ $coin-> chart_url}}</td>
            </tr>
            <tr>
                <td>price_source</td>
                <td>{{ $coin->price_source }}</td>
            </tr>
            <tr>
                <td>created_at</td>
                <td>{{ $coin-> created_at}}</td>
            </tr>
            <tr>
                <td>updated_at</td>
                <td>{{ $coin-> updated_at}}</td>
            </tr>
            </tbody>

        </table>

    </div>
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('portfolio-coins.index') }}" title="Go back">Go back</a>
    </div>

@endsection
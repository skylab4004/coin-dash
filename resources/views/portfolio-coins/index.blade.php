@extends('layouts.master')

@section('content')

    <div class="container">
        <div>
            <a href="{{ route('portfolio-coins.create') }}">create</a>
        </div>

        @if ($message = Session::get('success'))
            <div>
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table-auto border border-gray-800">
            <thead>
            <tr>
                <th></th>
                <th>Symbol</th>
                <th>Gecko ID</th>
                <th>Gecko name</th>
                <th>Trading</th>
                <th>Chart</th>
                <th>Price source</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($portfolioCoins as $portfolioCoin)
                <tr>
                    <td><img src="{{$portfolioCoin->img_url}}" class="rounded-full" alt="{{ $portfolioCoin->symbol }}"></td>
                    <td class="uppercase"><a href="{{ $portfolioCoin->cg_url }}" target="_blank">{{ $portfolioCoin->symbol }}</a></td>
                    <td><a href="{{ $portfolioCoin->cg_url }}" target="_blank">{{ $portfolioCoin->gecko_id }}</a></td>
                    <td><a href="{{ $portfolioCoin->cg_url }}" target="_blank">{{ $portfolioCoin->gecko_name }}</a></td>
                    <td>{{ $portfolioCoin->trade_url }}</td>
                    <td>{{ $portfolioCoin->chart_url }}</td>
                    <td>{{ $portfolioCoin->price_source }}</td>
                    <td>
                        <form action="{{ route('portfolio-coins.destroy', $portfolioCoin->id) }}" method="POST">

                            <a href="{{ route('portfolio-coins.show', $portfolioCoin->id) }}">show</a>

                            <a href="{{ route('portfolio-coins.edit', $portfolioCoin->id) }}">edit</a>

                            @csrf
                            @method('DELETE')
                            <button type="submit">delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection
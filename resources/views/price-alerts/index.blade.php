@extends('layouts.master')

@section('content')
    <div>
        <a href="{{ route('price-alerts.create') }}">create</a>
    </div>

    @if ($message = Session::get('success'))
        <div>
            <p>{{ $message }}</p>
        </div>
    @endif

    <table>
        <tr>
            <th>ID</th>
            <th>Symbol</th>
            <th>Contract address</th>
            <th>Threshold</th>
            <th>Condition</th>
            <th>Triggered</th>
            <th>Price source</th>
            <th>Action</th>
        </tr>
        @foreach ($priceAlerts as $priceAlert)
            <tr>
                <td>{{ $priceAlert->id }}</td>
                <td>{{ $priceAlert->symbol }}</td>
                <td>{{ $priceAlert->contract_address }}</td>
                <td>{{ $priceAlert->threshold }}</td>
                <td>{{ $priceAlert->condition }}</td>
                <td>{{ $priceAlert->triggered }}</td>
                <td>{{ $priceAlert->price_source }}</td>
                <td>
                    <form action="{{ route('price-alerts.destroy', $priceAlert->id) }}" method="POST">

                        <a href="{{ route('price-alerts.show', $priceAlert->id) }}">show</a>

                        <a href="{{ route('price-alerts.edit', $priceAlert->id) }}">edit</a>

                        @csrf
                        @method('DELETE')
                        <button type="submit">delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

@endsection
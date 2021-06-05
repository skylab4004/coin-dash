@extends('layouts.master')

@section('content')
    <div>
        <h2>Add New Portfolio Coin</h2>
        <a href="{{ route('portfolio-coins.index') }}">go back</a>
    </div>

    @if ($errors->any())
        <div>
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('portfolio-coins.store') }}" method="POST">
        @csrf
        <div>
            <div class="form-group">
                <strong>Gecko ID:</strong>
                <input type="text" name="gecko_id" class="form-control" placeholder="bitcoin">
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </div>

    </form>
@endsection
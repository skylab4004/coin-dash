@extends('layouts.master')

@section('content')
    <div>
        <h2>Add New Project</h2>
        <a href="{{ route('price-alerts.index') }}">go back</a>
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

    <form action="{{ route('price-alerts.store') }}" method="POST">
        @csrf
        <div>
            <div class="form-group">
                <strong>Symbol:</strong>
                <input type="text" name="symbol" class="form-control" placeholder="eg. BTC">
            </div>
            <div class="form-group">
                <strong>Contract address:</strong>
                <input type="text" name="contract_address" class="form-control" placeholder="0x01231231203123">
            </div>
            <div class="form-group">
                <strong>Threshold:</strong>
                <input type="number" step="any" name="threshold" class="form-control" placeholder="0.44">
            </div>
            <div class="form-group">
                <strong>Condition:</strong>
                <input type="number" step="any" name="condition" class="form-control" placeholder="0 for >, 1 for <">
            </div>
            <div class="form-group">
                <strong>Price source:</strong>
                <input type="number" name="price_source" class="form-control" placeholder="1 for UniSwap">
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </div>

    </form>
@endsection
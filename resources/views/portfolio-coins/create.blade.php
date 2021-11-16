@extends('layouts.master')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Portfolio Coin</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Portfolio Coin</h6>
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
                        <label>Gecko ID</label>
                        <input type="text" name="gecko_id" class="form-control" placeholder="bitcoin">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('portfolio-coins.index') }}" class="btn btn-secondary">Cancel</a>
                </form>

            </div>

        </div>
    </div>
@endsection
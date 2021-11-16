@extends('layouts.master')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Price alerts</h1>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

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

    <!-- Balances per DEX/CEX -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Price alerts</h6>
            </div>
            <div class="card-body">
                <div>
                    <a href="{{ route('price-alerts.create') }}" class="btn btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
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
                        </thead>
                        <tbody>
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


                                    <a href="{{ route('price-alerts.show', $priceAlert->id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i>
                                    </a>

                                    <a href="{{ route('price-alerts.edit', $priceAlert->id) }}"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('price-alerts.destroy', $priceAlert->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
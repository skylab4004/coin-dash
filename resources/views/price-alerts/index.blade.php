@extends('layouts.master')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Price alerts</h4>
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

        <div class="card mb-4">
            <div class="card-header pt-3 pb-0">
                <a href="{{ route('price-alerts.create') }}" class="btn btn-primary">Create</a>
            </div>
            <div class="card-body">
                <table class="table table-hover table-centered my-0">
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

                            <td class="table-action">
                                <a href="{{ route('price-alerts.show', $priceAlert->id) }}" class="action-icon"> <i class="far fa-eye"></i> </a>
                                <a href="{{ route('price-alerts.edit', $priceAlert->id) }}" class="action-icon"> <i class="fas fa-pencil-alt"></i> </a>
                                <a href="{{ route('price-alerts.destroy', $priceAlert->id) }}" class="action-icon"> <i class="far fa-trash-alt"></i> </a>
                            </td>

{{--                            <td>--}}
{{--                                <a href="{{ route('price-alerts.show', $priceAlert->id) }}"--}}
{{--                                   class="btn btn-primary btn-sm">--}}
{{--                                    <i class="fas fa-search"></i>--}}
{{--                                </a>--}}

{{--                                <a href="{{ route('price-alerts.edit', $priceAlert->id) }}"--}}
{{--                                   class="btn btn-secondary btn-sm">--}}
{{--                                    <i class="fas fa-edit"></i>--}}
{{--                                </a>--}}

{{--                                <form action="{{ route('price-alerts.destroy', $priceAlert->id) }}"--}}
{{--                                      method="POST">--}}
{{--                                    @csrf--}}
{{--                                    @method('DELETE')--}}
{{--                                    <button type="submit" class="btn btn-danger btn-sm">--}}
{{--                                        <i class="fas fa-trash"></i>--}}
{{--                                    </button>--}}
{{--                                </form>--}}
{{--                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
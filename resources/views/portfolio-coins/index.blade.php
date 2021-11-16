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

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Price alerts</h6>
            </div>
            <div class="card-body">
                <div>
                    <a href="{{ route('portfolio-coins.create') }}" class="btn btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
            </div>
        </div>

    </div>



@endsection
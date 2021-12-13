@extends('layouts.master')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Portfolio coins</h4>
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
                <a href="{{ route('portfolio-coins.create') }}" class="btn btn-primary">Create</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-centered my-0">
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
                            <td class="table-action">
                                <a href="{{ route('portfolio-coins.show', $portfolioCoin->id) }}" class="action-icon"> <i class="far fa-eye"></i> </a>
                                <a href="{{ route('portfolio-coins.edit', $portfolioCoin->id) }}" class="action-icon"> <i class="fas fa-pencil-alt"></i> </a>

                                <form action="{{ route('portfolio-coins.destroy', $portfolioCoin->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <i class="far fa-trash-alt" type="submit"></i>
                                </form>
                                <a href="{{ route('portfolio-coins.destroy', $portfolioCoin->id) }}" class="action-icon">  </a>

                            </td>
{{--                            <td>--}}
{{--                                <form action="{{ route('portfolio-coins.destroy', $portfolioCoin->id) }}" method="POST">--}}

{{--                                    <a href="{{ route('portfolio-coins.show', $portfolioCoin->id) }}">show</a>--}}

{{--                                    <a href="{{ route('portfolio-coins.edit', $portfolioCoin->id) }}">edit</a>--}}

{{--                                    @csrf--}}
{{--                                    @method('DELETE')--}}
{{--                                    <button type="submit">delete</button>--}}
{{--                                </form>--}}
{{--                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>



                <div>
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
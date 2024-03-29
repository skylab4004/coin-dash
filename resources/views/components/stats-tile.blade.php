<!-- Card -->
<div class="col-xl-3 col-md-6 mb-4">
    @if ($value < 0)
    <div class="card border-left-danger h-100 py-2"> <!-- gdy value >0 -->
    @else
    <div class="card border-left-success h-100 py-2">
    @endif
        <div class="card-body py-md-2">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-sm font-weight-bold text-secondary text-uppercase mb-1">{{$title}}</div>
                    @if ($value < 0)
                        <div class="mb-0 font-weight-bold text-danger text-xxl text-nowrap">{{$value}}
                            <span class="text-sm mb-0 font-weight-bold text-gray-400"> PLN</span>
                        </div>
                    @else
                        <div class="mb-0 font-weight-bold text-success text-xxl text-nowrap">{{$value}}
                            <span class="text-sm mb-0 font-weight-bold text-gray-400"> PLN</span>
                        </div>
                    @endif
                </div>
{{--                <!-- Icon -->--}}
{{--            <div class="col-auto">--}}
{{--                <i class="fas fa-calendar fa-2x text-gray-300"></i>--}}
{{--            </div>--}}
            </div>

        @isset($percent)
            <!-- Percents badge -->
            <div class="row no-gutters align-items-center">
                @if ($percent < 0 )
                <span class="badge-pill badge-danger font-weight-bolder text-nowrap">
                    <i class="fas fa-caret-down"></i>
                    {{$percent}}%
                </span>
                @else
                    <span class="badge-pill badge-success font-weight-bolder text-nowrap">
                        <i class="fas fa-caret-up"></i>
                        {{$percent}}%
                    </span>
                @endif
            </div>
        @endisset


        @isset($secondary)
            dupa2
            <div>{{$secondary}}</div>
            <!-- Secondary value -->
{{--            <div class="row no-gutters align-items-center">--}}
{{--                <span class="badge-pill badge-success font-weight-bolder text-nowrap">{{$secondary}} {{$secondary_unit}}</span>--}}
{{--            </div>--}}
        @endisset
        </div>
    </div>
</div>

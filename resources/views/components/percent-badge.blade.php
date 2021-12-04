@if ($value < 0 )
    <span class="text-danger text-nowrap">{{$value}}{{$unit}}</span>
@else
    <span class="text-success text-nowrap">{{$value}}{{$unit}}</span>
@endif

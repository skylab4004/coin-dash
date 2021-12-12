@if ($value == 'N/A' )
    <span class="text-warning text-nowrap">{{$value}}{{$unit}}</span>
@elseif ($value < 0 )
    <span class="text-danger text-nowrap">{{$value}}{{$unit}}</span>
@else
    <span class="text-success text-nowrap">{{$value}}{{$unit}}</span>
@endif

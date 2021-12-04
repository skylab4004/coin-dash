@if ($value < 0 )
    <span class="text-danger">{{$value}}{{$unit}}</span>
@else
    <span class="text-success">{{$value}}{{$unit}}</span>
@endif

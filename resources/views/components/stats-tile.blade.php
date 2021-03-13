<!-- Stats tile -->
@if ($value < 0)
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 shadow-md border-red-500 border-b-4">
@else
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 shadow-md border-green-500 border-b-4">
@endif
        <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">{{$title}}</div>
        <div class="flex items-baseline justify-center py-1">
            @if ($value < 0)
                <span class="text-red-500 font-normal text-4xl">{{$value}}</span>
            @else
                <span class="text-green-500 font-normal text-4xl">{{$value}}</span>
            @endif

            @if ($unit != null)
                <span class="text-sm ml-1 font-bold text-gray-600">{{$unit}}</span>
            @endif
        </div>

        @if ($percent!= null)
            <div class="flex place-self-end">
                @if ($percent < 0 )
                    <span class="bg-red-200 text-red-700 px-2 py-0.5 mb-2 ml-auto text-s font-medium rounded-full">{{$percent}}%</span>
                @else
                    <span class="bg-green-200 text-green-700 px-2 py-0.5 mb-2 ml-auto text-s font-medium rounded-full">+{{$percent}}%</span>
                @endif
            </div>
        @endif
    </div>
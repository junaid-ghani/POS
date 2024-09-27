<p>
    @php
    $location = \App\Models\Location::where('location_status', 1)
            ->where('location_id', $large_sale['sale_location'])
            ->first();
    @endphp
     A large sale of ${{ $large_sale['sale'] }} was made in {{ $location->location_name}} by {{ $large_sale['user_name'] }}.
</p>

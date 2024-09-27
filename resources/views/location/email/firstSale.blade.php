<p>
    @php
    $location = \App\Models\Location::where('location_status', 1)
            ->where('location_id', $first_sale['sale_location'])
            ->first();
    @endphp
    {{ $first_sale['user_name'] }} has made his/her first sale today of ${{ $first_sale['sale'] }} in {{ $location->location_name}}.
</p>

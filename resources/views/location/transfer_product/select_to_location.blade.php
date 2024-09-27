<option value="">Select</option>
@foreach($locations as $location)
<option value="<?php echo $location->location_id; ?>"><?php echo $location->location_name; ?></option>
@endforeach

<datalist id="vehicleOptions" style="width: max-content;">
    @if(!empty($options))
    @foreach($options as $option)
        <option style="width: max-content;" value="{{ $option['value'] }}" data-year="{{ $option['year'] }}" data-make="{{ $option['make_id'] ?? '' }}" data-model="{{ $option['model'] ?? '' }}"/>
    @endforeach
    @endif
</datalist>
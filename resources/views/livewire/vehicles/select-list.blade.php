
<datalist id="vehicleOptions">
    @if(!empty($options))
    @foreach($options as $option)
        <option value="{{ $option['value'] }}" data-year="{{ $option['year'] }}" data-make="{{ $option['make_id'] }}" data-model="{{ $option['model_id'] }}">
    @endforeach
    @endif
</datalist>

<datalist id="adjusterOptions">
    @if(!empty($adjusters))
    @foreach($adjusters as $adjuster)
        <option wire:onclick="updateAdjuster({{ $adjuster['id'] }})" data-user-id="{{ $adjuster['id'] }}" value="{{ $adjuster['name'] }}"/>
    @endforeach
    @endif
</datalist>
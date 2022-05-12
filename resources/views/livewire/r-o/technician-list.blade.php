
<datalist id="techniciansOptions">
    @if(!empty($technicians))
    @foreach($technicians as $technician)
        <option value="{{ $technician }}"/>
    @endforeach
    @endif
</datalist>
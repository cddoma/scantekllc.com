
<datalist id="adjusterOptions">
    @if(!empty($adjusters))
    @foreach($adjusters as $adjuster)
        <option value="{{ $adjuster }}"/>
    @endforeach
    @endif
</datalist>
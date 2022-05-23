
<datalist id="productOptions">
    @if(!empty($products))
    @foreach($products as $product)
        <option value="{{ $product['name'] }}" data-product-id="{{ $product['product_id'] data-price="{{ $product['price'] }}"/>
    @endforeach
    @endif
</datalist>
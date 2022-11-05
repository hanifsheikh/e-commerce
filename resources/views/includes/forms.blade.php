<form id="form-placeorder" action="/order/place" method="post" style="display: none;">
    @csrf
</form>
<form id="form-wishlist" action="/customer/wishlist/save" method="post" style="display: none;">
    @csrf
</form>
<form id="form-checkout" action="/order/checkout" method="post" style="display: none;">
    @csrf
</form>
<form id="address-delete" action="/shipping-address/delete" method="post" style="display: none;">
    @csrf
</form>
<form id='logout' action="/customer/logout" method="POST">
    @csrf
</form>
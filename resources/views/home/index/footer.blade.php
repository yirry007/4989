<div style="height:48px;"></div>
<div class="footers">
    <a href="{{ url('index') }}" class="{{ Request::path() == 'index' ? 'active' : '' }}">Main</a>
    <a href="{{ url('nav') }}" class="{{ Request::path() == 'nav' ? 'active' : '' }}">Cate</a>
    <a href="{{ url('brand') }}" class="{{ Request::path() == 'brand' || strstr(Request::path(), 'brand_view') ? 'active' : '' }}">Brand</a>
    <a href="{{ url('cart') }}" class="{{ Request::path() == 'cart' ? 'active' : '' }}">Cart<em></em></a>
    <a href="{{ url('my') }}" class="{{ Request::path() == 'my' ? 'active' : '' }}">My</a>
</div>
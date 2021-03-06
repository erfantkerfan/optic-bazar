<ul class="nav" id="side-menu">
    <li><a href="{{ url('cp-bonakdar') }}" class="waves-effect"><i class="icon-handbag" data-icon="v"></i> <span class="hide-menu"> سفارشات </span> </a></li>
    <li><a href="{{ url('cp-bonakdar/bill') }}" class="waves-effect"><i class="icon-docs" data-icon="v"></i> <span class="hide-menu"> صورت حساب مالی </span> </a></li>
    <li> <a href="{{ url('cp-bonakdar/products') }}" class="waves-effect"><i class="icon-eyeglass" data-icon="v"></i> <span class="hide-menu"> محصولات <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-bonakdar/products/lens') }}">نمایش همه لنز ها</a></li>
            <li><a href="{{ url('cp-bonakdar/product/add/lens') }}">افزودن لنز</a></li>
            <li><a href="{{ url('cp-bonakdar/products/optical-glass') }}">نمایش همه عدسی ها</a></li>
            <li><a href="{{ url('cp-bonakdar/product/add/optical-glass') }}">افزودن عدسی</a></li>
        </ul>
    </li>


    <li><a href="{{ url('order/new') }}" class="waves-effect"><i class="icon-plus" data-icon="v"></i> <span class="hide-menu">ثبت سفارش جدید </span> </a></li>
    <li><a href="{{ url('dashboard') }}" class="waves-effect"><i class="icon-handbag" data-icon="v"></i> <span class="hide-menu">سفارش های من</span> </a></li>
    <li><a href="{{ url('user/profile') }}" class="waves-effect"><i class="icon-user" data-icon="v"></i> <span class="hide-menu">حساب کاربری</span> </a></li>
    <li><a href="{{ url('user/charge') }}" class="waves-effect"><i class="icon-star" data-icon="v"></i> <span class="hide-menu">افزایش اعتبار</span> </a></li>
    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="waves-effect"><i class="icon-logout" data-icon="v"></i> <span class="hide-menu">خروج از حساب کاربری</span> </a></li>
</ul>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
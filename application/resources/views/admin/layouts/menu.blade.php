<ul class="nav" id="side-menu">
    <li> <a href="{{ url('cp-manager/dashboard') }}" class="waves-effect"><i class="icon-home" data-icon="v"></i> <span class="hide-menu"> داشبورد </span> </a></li>
    <li> <a href="{{ url('cp-manager/users') }}" class="waves-effect"><i class="icon-user" data-icon="v"></i> <span class="hide-menu"> کاربران <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/users') }}">نمایش همه کاربر</a></li>
            <li><a href="{{ url('cp-manager/user/add') }}">افزودن کاربر</a></li>
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/brands') }}" class="waves-effect"><i class="icon-tag" data-icon="v"></i> <span class="hide-menu"> برند ها <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/brands') }}">نمایش همه برند ها</a></li>
            <li><a href="{{ url('cp-manager/brand/add') }}">افزودن برند</a></li>
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/products') }}" class="waves-effect"><i class="icon-eyeglass" data-icon="v"></i> <span class="hide-menu"> محصولات <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/products/lens') }}">نمایش همه لنز ها</a></li>
            <li><a href="{{ url('cp-manager/product/add/lens') }}">افزودن لنز</a></li>
            <li><a href="{{ url('cp-manager/products/optical-glass') }}">نمایش همه عدسی ها</a></li>
            <li><a href="{{ url('cp-manager/product/add/optical-glass') }}">افزودن عدسی</a></li>
        </ul>
    </li>
    <li><a href="{{ url('cp-manager/orders') }}" class="waves-effect"><i class="icon-handbag" data-icon="v"></i> <span class="hide-menu"> سفارشات </span> </a></li>

    <li> <a href="{{ url('cp-manager/bill/charge') }}" class="waves-effect"><i class="icon-printer" data-icon="v"></i> <span class="hide-menu"> صورت حساب مالی <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/bill/charge') }}">صورت حساب افزایش اعتبار</a></li>
            <li><a href="{{ url('cp-manager/bill/shop') }}">صورت حساب سامانه</a></li>
            <li><a href="{{ url('cp-manager/bill/bonakdar') }}">صورت حساب بنکداران</a></li>
            <li><a href="{{ url('cp-manager/bill/amel') }}">صورت حساب عامل</a></li>
            <li><a href="{{ url('cp-manager/bill/labrator') }}">صورت حساب لابراتور</a></li>
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/coupons') }}" class="waves-effect"><i class="icon-tag" data-icon="v"></i> <span class="hide-menu"> کوپن تخفیف <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/coupons') }}">نمایش همه کوپن ها</a></li>
            <li><a href="{{ url('cp-manager/coupon/add') }}">افزودن کوپن</a></li>
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/calenders') }}" class="waves-effect"><i class="icon-calender" data-icon="v"></i> <span class="hide-menu"> مدیریت تحویل و ارسال <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/delivery/instant_or_normal') }}">تحویل فوری یا عادی</a></li>
            <li><a href="{{ url('cp-manager/delivery/calender') }}">تحویل تقویمی</a></li>
            {{--<li><a href="{{ url('cp-manager/calenders') }}">مدیریت تحویل و ارسال</a></li>--}}
            {{--<li><a href="{{ url('cp-manager/calender/holidays') }}">روزهای تعطیل</a></li>--}}
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/sliders') }}" class="waves-effect"><i class="icon-picture" data-icon="v"></i> <span class="hide-menu"> اسلاید شو <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/sliders') }}">نمایش همه اسلاید ها</a></li>
            <li><a href="{{ url('cp-manager/slider/add') }}">افزودن اسلاید</a></li>
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/posts') }}" class="waves-effect"><i class="icon-docs" data-icon="v"></i> <span class="hide-menu"> برگه ها <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/posts') }}">نمایش همه برگه ها</a></li>
            <li><a href="{{ url('cp-manager/post/add') }}">افزودن برگه</a></li>
        </ul>
    </li>

    <li> <a href="{{ url('cp-manager/messages') }}" class="waves-effect"><i class="icon-note" data-icon="v"></i> <span class="hide-menu"> پیام ها <span class="fa arrow"></span></span> </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ url('cp-manager/messages') }}">نمایش همه پیام ها</a></li>
            <li><a href="{{ url('cp-manager/message/add') }}">افزودن پیام</a></li>
        </ul>
    </li>

    <li><a href="{{ url('cp-manager/services') }}" class="waves-effect"><i class="icon-badge" data-icon="v"></i> <span class="hide-menu"> قمیت خدمات </span> </a></li>

    <li><a href="{{ url('cp-manager/banars') }}" class="waves-effect"><i class="icon-target" data-icon="v"></i> <span class="hide-menu"> بنر های تبلیغاتی </span> </a></li>
    <li><a href="{{ url('cp-manager/setting') }}" class="waves-effect"><i class="icon-settings" data-icon="v"></i> <span class="hide-menu"> تنظیمات </span> </a></li>
</ul>
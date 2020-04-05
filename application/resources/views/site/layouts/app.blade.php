<?php use \App\Http\Controllers\SettingController; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Safir') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('assets/adminui/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/bootstrap/dist/css/bootstrap.rtl.min.css') !!}" rel="stylesheet">

    <link href="{!! asset('assets/adminui/plugins/bower_components/sweetalert/sweetalert.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/select2/select2.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/assets/css/animate.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/assets/css/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/owl.carousel/owl.carousel.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/owl.carousel/owl.theme.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/toast-master/css/jquery.toast.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/jqueryui/jquery-ui.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/switchery/dist/switchery.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/datepicker/v2/persian-datepicker.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/front/css/zm_style.css') !!}" id="theme"  rel="stylesheet">
    <link href="{!! asset('assets/front/css/zm_responsive.css') !!}" id="theme"  rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{!! asset('assets/adminui/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/jqueryui/jquery-ui.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/sweetalert/sweetalert.min.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/select2/select2.min.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/owl.carousel/owl.carousel.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/toast-master/js/jquery.toast.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/switchery/dist/switchery.min.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/datepicker/v2/persian-date.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/datepicker/v2/persian-datepicker.js') !!}"></script>


</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <header class="zm-header">
        <div class="container">
            <div class="zm-logo pull-right">
                <a href="{{ url('') }}"><img src="{!! asset('assets/images/logo.png') !!}"></a>
            </div>
            <ul class="nav menu">
                @guest
                    <li><a class="link_login" href="{{ url('login') }}">ورود</a></li>
                    <li><a class="link_register" href="{{ url('register') }}">ثبت نام</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu animated flipInY" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->role == 'amel')

                                <li><a href="{{ url('cp-amel') }}">سفارشات عامل</a></li>
                                <li><a href="{{ url('cp-amel/bill') }}">صورت حساب مالی</a></li>
                                <li role="separator" class="divider"></li>

                            @elseif(Auth::user()->role == 'labrator')

                                <li><a href="{{ url('cp-labrator') }}">سفارشات لابراتور</a></li>
                                <li><a href="{{ url('cp-labrator/bill') }}">صورت حساب مالی</a></li>
                                <li role="separator" class="divider"></li>

                            @elseif(Auth::user()->role == 'bonakdar')

                                <li><a href="{{ url('cp-bonakdar') }}">سفارشات بنکدار</a></li>
                                <li><a href="{{ url('cp-bonakdar/bill') }}">صورت حساب مالی</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ url('cp-bonakdar/products/lens') }}">نمایش همه لنز های من</a></li>
                                <li><a href="{{ url('cp-bonakdar/product/add/lens') }}">افزودن لنز</a></li>
                                <li><a href="{{ url('cp-bonakdar/products/optical-glass') }}">نمایش همه عدسی های من</a></li>
                                <li><a href="{{ url('cp-bonakdar/product/add/optical-glass') }}">افزودن عدسی</a></li>

                            @elseif(Auth::user()->role == 'admin')

                                <li><a href="{{ url('cp-manager') }}">پنل مدیریت</a></li>
                                <li role="separator" class="divider"></li>

                            @endif
                            <li><a href="{{ url('order/new') }}">ثبت سفارش جدید</a></li>
                            <li><a href="{{ url('dashboard') }}">سفارش های من</a></li>
                            <li><a href="{{ url('user/profile') }}">حساب کاربری</a></li>
                            <li><a href="{{ url('user/charge') }}">افزایش اعتبار</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <?php
                                $order_count = App\Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                                    ->join('products', 'products.id' , '=', 'orders.product_id' )
                                    ->where('orders.status' , 'pending_pay')
                                    ->where('orders.user_id' , Auth::user()->id)
                                    ->orderBy('orders.created_at', 'desc')
                                    ->count();
                                ?>
                                <a href="{{ url('order/invoice') }}">سبد خرید <span class="pull-right notification_conter {{ ($order_count > 0) ? 'read' : '' }}">{{ $order_count }}</span></a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">خروج از حساب کاربری</a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    @endguest
                <li class="hidden-xs hidden-sm"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                <li class="hidden-xs hidden-sm"><a href="{{ url('post/قوانین-و-مقررات') }}">قوانین و مقررات</a></li>
                <li class="hidden-xs hidden-sm"><a href="{{ url('post/درباره-ما') }}">درباره ما</a></li>
                <li class="hidden-xs hidden-sm"><a href="{{ url('post/تماس-با-ما') }}">تماس با ما</a></li>
            </ul>
        </div>
    </header>
    
    <div class="main_content">
        @yield('content')
    </div>

    <footer class="zm-footer">
        <div class="center_section_footer">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-4 pading">
                        <span class="title">ما را در شبکه های اجتماعی دنبال کنید</span>
                        <span class="sub_title">گفتگو در مورد لنز و عدسی</span>

                        <div class="social_footer">
                            <ul>
                                <li><a href="{{ SettingController::get_package_optien('facebook') }}"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ SettingController::get_package_optien('instagram') }}"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="{{ SettingController::get_package_optien('linkedin') }}"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 pading center">
                        <span class="title">ثبت نام</span>
                        <span class="sub_title">بازاری برای خرید لنز و عدسی</span>

                        <div class="address_footer">
                            <a class="button_register" href="{{ url('register') }}">ایجاد حساب کاربری</a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 pading">
                        <span class="title">تماس با ما</span>

                        <div class="address_footer">
                            <span class="address"></span>
                            <span class="phone">تلفن : {{ SettingController::get_package_optien('phone') }}</span>
                            <span class="email">ایمیل : {{ SettingController::get_package_optien('email') }}</span>
                        </div>

                        <img src="https://trustseal.enamad.ir/logo.aspx?id=93856&amp;p=e4iqLQUpw5gvMPIJ" alt="" onclick="window.open(&quot;https://trustseal.enamad.ir/Verify.aspx?id=93856&amp;p=e4iqLQUpw5gvMPIJ&quot;, &quot;Popup&quot;,&quot;toolbar=no, location=no, statusbar=no, menubar=no, scrollbars=1, resizable=0, width=580, height=600, top=30&quot;)" style="cursor:pointer" id="e4iqLQUpw5gvMPIJ">

                        <img id='jxlznbqergvjjxlzjzpesizp' style='cursor:pointer' onclick='window.open("https://logo.samandehi.ir/Verify.aspx?id=123179&p=rfthuiwkxlaorfthjyoepfvl", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt='logo-samandehi' src='https://logo.samandehi.ir/logo.aspx?id=123179&p=nbpdodrfqftinbpdyndtbsiy'/>

                    </div>
                </div>
            </div>
        </div>
        <div class="copy_right">{{ date('Y') }} -  تمامی حقوق این وب سایت برای {{ config('app.name', 'safir') }} محفوظ است. {{--<a target="_blank" href="http://www.zafre.com">طراحی سایت</a> توسط زفره مدیا--}}</div>
    </footer>

<!-- /#wrapper -->
<!-- jQuery -->
<script src="{!! asset('assets/adminui/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') !!}"></script>
<script src="{!! asset('assets/front/js/zm_style.js') !!}"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $('.sa-warning').click(function(e){
            e.preventDefault();
            var hreflink = $(this).attr('href');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function(){
                window.location.href = hreflink;
            });
        });

        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });


        $(".observer").persianDatepicker({
            initialValue: false,
            format: 'YYYY/MM/DD'
        });


        $("select").select2();

    });

</script>


</body>
</html>

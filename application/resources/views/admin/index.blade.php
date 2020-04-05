@extends('admin.layouts.app')

@section('page_name', 'داشبورد')

@section('content')

    <div class="row">
        <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">تعداد کاربران</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash"></div>
                    </li>
                    <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success">{{ \App\User::where('role' , 'user')->count() }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">تعداد عامل ها</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash2"></div>
                    </li>
                    <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple">{{ \App\User::where('role' , 'amel')->count() }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">تعداد فروشگاه</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash3"></div>
                    </li>
                    <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info">{{ \App\User::where('role' , 'bonakdar')->count() }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">جمع کل فروش</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash4"></div>
                    </li>
                    <li class="text-right"><span class="text-danger" style="font-size: 18px">{{ number_format(\App\transaction::where('type' , 'order')->where('status' , 'paid')->sum('orginal_price')) }}</span></li>
                </ul>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                        <h3 class="box-title">سفارشات</h3>
                        <p class="m-t-30">مشاهده همه سفارشات دریافتی در از نرم افزار.</p>
                        <p><br/>
                        <a href="{{ url('cp-manager/orders?filter_start_date=' . jdate('Y/m/d')) }}" class="btn btn-block btn-success btn-rounded">سفارشات امروز</a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                        <h3 class="box-title">کاربران</h3>
                        <p class="m-t-30">مشاهده همه کاربران موجود</p>
                        <p><br/>
                        <a href="{{ url('cp-manager/users') }}" class="btn btn-block btn-info btn-rounded">کاربران</a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                        <h3 class="box-title">تحویل فوری یا عادی</h3>
                        <p class="m-t-30">مشاهده تنظیمات مربوط به تحویل</p>
                        <p><br/>
                        <a href="{{ url('cp-manager/calender/normal-delivery') }}" class="btn btn-block btn-warning btn-rounded">تنظیمات</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

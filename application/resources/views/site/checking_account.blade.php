@extends('site.layouts.app')

@section('content')
<div class="box_color_pages">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs-12 guidance">

                <div class="auth__guidance guidance noback">

                    <div class="guidance__rules">
                        <h2>حساب کاربری شما در حال تایید توسط تیم بازار اپتیک می باشد</h2>
                        <p>بعد از تایید حساب کاربری سایر بخش ها برای شما قابل دسترسی می شود.</p>
                        <p>برای مشاهده اطلاعات حساب کاربری به بخش <a style="color: #feab00" href="{{ url('user/profile') }}">حساب کاربری</a> مراجعه کنید</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="zm_baner_index">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="box_baner">
                        <img src="{!! asset('assets/images/1535.jpg') !!}">
                        <div class="hb-inner">
                            <div class="container">
                                <h1>عدسی ها</h1>
                                <a href="#">اطلاعات بیشتر</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="box_baner">
                        <img src="{!! asset('assets/images/mak.jpg') !!}">
                        <div class="hb-inner">
                            <div class="container">
                                <h1>لنز ها</h1>
                                <a href="#">اطلاعات بیشتر</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

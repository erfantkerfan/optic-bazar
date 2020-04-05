@extends('site.layouts.app')

@section('content')
<div class="box_color_pages">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-xs-12">
                <div class="card">

                    <div class="row">
                        <div class="error clearfix">
                            @if(Session::has('error'))
                                <div class="alert alert-danger" style="margin-top: -30px;">{{ Session::get('error') }}</div>
                            @endif

                            @if(Session::has('success'))
                                <div class="alert alert-success" style="margin-top: -30px;">{{ Session::get('success') }}</div>
                            @endif

                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="mobile" class="col-sm-4 col-form-label text-md-right">موبایل</label>

                                <div class="col-md-8">
                                    <input id="mobile" type="text" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile') }}" autocomplete="off"  autofocus>

                                    @if ($errors->has('mobile'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">رمز عبور</label>

                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <a class="btn btn-link pull-right" style="position: relative; z-index: 99" href="{{ route('password.request') }}">فراموشی رمز عبور</a>
                                    <div class="checkbox info">
                                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember"> مرا به خاطر بسپار
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-info">ورود به حساب کاربری</button>

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-xs-12 guidance">

                <div class="auth__guidance guidance noback">

                    <div class="guidance__thumb">
                        <svg width="173px" height="161px" viewBox="0 0 173 161" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Signup-Copy" transform="translate(-393.000000, -279.000000)" fill="#E6ECEC">
                                    <path d="M537.376029,307.3195 L537.376029,279.59095 L526.892629,279.59095 L526.892629,307.31735 L499.168379,307.31735 L499.168379,317.7986 L526.894779,317.7986 L526.894779,345.525 L537.380329,345.525 L537.380329,317.80075 L565.102429,317.80075 L565.102429,307.3195 L537.376029,307.3195 Z M451.320129,358.8722 C467.823529,358.8722 481.200829,342.40965 481.200829,322.10075 C481.200829,301.7897 467.821379,289.9217 451.320129,289.9217 C434.818879,289.9217 421.443729,301.7897 421.443729,322.10075 C421.445879,342.40965 434.818879,358.8722 451.320129,358.8722 L451.320129,358.8722 Z M452.165079,374.6618 C451.881279,374.6618 451.599629,374.67685 451.320129,374.6833 C451.032029,374.67685 450.761129,374.6618 450.473029,374.6618 C420.693379,374.6618 396.211329,384.1562 393.302379,410.83985 C393.229279,411.53 393.160479,417.37155 393.102429,420.51915 C409.134979,432.8279 428.611829,439.4112 451.317979,439.40905 C473.776879,439.40905 493.574079,432.58925 509.535679,420.51915 C509.479779,417.37155 509.402379,411.53 509.331429,410.83985 C506.424629,384.1562 481.944729,374.6618 452.165079,374.6618 L452.165079,374.6618 Z" id="add-user-icon"></path>
                                </g>
                            </g>
                        </svg>
                    </div>

                    <div class="guidance__rules">
                        <p>سریع تر و ساده تر خرید کنید</p>
                        <p>به سادگی سوابق خرید و فعالیت های خود را مدیریت کنید</p>
                        <p>لیست علاقمندی های خود را بسازید و تغییرات آنها را دنبال کنید</p>
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

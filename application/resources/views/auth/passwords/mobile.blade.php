@extends('site.layouts.app')

@section('content')
<div class="box_color_pages">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-xs-12">
                <div class="card" style="margin-top: 80px;">

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
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="mobile" class="col-md-2 col-form-label text-md-right">موبایل</label>

                                <div class="col-md-7">
                                    <input id="mobile" type="text" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile') }}" autocomplete="off"  autofocus>

                                    @if ($errors->has('mobile'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" style="padding: 8px; width: 100%" class="btn btn-info">ارسال رمز جدید</button>
                                </div>

                            </div>

                        </form>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-xs-12 guidance">

                <div class="auth__guidance guidance noback">

                    <div class="guidance__rules">
                        <p>فراموشی رمز عبور</p>
                        <p>جهت دریافت رمز جدید شماره همراه خود را وارد کنید.</p>
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

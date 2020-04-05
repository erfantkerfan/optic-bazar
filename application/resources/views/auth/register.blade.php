@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages">
        <div class="container">
            <div class="row justify-content-center" style="background: #f7f7f7;">
                <div class="col-md-6 col-xs-12" style="background: #fff;">
                    <div class="card" style="margin-top: 30px; ">

                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="role" class="col-md-4 col-form-label text-md-right">نوع کاربری</label>

                                    <div class="col-md-8">
                                        <select  id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role">
                                            <option value="user" {{ (old('role') == 'user') ? 'selected' : '' }}>فروشگاه عینک</option>
                                            <option value="labrator" {{ (old('role') == 'labrator') ? 'selected' : '' }}>لابراتور</option>
                                            <option value="amel" {{ (old('role') == 'amel') ? 'selected' : '' }}>عامل</option>
                                            <option value="bonakdar" {{ (old('role') == 'bonakdar') ? 'selected' : '' }}>بنکدار</option>
                                        </select>

                                        @if ($errors->has('role'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">نام و نام خانوادگی</label>

                                    <div class="col-md-8">
                                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">پست الکترونیکی</label>

                                    <div class="col-md-8">
                                        <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" >

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile" class="col-md-4 col-form-label text-md-right">موبایل</label>

                                    <div class="col-md-8">
                                        <input id="mobile" type="text" class="zm_comma_number form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile') }}" >

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
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">تکرار رمز عبور</label>

                                    <div class="col-md-8">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="checkbox checkbox-info">
                                        <div style="margin-right: 15px">
                                            <input type="checkbox" id="privacy_policy" name="privacy_policy" {{ ( old('privacy_policy') == 'on') ? "checked" : "" }}>
                                            <label style="margin-right: -7px; padding-right: 30px" for="privacy_policy"><a href="{{ url('post/قوانین-و-مقررات') }}">قوانین و مقررات</a> را خواندم و قبول دارم.</label>
                                        </div>
                                    </div>
                                    @if ($errors->has('privacy_policy'))
                                        <span class="invalid-feedback">
                                                <strong>{{ $errors->first('privacy_policy') }}</strong>
                                            </span>
                                    @endif
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-info">ایجاد حساب کاربری</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a class="pull-right" style="padding: 0; line-height: 35px" href="{{ url('login') }}">قبلا ثبت نام کرده ام / ورود</a>
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

                <div class="clearfix"></div>
            </div>
        </div>

        <div class="clearfix"></div>

    </div>
@endsection

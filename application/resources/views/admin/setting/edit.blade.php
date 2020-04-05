@extends('admin.layouts.app')

@section('page_name', 'ویرایش تنظیمات')

@section('content')

    <?php use \App\Http\Controllers\SettingController; ?>

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="title_about" class="col-md-4 col-form-label text-md-right">عنوان درباره ما صفحه اصلی</label>

                <div class="col-md-6">
                    <input id="title_about" type="text" class="form-control{{ $errors->has('title_about') ? ' is-invalid' : '' }}" name="title_about" value="{{ old('title_about', SettingController::get_package_optien('title_about')) }}">

                    @if ($errors->has('title_about'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('title_about') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="text_about" class="col-md-4 col-form-label text-md-right">متن درباره ما صفحه اصلی</label>

                <div class="col-md-6">

                    <textarea id="text_about" rows="18"  class="form-control textarea_editor {{ $errors->has('text_about') ? ' is-invalid' : '' }}" name="text_about" >{{ old('text_about', SettingController::get_package_optien('text_about')) }}</textarea>

                    @if ($errors->has('text_about'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('text_about') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label for="title_baner_order" class="col-md-4 col-form-label text-md-right">عنوان ثبت سفارش</label>

                <div class="col-md-6">
                    <input id="title_baner_order" type="text" class="form-control{{ $errors->has('title_baner_order') ? ' is-invalid' : '' }}" name="title_baner_order" value="{{ old('title_baner_order', SettingController::get_package_optien('title_baner_order')) }}">

                    @if ($errors->has('title_baner_order'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('title_baner_order') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="text_baner_order" class="col-md-4 col-form-label text-md-right">متن ثبت سفارش</label>

                <div class="col-md-6">
                    <input id="text_baner_order" type="text" class="form-control{{ $errors->has('text_baner_order') ? ' is-invalid' : '' }}" name="text_baner_order" value="{{ old('text_baner_order', SettingController::get_package_optien('text_baner_order')) }}">

                    @if ($errors->has('text_baner_order'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('text_baner_order') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label for="phone" class="col-md-4 col-form-label text-md-right">تلفن</label>

                <div class="col-md-6">
                    <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone', SettingController::get_package_optien('phone')) }}">

                    @if ($errors->has('phone'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">ایمیل</label>

                <div class="col-md-6">
                    <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', SettingController::get_package_optien('email')) }}">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label for="facebook" class="col-md-4 col-form-label text-md-right">facebook</label>

                <div class="col-md-6">
                    <input id="facebook" type="text" class="form-control{{ $errors->has('facebook') ? ' is-invalid' : '' }}" name="facebook" value="{{ old('facebook', SettingController::get_package_optien('facebook')) }}">

                    @if ($errors->has('facebook'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('facebook') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="instagram" class="col-md-4 col-form-label text-md-right">instagram</label>

                <div class="col-md-6">
                    <input id="instagram" type="text" class="form-control{{ $errors->has('instagram') ? ' is-invalid' : '' }}" name="instagram" value="{{ old('instagram', SettingController::get_package_optien('instagram')) }}">

                    @if ($errors->has('instagram'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('instagram') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="linkedin" class="col-md-4 col-form-label text-md-right">linkedin</label>

                <div class="col-md-6">
                    <input id="linkedin" type="text" class="form-control{{ $errors->has('linkedin') ? ' is-invalid' : '' }}" name="linkedin" value="{{ old('linkedin', SettingController::get_package_optien('linkedin')) }}">

                    @if ($errors->has('linkedin'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('linkedin') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت تنظیمات</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

@endsection
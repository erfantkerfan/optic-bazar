@extends('admin.layouts.app')

@section('page_name', 'افزودن کوپن')

@section('content')

    <form method="post" action="" style="direction: rtl">
        <div class="white-box">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="coupon_key" class="col-md-2 col-form-label text-md-right"> عنوان</label>

                <div class="col-md-8">
                    <input id="coupon_key" type="text" style="font-family: Tahoma" class="form-control coupon_code{{ $errors->has('name') ? ' is-invalid' : '' }}" name="coupon_key" value="{{ old('coupon_key') }}" autofocus>

                    @if ($errors->has('coupon_key'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('coupon_key') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="col-md-1">
                    <button type="button" id="generate_code" style="width: 100%;padding: 9px 0;" class="btn btn-info">ایجاد</button>
                </div>
            </div>


            <div class="form-group row">
                <label for="discount_type" class="col-md-2 col-form-label text-md-right"> نحوه تخفیف</label>

                <div class="col-md-9">
                    <select id="discount_type" class="select2 form-control{{ $errors->has('discount_type') ? ' is-invalid' : '' }}" name="discount_type">
                        <option value="percent" {{ (old('discount_type') == 'percent') ? 'selected' : '' }}>تخفیف درصدی</option>
                        <option value="fixed_product" {{ (old('fixed_product') == 'fixed_product') ? 'selected' : '' }}>تخفيف ثابت محصول</option>
                    </select>

                    @if ($errors->has('discount_type'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('discount_type') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label for="coupon_amount" class="col-md-2 col-form-label text-md-right"> مبلغ کوپن</label>

                <div class="col-md-9">
                    <input id="coupon_amount" type="text" class="form-control{{ $errors->has('coupon_amount') ? ' is-invalid' : '' }}" name="coupon_amount" value="{{ old('coupon_amount') }}">

                    @if ($errors->has('coupon_amount'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('coupon_amount') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label for="start_date" class="col-md-2 col-form-label text-md-right">تاریخ شروع</label>

                <div class="col-md-9">

                    <div class="input-group">
                        <input type="text" class="form-control observer{{ $errors->has('start_date') ? ' is-invalid' : '' }}" placeholder="از همین لحظه" id="start_date" name="start_date" autocomplete="off" value="{{ old('start_date') }}">
                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                    </div>
                    @if ($errors->has('start_date'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('start_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="expiry_date" class="col-md-2 col-form-label text-md-right">تاریخ انقضا</label>

                <div class="col-md-9">

                    <div class="input-group">
                        <input type="text" class="form-control observer{{ $errors->has('expiry_date') ? ' is-invalid' : '' }}" placeholder="yyyy-mm-dd" id="expiry_date" name="expiry_date" autocomplete="off" value="{{ old('expiry_date') }}">
                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                    </div>
                    @if ($errors->has('expiry_date'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('expiry_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="white-box">


            <div class="form-group row">
                <label for="minimum_amount" class="col-md-2 col-form-label text-md-right"> حداقل هزینه</label>

                <div class="col-md-9">
                    <input id="minimum_amount" type="text" class="form-control{{ $errors->has('minimum_amount') ? ' is-invalid' : '' }}" placeholder="هیچ حداقلی وجود ندارد" name="minimum_amount" value="{{ old('minimum_amount') }}">

                    @if ($errors->has('minimum_amount'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('minimum_amount') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label for="maximum_amount" class="col-md-2 col-form-label text-md-right">حداکثر هزینه</label>

                <div class="col-md-9">
                    <input id="maximum_amount" type="text" class="form-control{{ $errors->has('maximum_amount') ? ' is-invalid' : '' }}" placeholder="بدون محدودیت" name="maximum_amount" value="{{ old('maximum_amount') }}">

                    @if ($errors->has('maximum_amount'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('maximum_amount') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            {{--<div class="form-group row">
                <label for="exclude_sale" class="col-md-2 col-form-label text-md-right">به‌جز محصولات فروش ویژه</label>

                <div class="col-md-9">
                    <div class="checkbox checkbox-info">
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="checkbox" id="exclude_sale" name="exclude_sale" {{ ( old('exclude_sale') == 'on') ? "checked" : "" }}>
                            <label for="exclude_sale">بله</label>
                        </div>
                    </div>
                    @if ($errors->has('exclude_sale'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('exclude_sale') }}</strong>
                        </span>
                    @endif
                </div>
            </div>--}}

            <div class="form-group row">
                <label for="disposable" class="col-md-2 col-form-label text-md-right">قابلیت استفاده برای یک بار</label>

                <div class="col-md-9">
                    <div class="checkbox checkbox-info">
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="checkbox" id="disposable" name="disposable" {{ ( old('disposable') == 'on') ? "checked" : "" }}>
                            <label for="disposable">بله</label>
                        </div>
                    </div>
                    @if ($errors->has('disposable'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('disposable') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="white-box">


            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right"> محصولات</label>

                <div class="col-md-9">

                    <select multiple data-role="tagsinput" class="form-control{{ $errors->has('product_ids') ? ' is-invalid' : '' }}" name="product_ids[]"></select>

                    @if ($errors->has('product_ids'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('product_ids') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right"> به جز این محصولات</label>

                <div class="col-md-9">

                    <select multiple data-role="tagsinput" class="form-control{{ $errors->has('exclude_product_ids') ? ' is-invalid' : '' }}" name="exclude_product_ids[]"></select>

                    @if ($errors->has('exclude_product_ids'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('exclude_product_ids') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <hr>


            <div class="form-group row">
                <label for="product_categories" class="col-md-2 col-form-label text-md-right"> دسته بندی</label>

                <div class="col-md-9">

                    <select id="product_categories" class="select2 form-control{{ $errors->has('product_categories') ? ' is-invalid' : '' }}" name="product_categories">
                        <option value="all">همه دسته بندی ها</option>
                        <option value="lens" {{ (old('product_categories') == 'lens') ? 'selected' : '' }}>لنز</option>
                        <option value="optical-glass" {{ (old('product_categories') == 'optical-glass') ? 'selected' : '' }}>عدسی</option>
                    </select>

                    @if ($errors->has('product_categories'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('product_categories') }}</strong>
                        </span>
                    @endif

                </div>
            </div>


            {{--<hr>


            <div class="form-group row">
                <label for="customer_phone" class="col-md-2 col-form-label text-md-right"> محدودیت‌های شماره موبایل</label>

                <div class="col-md-9">
                    <input id="customer_phone" type="text" class="form-control{{ $errors->has('customer_phone') ? ' is-invalid' : '' }}" name="customer_phone" value="{{ old('customer_phone') }}">

                    @if ($errors->has('customer_phone'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('customer_phone') }}</strong>
                        </span>
                    @endif
                </div>
            </div>--}}

            <div class="clearfix"></div>
        </div>

        <div class="white-box">

            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label text-md-right">توضیحات</label>

                <div class="col-md-9">
                    <textarea id="description" rows="8"  class="form-control textarea_editor {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" >{{ old('description') }}</textarea>

                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-9 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت کد تخفیف</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

    </form>


    <script>
        $(document).ready(function($) {
            $('#generate_code').on('click' , function (e) {
                e.preventDefault();
                $('.coupon_code').val("{{ str_random(8) }}")
            });
        });
    </script>


@endsection
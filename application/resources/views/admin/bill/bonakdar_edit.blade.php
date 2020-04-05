@extends('admin.layouts.app')

@section('page_name', 'ویرایش صورت حساب مالی بنکداران ')

@section('content')

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">شماره صورت حساب</label>

                <div class="col-md-10">
                    BOP-{{ $request->transactions_id }}-{{ $request->order_id }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">نام و نام خانوادگی	</label>

                <div class="col-md-10">
                    {{ $request->user_name }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">تاریخ سفارش</label>

                <div class="col-md-10">
                    {{ jdate('Y/m/d', strtotime($request->transactions_created_at)) }}
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">مبلغ کل (تومان)</label>

                <div class="col-md-10">
                    {{ number_format($request->price) }}
                </div>
            </div>

            <div class="form-group row">
                <label for="status" class="col-md-2 col-form-label text-md-right">وضعیت</label>

                <div class="col-md-10">
                    <div class="radio radio-info">
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="status_active" name="status" value="0" {{ ( old('status', $request->checkout_bonakdar) == 0) ? "checked" : "" }}>
                            <label for="status_active">بستانکار</label>
                        </div>
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="status_inactive" name="status" value="1" {{ ( old('status', $request->checkout_bonakdar) == 1) ? "checked" : "" }}>
                            <label for="status_inactive">بدهکار</label>
                        </div>
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="status_not_active" name="status" value="2" {{ ( old('status', $request->checkout_bonakdar) == 2) ? "checked" : "" }}>
                            <label for="status_not_active">تسویه شده</label>
                        </div>
                    </div>
                    @if ($errors->has('status'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label text-md-right">شرح صورت حساب</label>

                <div class="col-md-8">
                    <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description', $request->description) }}" autofocus>

                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row mb-0">
                <div class="col-md-10 offset-md-2">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت صورت حساب </button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>
@endsection
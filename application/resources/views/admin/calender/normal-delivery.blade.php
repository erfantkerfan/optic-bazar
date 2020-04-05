@extends('admin.layouts.app')

@section('page_name', 'تحویل فوری یا عادی')

@section('content')

    <?php use \App\Http\Controllers\SettingController; ?>

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="row">

                <div class="col-xs-12 col-md-6">
                    <div class="form-group row">
                        <label for="calender_timepic_get" class="col-md-4 col-form-label text-md-right">اختلاف زمانی تحویل فریم (ساعت) </label>

                        <div class="col-md-8">
                            <input id="calender_timepic_get" type="number" min="0" class="form-control{{ $errors->has('calender_timepic_get') ? ' is-invalid' : '' }}" name="calender_timepic_get" value="{{ old('calender_timepic_get', SettingController::get_package_optien('calender_timepic_get')) }}">

                            @if ($errors->has('calender_timepic_get'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('calender_timepic_get') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <br>


                <div class="col-xs-12 col-md-6">
                    <div class="form-group row">
                        <label for="calender_timepic_send_notlathe" class="col-md-4 col-form-label text-md-right">اختلاف زمانی تحویل سفارش - بدون تراشکاری (ساعت) </label>

                        <div class="col-md-8">
                            <input id="calender_timepic_send_notlathe" type="number" min="0" class="form-control{{ $errors->has('calender_timepic_send_notlathe') ? ' is-invalid' : '' }}" name="calender_timepic_send_notlathe" value="{{ old('calender_timepic_send_notlathe', SettingController::get_package_optien('calender_timepic_send_notlathe')) }}">

                            @if ($errors->has('calender_timepic_send_notlathe'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('calender_timepic_send_notlathe') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group row">
                        <label for="calender_timepic_send_lathe" class="col-md-4 col-form-label text-md-right">اختلاف زمانی تحویل سفارش - با تراشکاری (ساعت) </label>

                        <div class="col-md-8">
                            <input id="calender_timepic_send_lathe" type="number" min="0" class="form-control{{ $errors->has('calender_timepic_send_lathe') ? ' is-invalid' : '' }}" name="calender_timepic_send_lathe" value="{{ old('calender_timepic_send_lathe', SettingController::get_package_optien('calender_timepic_send_lathe')) }}">

                            @if ($errors->has('calender_timepic_send_lathe'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('calender_timepic_send_lathe') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <br>

                <div class="col-xs-12 col-md-6">

                    <div class="form-group row">
                        <label for="calender_order_counter_get" class="col-md-4 col-form-label text-md-right">تعداد سفارش مجاز برای دریافت فریم در هر ساعت</label>

                        <div class="col-md-8">
                            <input id="calender_order_counter_get" type="number" min="0" class="form-control{{ $errors->has('calender_order_counter_get') ? ' is-invalid' : '' }}" name="calender_order_counter_get" value="{{ old('calender_order_counter_get', SettingController::get_package_optien('calender_order_counter_get')) }}">

                            @if ($errors->has('calender_order_counter_get'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('calender_order_counter_get') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-xs-12 col-md-6">

                    <div class="form-group row">
                        <label for="calender_order_counter_send" class="col-md-4 col-form-label text-md-right">تعداد سفارش مجاز برای ارسال سفارش در هر ساعت</label>

                        <div class="col-md-8">
                            <input id="calender_order_counter_send" type="number" min="0" class="form-control{{ $errors->has('calender_order_counter_send') ? ' is-invalid' : '' }}" name="calender_order_counter_send" value="{{ old('calender_order_counter_send', SettingController::get_package_optien('calender_order_counter_send')) }}">

                            @if ($errors->has('calender_order_counter_send'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('calender_order_counter_send') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="clearfix"></div>

            </div>

            <hr>

            <div class="form-group row">

                <div class="col-md-6">
                    <label>ساعت های مجاز برای تحویل فریم به پیک</label>
                    <br>
                    <br>
                    <div class="add_get_time_list">
                        <div class="row">
                            <div class="col-xs-4">
                                <input id="get_time_set" type="number" min="1" class="form-control" placeholder="ساعت را وارد کنید">
                            </div>
                            <div class="col-xs-6">
                                <div class="tags-default">
                                    <input type="text" value="" id="get_time_unit" name="unit" data-role="tagsinput" placeholder="مناطق را وارد کنید"/>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" style="width: 100%" id="add_get_time_set" class="btn btn-warning">افزودن ساعت</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list get_list">

                            @if($calender_order_get_time)
                                @foreach($calender_order_get_time as $item)
                                    <div class="item_time">
                                        <span>ساعت {{ $item->time }} تا {{ $item->time + old('calender_timepic_get', SettingController::get_package_optien('calender_timepic_get')) }} - مناطق : {{ $item->unit }}
                                        </span>
                                        <input type="hidden" name="calender_order_get_time[]" value="{{ $item->time }}">
                                        <input type="hidden" name="calender_order_get_time_unit[]" value="{{ $item->unit }}">
                                        <span class="remove_time pull-right">
                                        <i class="fa fa-remove"></i>
                                        </span>
                                    </div>
                                @endforeach
                            @endif

                        </div>


                    </div>
                </div>

                <div class="col-md-6">
                    <label>ساعت های مجاز برای تحویل سفارش انجام شده به فروشگاه</label>
                    <br>
                    <br>
                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-3">
                                <input id="time_set" type="number" min="1" class="form-control" placeholder="ساعت را وارد کنید">
                            </div>
                            <div class="col-xs-9">
                                <div class="tags-default">
                                    <input type="text" value="" id="time_unit" name="unit" data-role="tagsinput" placeholder="مناطق را وارد کنید"/>
                                </div>
                            </div>
                            <div class="col-xs-6" style="margin-top: 10px">
                                <input type="number" id="time_price" min="0" max="100" class="form-control" name="price" value="" placeholder="قیمت در صورت تحویل فوری (٪)">
                            </div>
                            <div class="col-xs-6" style="margin-top: 10px">
                                <button type="button" style="width: 100%" id="add_time_set" class="btn btn-warning">افزودن ساعت</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list send_list">

                            @if($calender_order_time)
                                @foreach($calender_order_time as $item)
                                    <div class="item_time">
                                        <span>ساعت {{ $item->time }} - مناطق : {{ $item->unit }} - {{ ($item->price) ? ' * تحویل فوری ' . $item->price . ' % افزایش قیمت' : ' * تحویل عادی' }}
                                        </span>
                                        <input type="hidden" name="calender_order_time[]" value="{{ $item->time }}">
                                        <input type="hidden" name="calender_order_time_unit[]" value="{{ $item->unit }}">
                                        <input type="hidden" name="calender_order_time_price[]" value="{{ $item->price }}">
                                        <span class="remove_time pull-right">
                                        <i class="fa fa-remove"></i>
                                        </span>
                                    </div>
                                @endforeach
                            @endif

                        </div>


                    </div>
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


    <script>

        $('.remove_time').click(function () {
            $(this).parent().remove();
        });

        $('#add_get_time_set').click(function () {
            var data_set = $('#get_time_set').val();
            var data_unit = $('#get_time_unit').val();
            var data_time_pic = ($('#calender_timepic_get').val()) ? $('#calender_timepic_get').val() : '{{ SettingController::get_package_optien('calender_timepic_get') }}';

            if(!data_time_pic){
                alert('اختلاف زمانی تحویل فریم را ابتدا وارد کنید');
                return false;
            }

            if(!data_unit){
                alert('لطفا مناطق را وارد کنید');
                return false;
            }

            if(!data_set){
                alert('لطفا ساعت را وارد کنید');
                return false;
            }

            data_time_pic = parseInt(data_set) + parseInt(data_time_pic);

            $('.full_time_list.get_list').append('<div class="item_time"><span>ساعت '+data_set + ' تا ' + data_time_pic +' - مناطق : '+data_unit+'</span><input type="hidden" name="calender_order_get_time[]" value="'+data_set+'"><input type="hidden" name="calender_order_get_time_unit[]" value="'+data_unit+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#get_time_set').val('');
        });

        $('#add_time_set').click(function () {
            var data_set = $('#time_set').val();
            var data_unit = $('#time_unit').val();
            var data_price = $('#time_price').val();

            if(!data_unit){
                alert('لطفا مناطق را وارد کنید');
                return false;
            }

            if(!data_set){
                alert('لطفا ساعت را وارد کنید');
                return false;
            }

            var data_price_text = (data_price) ? ' * تحویل فوری ' + data_price + ' % افزایش قیمت' : ' * تحویل عادی';

            $('.full_time_list.send_list').append('<div class="item_time"><span>ساعت '+data_set+' - مناطق : '+data_unit+' '+data_price_text+'</span><input type="hidden" name="calender_order_time[]" value="'+data_set+'"><input type="hidden" name="calender_order_time_unit[]" value="'+data_unit+'"><input type="hidden" name="calender_order_time_price[]" value="'+data_price+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#time_set').val('');
        });

    </script>

@endsection
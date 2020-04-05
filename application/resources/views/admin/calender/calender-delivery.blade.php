@extends('admin.layouts.app')

@section('page_name', 'تحویل تقویمی')

@section('content')

    <?php use \App\Http\Controllers\SettingController; ?>

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="row">

                <div class="col-xs-12">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right"> انتخاب مناطق</label>

                        <div class="col-md-9">

                            <select multiple data-role="tagsinput" class="form-control{{ $errors->has('areas') ? ' is-invalid' : '' }}" name="areas[]">

                                @if(SettingController::get_package_optien('calender_dv_areas'))
                                    @foreach(json_decode(old('calender_dv_areas', SettingController::get_package_optien('calender_dv_areas'))) as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                @endif

                            </select>

                            @if ($errors->has('areas'))
                                <span class="invalid-feedback">
                            <strong>{{ $errors->first('areas') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-xs-12">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">طرفیت مجاز در هر بازه</label>


                        <div class="col-md-3">

                            <input id="calender_dv_count" type="number" min="0" class="form-control{{ $errors->has('calender_dv_count') ? ' is-invalid' : '' }}" name="calender_dv_count" value="{{ old('calender_dv_count', SettingController::get_package_optien('calender_dv_count')) }}">

                            @if ($errors->has('calender_dv_count'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('calender_dv_count') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="clearfix"></div>
                <br>


                <div class="col-md-12">
                    <label>تعریف زمان ارسال</label>
                    <br>
                    <br>
                </div>


                <div class="col-md-12">
                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <input id="time_set" type="number" min="1" class="form-control" placeholder="ساعت را وارد کنید">
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <input id="time_date" type="text" class="form-control observer" placeholder="تاریخ را وارد کنید">
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <input type="number" id="time_price" min="-100" max="100" class="form-control" name="price" value="" placeholder="کاهش و افزایش قیمت (٪)">
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <button type="button" style="width: 100%" id="add_time_set" class="btn btn-warning">افزودن</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list send_list" style="margin-bottom: 20px">


                            @if(SettingController::get_package_optien('calender_getdv_time'))
                                @foreach(json_decode(SettingController::get_package_optien('calender_getdv_time')) as $item)
                                    <div class="item_time">
                                        <span>ساعت {{ $item->time }} - روز : {{ $item->date }}
                                            @if($item->price)
                                                {{ ($item->price > 0) ? " * " . $item->price . "% افزایش قیمت" : " * " . $item->price . "% کاهش قیمت" }}
                                            @endif
                                        </span>
                                        <input type="hidden" name="calender_getdv_time[]" value="{{ $item->time }}">
                                        <input type="hidden" name="calender_getdv_time_date[]" value="{{ $item->date }}">
                                        <input type="hidden" name="calender_getdv_time_price[]" value="{{ $item->price }}">
                                        <span class="remove_time pull-right">
                                        <i class="fa fa-remove"></i>
                                        </span>
                                    </div>
                                @endforeach
                            @endif


                        </div>


                    </div>
                </div>



                <div class="col-xs-12">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">اختلاف زمانی ارسال</label>


                        <div class="col-md-3">

                            <input id="calender_timepic_getdv" type="number" min="0" class="form-control{{ $errors->has('calender_timepic_getdv') ? ' is-invalid' : '' }}" name="calender_timepic_getdv" value="{{ old('calender_timepic_getdv', SettingController::get_package_optien('calender_timepic_getdv')) }}">

                            @if ($errors->has('calender_timepic_getdv'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('calender_timepic_getdv') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="clearfix"></div>
                <br>


                <div class="col-md-12">
                    <label>تعریف زمان تحویل</label>
                    <br>
                    <br>
                </div>


                <div class="col-md-12">
                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <input id="time_set_two" type="number" min="1" class="form-control" placeholder="ساعت را وارد کنید">
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <input id="time_date_two" type="text" class="form-control observer" placeholder="تاریخ را وارد کنید">
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <button type="button" style="width: 100%" id="add_time_set_two" class="btn btn-warning">افزودن</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list send_list_two" style="margin-bottom: 20px">


                            @if(SettingController::get_package_optien('calender_senddv_time'))
                                @foreach(json_decode(SettingController::get_package_optien('calender_senddv_time')) as $item)
                                    <div class="item_time">
                                        <span>ساعت {{ $item->time }} - روز : {{ $item->date }}
                                        </span>
                                        <input type="hidden" name="calender_senddv_time[]" value="{{ $item->time }}">
                                        <input type="hidden" name="calender_senddv_time_date[]" value="{{ $item->date }}">
                                        <span class="remove_time pull-right">
                                        <i class="fa fa-remove"></i>
                                        </span>
                                    </div>
                                @endforeach
                            @endif


                        </div>


                    </div>
                </div>



                <div class="col-xs-12">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">اختلاف زمانی ارسال</label>


                        <div class="col-md-3">

                            <input id="calender_timepic_senddv" type="number" min="0" class="form-control{{ $errors->has('calender_timepic_senddv') ? ' is-invalid' : '' }}" name="calender_timepic_senddv" value="{{ old('calender_timepic_senddv', SettingController::get_package_optien('calender_timepic_senddv')) }}">

                            @if ($errors->has('calender_timepic_senddv'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('calender_timepic_senddv') }}</strong>
                                </span>
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


        $('#add_time_set').click(function () {
            var data_set = $('#time_set').val();
            var data_date = $('#time_date').val();
            var data_price = $('#time_price').val();

            if(!data_date){
                alert('لطفا تاریخ را وارد کنید');
                return false;
            }

            if(!data_set){
                alert('لطفا ساعت را وارد کنید');
                return false;
            }

            var data_price_text = '';
            if(data_price){
                data_price_text = (data_price > 0) ? ' * ' + data_price + ' % افزایش قیمت' : ' * ' + data_price + ' % کاهش قیمت' ;
            }

            $('.full_time_list.send_list').append('<div class="item_time"><span>ساعت '+data_set+' - روز : '+data_date+' '+data_price_text+'</span><input type="hidden" name="calender_getdv_time[]" value="'+data_set+'"><input type="hidden" name="calender_getdv_time_date[]" value="'+data_date+'"><input type="hidden" name="calender_getdv_time_price[]" value="'+data_price+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#time_set').val('');
            $('#time_date').val('');
            $('#time_price').val('');
        });
    </script>

    <script>

        $(document).delegate('.remove_time', 'click', function (e) {
            $(this).parent().remove();
        });


        $('#add_time_set_two').click(function () {
            var data_set = $('#time_set_two').val();
            var data_date = $('#time_date_two').val();

            if(!data_date){
                alert('لطفا تاریخ را وارد کنید');
                return false;
            }

            if(!data_set){
                alert('لطفا ساعت را وارد کنید');
                return false;
            }

            $('.full_time_list.send_list_two').append('<div class="item_time"><span>ساعت '+data_set+' - روز : '+data_date+'</span><input type="hidden" name="calender_senddv_time[]" value="'+data_set+'"><input type="hidden" name="calender_senddv_time_date[]" value="'+data_date+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#time_set_two').val('');
            $('#time_date_two').val('');
        });
    </script>

@endsection
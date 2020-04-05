@extends('admin.layouts.app')

@section('page_name', 'ویرایش تحویل فوری یا عادی')

@section('content')

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}


            <div class="form-group row">
                <label for="areas" class="col-md-2 col-form-label text-md-right">انتخاب مناطق</label>

                <div class="col-md-8">

                    <select multiple data-role="tagsinput" class="form-control{{ $errors->has('areas') ? ' is-invalid' : '' }}" id="areas" name="areas[]">
                        @if(old('areas', json_decode($request->areas)))
                            @foreach(old('areas', json_decode($request->areas)) as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
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

            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">زمان حال : </label>
                <div class="col-md-8">
                    <span style="display: inline-block;margin-left: 13px;font-weight: 600;">ساعت : {{ jdate('H:i') }}</span>
                    <span style="display: inline-block;margin-left: 13px;font-weight: 600;">تاریخ : {{ jdate('Y/m/d') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label for="time_difference_delivery_without_shaving" class="col-md-3 col-form-label text-md-right">اختلاف زمانی تحویل (ساعت) با زمان حال برای حالت بدون تراش</label>

                <div class="col-md-7">
                    <input id="time_difference_delivery_without_shaving" type="text" class="form-control{{ $errors->has('time_difference_delivery_without_shaving') ? ' is-invalid' : '' }}" name="time_difference_delivery_without_shaving" value="{{ old('time_difference_delivery_without_shaving', $request->without_shaving) }}">

                    @if ($errors->has('time_difference_delivery_without_shaving'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('time_difference_delivery_without_shaving') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="time_difference_delivery_with_shaving" class="col-md-3 col-form-label text-md-right">اختلاف زمانی تحویل (ساعت) با زمان ارسال برای حالت به همراه تراش</label>

                <div class="col-md-7">
                    <input id="time_difference_delivery_with_shaving" type="text" class="form-control{{ $errors->has('time_difference_delivery_with_shaving') ? ' is-invalid' : '' }}" name="time_difference_delivery_with_shaving" value="{{ old('time_difference_delivery_with_shaving', $request->with_shaving) }}">

                    @if ($errors->has('time_difference_delivery_with_shaving'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('time_difference_delivery_with_shaving') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="difference_delivery" class="col-md-3 col-form-label text-md-right">اختلاف زمانی (ساعت ها)</label>

                <div class="col-md-7">
                    <input id="difference_delivery" type="text" class="form-control{{ $errors->has('difference_delivery') ? ' is-invalid' : '' }}" name="difference_delivery" value="{{ old('difference_delivery', $request->difference) }}">

                    @if ($errors->has('difference_delivery'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('difference_delivery') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label class="col-md-12 col-form-label text-md-right">زمان های تحویل سفارش به مشتری بدون تراش : </label>
            </div>

            <div class="form-group row">
                <div id="delivery_without">

                    @if($areas)

                        @foreach($areas as $key => $item)

                            @if($item['type'] == 'without')

                                <div class="full_time_list send_list" style="margin-bottom: 20px">
                                    <div class="item_time">

                                        <div class="col-md-3">

                                            <div class="time_controll">

                                                <div class="row">

                                                    <div class="col-xs-6">
                                                        <div class="time_box">
                                                            <select class="form-control delivery_without_time" name="delivery_without_time[]">
                                                                @for($i = 1 ; $i <= 24 ; $i++)
                                                                    <option value="{{ $i }}" {{ ($item['without_time'] == $i) ? 'selected' : ''}}>ساعت {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="time_box_end" style="line-height: 39px;">
                                                            <span style="margin-left: 10px">-</span>
                                                            <span class="time">ساعت {{ $item['without_difference_time'] }}</span>
                                                        </div>
                                                        <input type="hidden" class="delivery_without_difference_time" name="delivery_without_difference_time[]" value="{{ $item['without_difference_time'] }}">
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <input name="delivery_without_date[]" type="text" class="form-control observer delivery_without_date" value="{{ tr_num(jdate('Y/m/d', strtotime($item['without_date']))) }}" placeholder="تاریخ را وارد کنید">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="delivery_without_price[]" min="-100" max="100" class="form-control delivery_without_price" value="{{ $item['without_price'] }}" placeholder="کاهش و افزایش قیمت (٪)">
                                        </div>
                                        <div class="col-md-3">
                                            <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                        </div>

                                        <div class="clearfix"></div>

                                    </div>
                                </div>

                            @endif

                        @endforeach

                    @elseif(old('delivery_without_time'))
                        @foreach(old('delivery_without_time') as $key => $item)

                            <div class="full_time_list send_list" style="margin-bottom: 20px">
                                <div class="item_time">

                                    <div class="col-md-3">

                                        <div class="time_controll">

                                            <div class="row">

                                                <div class="col-xs-6">
                                                    <div class="time_box">
                                                        <select class="form-control delivery_without_time" name="delivery_without_time[]">
                                                            @for($i = 1 ; $i <= 24 ; $i++)
                                                                <option value="{{ $i }}" {{ ($item == $i) ? 'selected' : ''}}>ساعت {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <?php
                                                    $difference_delivery = old('difference_delivery');
                                                    $difference = old('delivery_without_difference_time')[$key] + $difference_delivery;
                                                    ?>
                                                    <div class="time_box_end" style="line-height: 39px;">
                                                        <span style="margin-left: 10px">-</span>
                                                        <span class="time">ساعت {{ $difference }}</span>
                                                    </div>
                                                    <input type="hidden" class="delivery_without_difference_time" name="delivery_without_difference_time[]" value="{{ $difference }}">
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <input name="delivery_without_date[]" type="text" class="form-control observer delivery_without_date" value="{{ tr_num(old('delivery_without_date')[$key]) }}" placeholder="تاریخ را وارد کنید">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="delivery_without_price[]" min="-100" max="100" class="form-control delivery_without_price" value="{{ old('delivery_without_price')[$key] }}" placeholder="کاهش و افزایش قیمت (٪)">
                                    </div>
                                    <div class="col-md-3">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                    <div class="clearfix"></div>

                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="button" id="add_delivery_without" class="btn btn-info waves-effect waves-light">افزودن زمان جدید</button>
                </div>
            </div>

            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label class="col-md-12 col-form-label text-md-right">زمان های تحویل سفارش به مشتری برای حالت به همراه تراش : </label>
            </div>

            <div class="form-group row">
                <div id="delivery_with">
                    @if($areas)

                        @foreach($areas as $key => $item)

                            @if($item['type'] == 'with')

                                <div class="full_time_list send_list" style="margin-bottom: 20px">
                                    <div class="item_time">

                                        <div class="col-md-3">

                                            <div class="time_controll">

                                                <div class="row">

                                                    <div class="col-xs-6">
                                                        <div class="time_box">
                                                            <select class="form-control delivery_with_time" name="delivery_with_time[]">
                                                                @for($i = 1 ; $i <= 24 ; $i++)
                                                                    <option value="{{ $i }}" {{ ($item['with_time'] == $i) ? 'selected' : ''}}>ساعت {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="time_box_end" style="line-height: 39px;">
                                                            <span style="margin-left: 10px">-</span>
                                                            <span class="time">ساعت {{ $item['with_difference_time'] }}</span>
                                                        </div>
                                                        <input type="hidden" class="delivery_with_difference_time" name="delivery_with_difference_time[]" value="{{ $item['with_difference_time'] }}">
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <input name="delivery_with_date[]" type="text" class="form-control observer delivery_with_date" value="{{ tr_num(jdate('Y/m/d', strtotime($item['with_date']))) }}" placeholder="تاریخ را وارد کنید">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="delivery_with_price[]" min="-100" max="100" class="form-control delivery_with_price" value="{{ $item['with_price'] }}" placeholder="کاهش و افزایش قیمت (٪)">
                                        </div>
                                        <div class="col-md-3">
                                            <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                        </div>

                                        <div class="clearfix"></div>

                                    </div>
                                </div>

                            @endif

                        @endforeach

                    @elseif(old('delivery_with_time'))
                        @foreach(old('delivery_with_time') as $key => $item)

                            <div class="full_time_list send_list" style="margin-bottom: 20px">
                                <div class="item_time">

                                    <div class="col-md-3">

                                        <div class="time_controll">

                                            <div class="row">

                                                <div class="col-xs-6">
                                                    <div class="time_box">
                                                        <select class="form-control delivery_with_time" name="delivery_with_time[]">
                                                            @for($i = 1 ; $i <= 24 ; $i++)
                                                                <option value="{{ $i }}" {{ ($item == $i) ? 'selected' : ''}}>ساعت {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <?php
                                                    $difference_delivery = old('difference_delivery');
                                                    $difference = old('delivery_with_difference_time')[$key] + $difference_delivery;
                                                    ?>
                                                    <div class="time_box_end" style="line-height: 39px;">
                                                        <span style="margin-left: 10px">-</span>
                                                        <span class="time">ساعت {{ $difference }}</span>
                                                    </div>
                                                    <input type="hidden" class="delivery_with_difference_time" name="delivery_with_difference_time[]" value="{{ $difference }}">
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <input name="delivery_with_date[]" type="text" class="form-control observer delivery_with_date" value="{{ tr_num(old('delivery_with_date')[$key]) }}" placeholder="تاریخ را وارد کنید">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="delivery_with_price[]" min="-100" max="100" class="form-control delivery_with_price" value="{{ old('delivery_with_price')[$key] }}" placeholder="کاهش و افزایش قیمت (٪)">
                                    </div>
                                    <div class="col-md-3">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                    <div class="clearfix"></div>

                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="button" id="add_delivery_with" class="btn btn-info waves-effect waves-light">افزودن زمان جدید</button>
                </div>
            </div>

            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label class="col-md-12 col-form-label text-md-right">زمان های دریافت فریم توسط پیک : </label>
            </div>

            <div class="form-group row">

                <div class="col-md-6">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-8">
                                <select class="form-control hour_of_receipt" id="hour_of_receipt">
                                    @for($i = 1 ; $i <= 24 ; $i++)
                                        <option value="{{ $i }}">ساعت {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_hour_of_receipt" class="btn btn-warning">افزودن ساعت</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list get" id="list_hour_of_receipt">
                            @if($receipt_time)

                                @foreach($receipt_time as $key => $item)

                                    <div class="item_time receipt"><span>ساعت {{ $item['receipt_time'] }} - ساعت {{ $item['receipt_difference_time'] }}</span>
                                        <input type="hidden" name="hour_of_receipt[]" value="{{ $item['receipt_time'] }}">
                                        <input type="hidden" name="hour_of_receipt_diff[]" value="{{ $item['receipt_difference_time'] }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach

                            @elseif(old('hour_of_receipt'))
                                @foreach(old('hour_of_receipt') as $key => $item)

                                    <div class="item_time receipt"><span>ساعت {{ $item }} - ساعت {{ old('hour_of_receipt_diff')[$key] }}</span>
                                        <input type="hidden" name="hour_of_receipt[]" value="{{ $item }}">
                                        <input type="hidden" name="hour_of_receipt_diff[]" value="{{ old('hour_of_receipt_diff')[$key] }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach
                            @endif

                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="input-group">
                                    <input id="date_of_receipt" type="text" min="1" class="form-control observer" placeholder="تاریخ را وارد کنید.">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_date_of_receipt" class="btn btn-warning">افزودن تاریخ</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list" id="list_date_of_receipt">

                            @if($receipt_date)

                                @foreach($receipt_date as $key => $item)

                                    <div class="item_time receipt"><span>{{ tr_num(jdate('Y/m/d', strtotime($item))) }}</span>
                                        <input type="hidden" name="date_of_receipt[]" value="{{ tr_num(jdate('Y/m/d', strtotime($item))) }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach

                            @elseif(old('date_of_receipt'))
                                @foreach(old('date_of_receipt') as $key => $item)

                                    <div class="item_time receipt"><span>{{ $item }}</span>
                                        <input type="hidden" name="date_of_receipt[]" value="{{ $item }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach
                            @endif

                        </div>
                    </div>

                </div>

            </div>

            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت </button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

        <div class="hidden" id="delivery_without_reaped">

            <div class="full_time_list send_list" style="margin-bottom: 20px">
                <div class="item_time">

                    <div class="col-md-3">

                        <div class="time_controll">

                            <div class="row">

                                <div class="col-xs-6">
                                    <div class="time_box">
                                        <select class="form-control delivery_without_time" name="delivery_without_time[]">
                                            @for($i = 1 ; $i <= 24 ; $i++)
                                                <option value="{{ $i }}">ساعت {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="time_box_end" style="line-height: 39px;">
                                        <span style="margin-left: 10px">-</span>
                                        <span class="time">ساعت 2</span>
                                    </div>
                                    <input type="hidden" class="delivery_without_difference_time" name="delivery_without_difference_time[]" value="">
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="col-md-4">
                        <input name="delivery_without_date[]" type="text" class="form-control observer delivery_without_date" value="" placeholder="تاریخ را وارد کنید">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="delivery_without_price[]" min="-100" max="100" class="form-control delivery_without_price" value="" placeholder="کاهش و افزایش قیمت (٪)">
                    </div>
                    <div class="col-md-3">
                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>

        </div>
        <div class="hidden" id="delivery_with_reaped">

            <div class="full_time_list send_list" style="margin-bottom: 20px">
                <div class="item_time">

                    <div class="col-md-3">

                        <div class="time_controll">

                            <div class="row">

                                <div class="col-xs-6">
                                    <div class="time_box">
                                        <select class="form-control delivery_with_time" name="delivery_with_time[]">
                                            @for($i = 1 ; $i <= 24 ; $i++)
                                                <option value="{{ $i }}">ساعت {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="time_box_end" style="line-height: 39px;">
                                        <span style="margin-left: 10px">-</span>
                                        <span class="time">ساعت 2</span>
                                    </div>
                                    <input type="hidden" class="delivery_with_difference_time" name="delivery_with_difference_time[]" value="">
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="col-md-4">
                        <input name="delivery_with_date[]" type="text" class="form-control observer delivery_with_date" value="" placeholder="تاریخ را وارد کنید">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="delivery_with_price[]" min="-100" max="100" class="form-control delivery_with_price" value="" placeholder="کاهش و افزایش قیمت (٪)">
                    </div>
                    <div class="col-md-3">
                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>

        </div>

    </div>



    <script>

        $(document).delegate('.send_list .remove_time', 'click', function (e) {
            $(this).parent().parent().parent().remove();
        });

        $(document).delegate('.item_time.receipt .remove_time', 'click', function (e) {
            $(this).parent().remove();
        });

        $(document).delegate('#delivery_without .delivery_without_time', 'change', function (e) {

            var time_now  =  parseInt($(this).val());

            var time_difference_delivery  =  parseInt($('#difference_delivery').val());

            var diff = time_now + 1;
            if(time_difference_delivery > 0){
                diff = time_now + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }

            $(this).parent().parent().parent().find('.time_box_end .time').html("ساعت " + diff);
            $(this).parent().parent().parent().find('.delivery_without_difference_time').val(diff);

        });


        $(document).delegate('#delivery_with .delivery_with_time', 'change', function (e) {

            var time_now  =  parseInt($(this).val());

            var time_difference_delivery  =  parseInt($('#difference_delivery').val());

            var diff = time_now + 1;
            if(time_difference_delivery > 0){
                diff = time_now + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }

            $(this).parent().parent().parent().find('.time_box_end .time').html("ساعت " + diff);
            $(this).parent().parent().parent().find('.delivery_with_difference_time').val(diff);

        });


        $('#add_delivery_without').click(function () {
            var data_set = $('#time_difference_delivery_without_shaving').val();

            $('#delivery_without_reaped .delivery_without_date').attr('value', "{{ tr_num(jdate('Y/m/d')) }}");

            var time_now  =  parseInt("{{ tr_num(jdate('H')) }}");
            var time_delivery_without  =  parseInt($('#time_difference_delivery_without_shaving').val());

            if(time_delivery_without > 0){
                time_now = time_now + time_delivery_without;
            }

            var time_difference_delivery  =  parseInt($('#difference_delivery').val());

            var diff = time_now + 1;
            if(time_delivery_without > 0){
                diff = time_now + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }


            $("#delivery_without_reaped .delivery_without_time option").each(function () {
                $(this).removeAttr('selected');
                if(time_now.toString() === $(this).val()){
                    $(this).attr('selected', '');
                }
            });
            $('#delivery_without_reaped .time_box_end .time').html("ساعت " + diff);
            $('#delivery_without_reaped .delivery_without_difference_time').val(diff);


            var reaped = $('#delivery_without_reaped').html();
            $('#delivery_without').append(reaped);

            $(".observer").persianDatepicker({
                initialValue: false,
                format: 'YYYY/MM/DD'
            });
        });

        $('#add_delivery_with').click(function () {
            var data_set = $('#time_difference_delivery_with_shaving').val();

            $('#delivery_with_reaped .delivery_with_date').attr('value', "{{ tr_num(jdate('Y/m/d')) }}");

            var time_now  =  parseInt("{{ tr_num(jdate('H')) }}");
            var time_delivery_with  =  parseInt($('#time_difference_delivery_with_shaving').val());

            if(time_delivery_with > 0){
                time_now = time_now + time_delivery_with;
            }

            var time_difference_delivery  =  parseInt($('#difference_delivery').val());

            var diff = time_now + 1;
            if(time_delivery_with > 0){
                diff = time_now + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }


            $("#delivery_with_reaped .delivery_with_time option").each(function () {
                $(this).removeAttr('selected');
                if(time_now.toString() === $(this).val()){
                    $(this).attr('selected', '');
                }
            });
            $('#delivery_with_reaped .time_box_end .time').html("ساعت " + diff);
            $('#delivery_with_reaped .delivery_with_difference_time').val(diff);


            var reaped = $('#delivery_with_reaped').html();
            $('#delivery_with').append(reaped);

            $(".observer").persianDatepicker({
                initialValue: false,
                format: 'YYYY/MM/DD'
            });
        });

        $('#add_hour_of_receipt').click(function () {
            var data_set = parseInt($('#hour_of_receipt').val());

            var time_difference_delivery  =  parseInt($('#difference_delivery').val());

            var diff = data_set + 1;
            if(time_difference_delivery > 0){
                diff = data_set + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }

            $('#list_hour_of_receipt').append('<div class="item_time receipt"><span>ساعت '+data_set+' - ساعت '+diff+'</span><input type="hidden" name="hour_of_receipt[]" value="'+data_set+'"><input type="hidden" name="hour_of_receipt_diff[]" value="'+diff+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');

        });

        $('#add_date_of_receipt').click(function () {
            var data_set = $('#date_of_receipt').val();
            if(data_set){

                $('#list_date_of_receipt').append('<div class="item_time receipt"><span>'+data_set+'</span><input type="hidden" name="date_of_receipt[]" value="'+data_set+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');

            }
        });

    </script>

@endsection
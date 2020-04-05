<?php use Illuminate\Support\Facades\Input; ?>

<div id="load_date" style="position: relative;">
    <div class="row">

        <div class="col-xs-12">

            <form method="post" id="date_controller" action="{{ url('/order/new/date') }}">


                <div class="row">
                    <div class="name_box">

                        <div class="col-xs-12 col-md-4">
                            @if($timeList['sendboxday'])
                                <div class="radio info type_shipping active">
                                    <input type="radio" name="type_shipping" value="theNormal" id="theNormal" checked>
                                    <label>تحویل فوری یا عادی</label>
                                </div>
                            @endif
                            @if(!$timeList['city'])
                            <div class="radio info type_shipping {{ ($timeList['sendboxday']) ? '' : 'active' }}">
                                <input type="radio" name="type_shipping" value="inPerson" id="inPerson" {{ ($timeList['sendboxday']) ? '' : 'checked' }}>
                                <label>تحویل حضوری</label>
                            </div>
                            @endif
                            @if(!$timeList['city'] && !$timeList['passage'])
                                <div class="radio info type_shipping">
                                    <input type="radio" name="type_shipping" value="theCalendar" id="theCalendar">
                                    <label>تحویل تقویمی</label>
                                </div>
                            @endif
                            @if($timeList['city'])
                                <div class="radio info type_shipping active">
                                    <input type="radio" name="type_shipping" value="theCity" id="theCity" checked>
                                    <label>تحویل شهرستان</label>
                                </div>
                            @endif
                        </div>

                        <div class="col-xs-12 col-md-8">
                            <div class="type_shipping_main">
                                @if($timeList['sendboxday'])
                                    <div class="tab theNormal active">

                                        <div class="row">
aa
                                            <div class="clearfix"></div>


                                            @if($timeList['getboxday'])
                                                <div class="col-xs-6">
                                                    <label class="block_table">زمان تحویل فریم به پیک</label>
                                                    <select class="form-control input_control {{ $errors->has('getboxday') ? ' is-invalid' : '' }}" id="getboxday" name="getboxday">
                                                        <option value="">انتخاب روز تحویل</option>

                                                        @foreach($timeList['getboxday'] as $set)
                                                            <option value="{{ $set }}">{{ $set }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <div class="col-xs-6">
                                                    <label class="block_table">ساعت تحویل فریم</label>

                                                    <select class="form-control input_control {{ $errors->has('getboxtime') ? ' is-invalid' : '' }}" id="getboxtime" name="getboxtime">
                                                        <option value="">انتخاب ساعت زمان تحویل</option>
                                                        @if($timeList['getboxtime'])
                                                            @foreach($timeList['getboxtime'] as $time => $set)
                                                                <option value="{{ $time }}">{{ $set }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="zm_blank_hight"></div>
                                                </div>
                                            @endif


                                            @if($timeList['sendboxday'])
                                                <div class="col-xs-6">
                                                    <label class="block_table">زمان تحویل سفارش به فروشگاه</label>
                                                    <select class="form-control input_control {{ $errors->has('sendboxday') ? ' is-invalid' : '' }}" id="sendboxday" name="sendboxday">
                                                        <option value="">انتخاب زمان تحویل</option>
                                                        @foreach($timeList['sendboxday'] as $set)
                                                            <option value="{{ $set }}">{{ $set }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xs-6">
                                                    <label class="block_table">ساعت تحویل سفارش</label>

                                                    <select class="form-control input_control {{ $errors->has('sendboxtime') ? ' is-invalid' : '' }}" id="sendboxtime" name="sendboxtime">
                                                        <option value="">انتخاب ساعت تحویل</option>
                                                        @if($timeList['sendboxtime'])
                                                            @foreach($timeList['sendboxtime'] as $time => $set)
                                                                <option value="{{ $time }}">{{ $set }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endif
                                @if(!$timeList['city'])
                                    <div class="tab inPerson {{ ($timeList['sendboxday']) ? '' : 'active' }}">

                                        <div class="row">

                                            <div class="box_timer_set">
                                                <div class="col-xs-12 col-md-4">
                                                    <span class="shipping_title">تحویل فریم به پیک</span>
                                                </div>
                                                <div class="col-xs-12 col-md-8">
                                                    <p>تحویل حضوری انتخاب شده است ، برای ارسال فریم ها به ما مراجعه کنید یا به آدرس ما بفرستید</p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="col-xs-12">
                                                <div class="zm_blank_hight"></div>
                                            </div>

                                            <div class="box_timer_set">
                                                <div class="col-xs-12 col-md-4">
                                                    <span class="shipping_title">تحویل سفارش به فروشگاه</span>
                                                </div>
                                                <div class="col-xs-12 col-md-8">
                                                    <p>تحویل حضوری انتخاب شده است ، پس از تکمیل فرآیند فروش به شما اطلاع داده میشود تا برای تحویل مراجعه کنید</p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                        </div>

                                    </div>
                                @endif
                                @if(!$timeList['city'] && !$timeList['passage'])
                                    <div class="tab theCalendar">

                                        <p class="hellp_controll">تحویل طبق زمانبندی تقویم انجام میشود پیک سامانه به محل شما مراجعه میکند فریم های سفارش جدید شما را در صورت وجود دریافت میکند و همزمان سفارش های قبلی شما را تحویل میدهد هزینه پبک رایگان است
                                            <br>
                                            برای تنظیم تقویم باید به قسمت حساب کاربری و زمان بندی تقویم مراجعه کنید
                                        </p>

                                        <div class="row">
                                            @if($timeList['getcalender'])
                                                <div class="col-xs-6">
                                                    <label class="block_table">زمان تحویل فریم به پیک</label>
                                                    <select class="form-control input_control {{ $errors->has('getboxday') ? ' is-invalid' : '' }}" id="getboxday" name="getboxday">
                                                        <option value="">انتخاب روز تحویل</option>

                                                        @foreach($timeList['getboxday'] as $set)
                                                            <option value="{{ $set }}">{{ $set }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <div class="col-xs-12">
                                                    <div class="zm_blank_hight"></div>
                                                </div>
                                            @endif

                                            @if($timeList['sendcalender'])
                                                <div class="col-xs-6">
                                                    <label class="block_table">زمان تحویل سفارش به فروشگاه</label>
                                                    <select class="form-control input_control {{ $errors->has('sendboxday') ? ' is-invalid' : '' }}" id="sendboxday" name="sendboxday">
                                                        <option value="">انتخاب زمان تحویل</option>
                                                        @foreach($timeList['sendboxday'] as $set)
                                                            <option value="{{ $set }}">{{ $set }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endif
                                @if($timeList['city'])
                                    <div class="tab theCity active">

                                    <div class="row">

                                        <div class="box_timer_set">
                                            <div class="col-xs-12 col-md-4">
                                                <span class="shipping_title">تحویل فریم به پیک</span>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <p>تحویل بر اساس زمان بندی شرکت باربری میباشد ، برای ارسال فریم ها با ما تماس بگیرید و به آدرس ما بفرستید</p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="zm_blank_hight"></div>
                                        </div>

                                        <div class="box_timer_set">
                                            <div class="col-xs-12 col-md-4">
                                                <span class="shipping_title">تحویل سفارش به فروشگاه</span>
                                            </div>
                                            <div class="col-xs-12 col-md-8">
                                                <p>تحویل بر اساس زمانبندی شرکت باربری میباشد ، پس از تکمیل فرآیند فروش عدسی به شما اطلاع داده میشود تا با چه شرکت باربری مرسوله شما ارسال شود</p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>

                                    </div>

                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-xs-12 buttomSend">
                            <button type="submit" class="btn btn-info">گام بعدی</button>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>

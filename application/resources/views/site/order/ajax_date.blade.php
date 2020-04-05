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

                                            @if($timeList['sendboxday']['lathe'])
                                                @if(isset($timeList['sendboxday']['receipt']['date']))
                                                    <div class="col-xs-12">
                                                        <label class="block_table">زمان تقریبی پذیرش سفارش</label>
                                                        <p>تاریخ و ساعت مراجعه پیک برای دریافت فریم از فروشگاه را وارد کنید</p>
                                                    </div>

                                                    <div class="col-xs-6 normal_receipt_time">
                                                        <select class="form-control input_control" id="getboxtime" name="get_time">
                                                            <option value="">ساعت را انتخاب کنید</option>

                                                            {{--@if($timeList['sendboxday']['receipt']['time'])
                                                                @foreach($timeList['sendboxday']['receipt']['time'] as $set)
                                                                    <option value="{{ $set['receipt_time'] }}">{{ $set['receipt_time'] . ':00 - ' . $set['receipt_difference_time'] . ':00' }}</option>
                                                                @endforeach
                                                            @endif--}}

                                                        </select>
                                                    </div>

                                                    <div class="col-xs-6 normal_receipt_date">
                                                        <select class="form-control input_control" id="getboxday" name="get_date">
                                                            <option value="">تاریخ را انتخاب کنید</option>
                                                            <?php
                                                                $datereceipt = [];
                                                            ?>
                                                            @foreach($timeList['sendboxday']['receipt']['date'] as $set)
                                                                @if(!in_array($set, $datereceipt))
                                                                    <?php
                                                                        $datereceipt[] = $set;
                                                                    ?>
                                                                    <option value="{{ $set }}">{{ tr_num(jdate('Y/m/d', strtotime($set))) }}</option>
                                                                @endif
                                                            @endforeach

                                                        </select>
                                                    </div>


                                                    <div class="col-xs-12">
                                                        <hr>
                                                    </div>

                                                @endif

                                                <div class="clearfix"></div>

                                                @if(isset($timeList['sendboxday']['get']['date']))
                                                    <div class="col-xs-12">
                                                        <label class="block_table">زمان خروج سفارش حاضر شده</label>
                                                        <p>تاریخ و ساعت مراجعه پیک برای تحویل سفارش به فروشگاه را وارد کنید</p>
                                                    </div>

                                                    <div class="col-xs-6 normal_get_time">
                                                        <select class="form-control input_control" id="sendboxtime" name="send_time">
                                                            <option value="">ساعت را انتخاب کنید</option>
                                                            {{--@if($timeList['sendboxday']['get']['time'])
                                                                @foreach($timeList['sendboxday']['get']['time'] as $set)
                                                                    <option value="{{ ($set['with_time']) ? $set['with_time'] : $set['without_time'] }}">{{ ($set['with_time']) ? $set['with_time'] . ':00 - ' . $set['with_difference_time'] . ':00' : $set['without_time'] . ':00 - ' . $set['without_difference_time'] . ':00' }}</option>
                                                                @endforeach
                                                            @endif--}}

                                                        </select>
                                                    </div>

                                                    <div class="col-xs-6 normal_get_date">
                                                        <select class="form-control input_control" id="sendboxday" name="send_date">
                                                            <option value="">تاریخ را انتخاب کنید</option>
                                                            <?php
                                                            $datereceipt = [];
                                                            ?>
                                                            {{--@foreach($timeList['sendboxday']['get']['date'] as $set)
                                                                @if(!in_array($set, $datereceipt))
                                                                    <?php
                                                                    $datereceipt[] = $set;
                                                                    ?>
                                                                    <option value="{{ $set }}">{{ tr_num(jdate('Y/m/d', strtotime($set))) }}</option>
                                                                @endif
                                                            @endforeach--}}

                                                        </select>
                                                    </div>

                                                @endif

                                            @else


                                                @if(isset($timeList['sendboxday']['get']['date']))
                                                    <div class="col-xs-12">
                                                        <label class="block_table">زمان خروج سفارش حاضر شده</label>
                                                        <p>تاریخ و ساعت مراجعه پیک برای تحویل سفارش به فروشگاه را وارد کنید</p>
                                                    </div>

                                                    <div class="col-xs-6 normal_get_time">
                                                        <select class="form-control input_control" id="sendboxtime" name="send_time">
                                                            <option value="">ساعت را انتخاب کنید</option>
                                                            {{--@if($timeList['sendboxday']['get']['time'])
                                                                @foreach($timeList['sendboxday']['get']['time'] as $set)
                                                                    <option value="{{ ($set['with_time']) ? $set['with_time'] : $set['without_time'] }}">{{ ($set['with_time']) ? $set['with_time'] . ':00 - ' . $set['with_difference_time'] . ':00' : $set['without_time'] . ':00 - ' . $set['without_difference_time'] . ':00' }}</option>
                                                                @endforeach
                                                            @endif--}}

                                                        </select>
                                                    </div>

                                                    <div class="col-xs-6 normal_get_date">
                                                        <select class="form-control input_control" id="sendboxday" name="send_date">
                                                            <option value="">تاریخ را انتخاب کنید</option>
                                                            <?php
                                                            $datereceipt = [];
                                                            ?>
                                                            @foreach($timeList['sendboxday']['get']['date'] as $set)
                                                                @if(!in_array($set, $datereceipt))
                                                                    <?php
                                                                    $datereceipt[] = $set;
                                                                    ?>
                                                                    <option value="{{ $set }}">{{ tr_num(jdate('Y/m/d', strtotime($set))) }}</option>
                                                                @endif
                                                            @endforeach

                                                        </select>
                                                    </div>

                                                @endif

                                            @endif

                                            <div class="clearfix"></div>





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

                                        <p class="hellp_controll" style="margin-bottom: 5px;">تحویل طبق زمانبندی تقویم انجام میشود پیک سامانه به محل شما مراجعه میکند فریم های سفارش جدید شما را در صورت وجود دریافت میکند و همزمان سفارش های قبلی شما را تحویل میدهد هزینه پبک رایگان است</p>

                                        <div class="row">

                                            @if($timeList['sendcalender']['date_time'])

                                                @foreach($timeList['sendcalender']['date_time'] as $key => $item)

                                                    @if($timeList['sendcalender']['lathe'])

                                                        <div class="box_timer_set {{ ($key == 0) ? 'active' : '' }}" data-senddate="{{ $item['get']['date'] }}" data-getdate="{{ $item['receipt']['date'] }}" data-sendtime="{{ $item['get']['time']['start'] }}" data-gettime="{{ $item['receipt']['time']['start'] }}">
                                                            <div class="col-xs-12 col-md-4">
                                                                <span class="shipping_title">تحویل فریم به پیک</span>
                                                            </div>
                                                            <div class="col-xs-12 col-md-8">
                                                                <p> روز : {{ tr_num(jdate('Y/m/d', strtotime($item['receipt']['date']))) }} ساعت : {{ $item['receipt']['time']['start'] }}:00 -  {{ $item['receipt']['time']['end'] }}:00
                                                                </p>
                                                            </div>
                                                            <div class="clearfix"></div>

                                                            <hr>

                                                            <div class="col-xs-12 col-md-4">
                                                                <span class="shipping_title">تحویل سفارش به فروشگاه</span>
                                                            </div>
                                                            <div class="col-xs-12 col-md-8">
                                                                <p>روز : {{ tr_num(jdate('Y/m/d', strtotime($item['get']['date']))) }} ساعت : {{ $item['get']['time']['start'] }}:00 -  {{ $item['get']['time']['end'] }}:00
                                                                </p>
                                                            </div>

                                                            <div class="clearfix"></div>

                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="zm_blank_hight"></div>
                                                        </div>

                                                    @else

                                                        <div class="box_timer_set {{ ($key == 0) ? 'active' : '' }}" data-senddate="{{ $item['get']['date'] }}" data-sendtime="{{ $item['get']['time']['start'] }}">

                                                            <div class="col-xs-12 col-md-4">
                                                                <span class="shipping_title">تحویل سفارش به فروشگاه</span>
                                                            </div>
                                                            <div class="col-xs-12 col-md-8">
                                                                <p>روز : {{ tr_num(jdate('Y/m/d', strtotime($item['get']['date']))) }} ساعت : {{ $item['get']['time']['start'] }}:00 -  {{ $item['get']['time']['end'] }}:00
                                                                </p>
                                                            </div>

                                                            <div class="clearfix"></div>

                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="zm_blank_hight"></div>
                                                        </div>

                                                    @endif

                                                @endforeach

                                                @if($timeList['sendcalender']['lathe'])
                                                    <input type="hidden" id="get_time" class="input_control" name="calender_get_time" value="{{ $timeList['sendcalender']['date_time'][0]['receipt']['date'] }}">
                                                    <input type="hidden" id="get_date" class="input_control" name="calender_get_date" value="{{ $timeList['sendcalender']['date_time'][0]['receipt']['time']['start'] }}">
                                                @endif

                                                <input type="hidden" id="send_time" class="input_control" name="calender_send_time" value="{{ $timeList['sendcalender']['date_time'][0]['get']['date'] }}">
                                                <input type="hidden" id="send_date" class="input_control" name="calender_send_date" value="{{ $timeList['sendcalender']['date_time'][0]['get']['time']['start'] }}">

                                            @else

                                            <div class="col-xs-12">
                                                <br>
                                                <div class="alert alert-danger">در حال حاضر امکان ارسال تقویمی وجود ندارد</div>
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

@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages">
        <div class="container">
            <div class="row justify-content-center" style="background: #f7f7f7;">

                <div class="col-md-6 col-xs-12" style="background: #fff;">
                    <div class="card" style="margin-top: 30px;padding-top: 20px; ">

                        <h2 class="title" style="margin: 0; margin-bottom: 25px;">تایید شماره همراه</h2>
                        <p class="m-text-larger text-center">کد تایید به شماره تلفن همراه شما ارسال گردید.</p>

                        <div class="row">
                            <div class="error clearfix">
                                @if(Session::has('error'))
                                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                                @endif

                                @if(Session::has('success'))
                                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <form class="form-horizontal" method="post" id="verification_submit">
                                @csrf



                                <div class="form-group row" id="verification">
                                    <div class="row">
                                        <div class="col-xs-2 leftflat">
                                            <input class="form-control text-center inputs code1 zm_comma_number" name="code[]" type="text" maxlength="1" formcontrolname="code2" autocomplete="off" autofocus />
                                        </div>
                                        <div class="col-xs-2 leftflat">
                                            <input class="form-control text-center inputs code2 zm_comma_number" name="code[]" type="text" maxlength="1" formcontrolname="code3" autocomplete="off" />
                                        </div>
                                        <div class="col-xs-2 leftflat">
                                            <input class="form-control text-center inputs code3 zm_comma_number" name="code[]" type="text" maxlength="1" formcontrolname="code4" autocomplete="off" />
                                        </div>
                                        <div class="col-xs-2 leftflat">
                                            <input class="form-control text-center inputs code4 zm_comma_number" name="code[]" type="text" maxlength="1" formcontrolname="code5" autocomplete="off" />
                                        </div>
                                        <div class="col-xs-2 leftflat">
                                            <input class="form-control text-center inputs code5 zm_comma_number" name="code[]" type="text" maxlength="1" formcontrolname="code6" autocomplete="off" />
                                        </div>
                                        <div class="col-xs-2 leftflat">
                                            <input class="form-control text-center inputs code6 zm_comma_number" name="code[]" type="text" maxlength="1" formcontrolname="Submit" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group" id="">
                                    <div class="timerResandData" id="timerResand">جهت ارسال مجدد کد فعال سازی <span id="titmer_show">00:00</span> دقیقه صبر کنید. </div>
                                </div>


                                <script>
                                    var countDownDate = new Date("{{ $expiredTime }}").getTime();

                                    // Update the count down every 1 second
                                    var x = setInterval(function() {

                                        // Get todays date and time
                                        var now = new Date().getTime();

                                        // Find the distance between now an the count down date
                                        var distance = countDownDate - now;

                                        // Time calculations for days, hours, minutes and seconds
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                        // Output the result in an element with id="demo"
                                        document.getElementById("titmer_show").innerHTML = minutes + ":" + seconds;

                                        // If the count down is over, write some text
                                        if (distance < 0) {
                                            clearInterval(x);
                                            document.getElementById("timerResand").innerHTML = "<a href='{{ url('resand-conferm-account') }}'>ارسال مجدد کد فعال سازی</a>";
                                        }
                                    }, 1000);
                                </script>


                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-md-6 col-xs-12 guidance">

                    <div class="auth__guidance guidance noback">

                        <div class="guidance__rules">
                            <p>سریع تر و ساده تر خرید کنید</p>
                            <p>به سادگی سوابق خرید و فعالیت های خود را مدیریت کنید</p>
                            <p>لیست علاقمندی های خود را بسازید و تغییرات آنها را دنبال کنید</p>
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

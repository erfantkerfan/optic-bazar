@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages baclite">
        <div class="product_page_list">

            <div class="zm_order_lists">
                <div class="container">

                    <div class="row">

                        <div class="zm_step_box active">

                            <div class="main_step">

                                <div class="error clearfix">
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger" style="margin-top: -30px;">{{ Session::get('error') }}</div>
                                    @endif

                                    @if(Session::has('success'))
                                        <div class="alert alert-success" style="margin-top: -30px;">{{ Session::get('success') }}</div>
                                    @endif

                                </div>

                                <div class="thank_message">
                                    <div class="success_logo"></div>
                                    <h2 class="thank_title success">پرداخت با موفقیت انجام شد</h2>
                                    <div class="thank_text">اعتبار شما با موفقیت افزایش داده شد ، کد پیگیری : {{ $request->id }}</div>
                                    <div class="timer">در حال هدایت به بخش ثبت سفارش تا <span id="titmer_show">10</span> ثانیه دیگر.</div>

                                </div>

                            </div>

                        </div>

                        <div class="enterbox">
                            <a href="{{ url('dashboard') }}" class="btn btn-success" style="line-height: 24px;width: 100%;">قبت سفارش</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        var countDownDate = new Date("{{ date('Y-m-d H:i:s' , strtotime('10 second')) }}").getTime();

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
            document.getElementById("titmer_show").innerHTML = seconds;

            // If the count down is over, write some text
            if (distance < 0) {
                clearInterval(x);
                window.location.href = '{{ url('dashboard') }}';
            }
        }, 1000);
    </script>


@endsection

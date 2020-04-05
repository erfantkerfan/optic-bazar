@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages baclite">
        <div class="product_page_list">

            <div class="zm_order_lists">
                <div class="container">

                    <div class="row">

                        <form method="post">
                            {{ csrf_field() }}

                            <div class="zm_step_box active">
                                <div class="new-form-inner" style="margin-bottom: 30px">
                                    <div class="title-section-card" style="font-size: 16px">افزایش اعتبار </div>
                                </div>

                                <div class="main_step">

                                    <div class="error clearfix">
                                        @if(Session::has('error'))
                                            <div class="alert alert-danger" style="margin-top: -30px;">{{ Session::get('error') }}</div>
                                        @endif

                                        @if(Session::has('success'))
                                            <div class="alert alert-success" style="margin-top: -30px;">{{ Session::get('success') }}</div>
                                        @endif

                                    </div>


                                    <div class="header_pey">
                                        <p>حساب خود را به راحتی شارژ کنید تا از انبوهی از تراکنش های کوچک در صورت حساب بانکیتان جلوگیری کنید و بتوانید صورت حساب های صادره را به راحتی پرداخت کنید.</p>
                                    </div>

                                    <div class="invoice_dill">
                                        <div class="row">
                                            <div class="color_green">
                                                <div class="col-xs-6"><span>اعتبار حساب شما</span></div>
                                                <div class="col-xs-6">{{ number_format($UserLogin->credit) }} تومان</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="invoice_dill">

                                        <div class="form-group row">
                                            <label for="price" style="line-height: 36px;margin-bottom: 0;" class="col-md-4 col-form-label text-md-right">مبلغ واریزی : 	</label>

                                            <div class="col-md-8">
                                                <input id="price" type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" value="{{ old('price') }}">

                                                @if ($errors->has('price'))
                                                    <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('price') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row" style="margin-bottom: 0">
                                            <label for="type" style="line-height: 36px;margin-bottom: 0;" class="col-md-4 col-form-label text-md-right">روش پرداخت : 	</label>

                                            <div class="col-md-8">
                                                <select class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="type">
                                                    <option value="saman">درگاه بانک سامان</option>
                                                </select>
                                                @if ($errors->has('type'))
                                                    <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('type') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="enterbox">
                                <button type="submit" id="submit_full_form" class="btn btn-success" style="line-height: 24px;width: 100%;">افزایش اعتبار</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection

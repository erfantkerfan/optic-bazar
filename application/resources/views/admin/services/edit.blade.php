@extends('admin.layouts.app')

@section('page_name', 'ویرایش اطلاعات کاربر')

@section('content')

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-right">نام و نام خانوادگی	</label>

                <div class="col-md-6">
                    {{ $request->name }}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">


                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-4">
                                <input id="set_title" type="text" min="1" class="form-control" placeholder="نام خدمات">
                            </div>
                            <div class="col-xs-3">
                                <input id="set_price" type="number" min="1" class="form-control" placeholder="قیمت فروش">
                            </div>
                            <div class="col-xs-3">
                                <input id="set_sale_price" type="number" min="1" class="form-control" placeholder="قیمت اولیه">
                            </div>
                            <div class="col-xs-2">
                                <button type="button" style="width: 100%" id="add_service" class="btn btn-warning">افزودن خدمات</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list get">

                            @if($labrator_service)
                                @foreach($labrator_service as $item)
                                    <div class="item_time">
                                        <span>{{ $item['title'] }} - قیمت اولیه {{ ($item['sale_price']) ? number_format($item['sale_price']) : 0 }} تومان - قیمت فروش {{ ($item['price']) ? number_format($item['price']) : 0 }} تومان</span>
                                        <input type="hidden" name="title[]" value="{{ $item['title'] }}">
                                        <input type="hidden" name="price[]" value="{{ $item['price'] }}">
                                        <input type="hidden" name="sale_price[]" value="{{ $item['sale_price'] }}">
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
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت خدمات</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>


    <script>
        $('#add_service').click(function () {
            var data_title = $('#set_title').val();
            var data_price = $('#set_price').val();
            var data_sale_price = $('#set_sale_price').val();

            if(!data_price){
                alert('قیمت فروش را وارد کنید.');
                return false;
            }

            if(!data_sale_price){
                alert('قیمت اولیه را وارد کنید.');
                return false;
            }

            if(!data_title){
                alert('نام خدمات را وارد کنید.');
                return false;
            }

            if(data_price < data_sale_price){
                alert('قیمت اولیه نمیتواند کم تر از قیمت فروش باشد.');
                return false;
            }



            $('.full_time_list.get').append('<div class="item_time">' +
                '<span>'+data_title+'  - قیمت اولیه  '+data_sale_price+' تومان</span>'+'  - قیمت فروش  '+data_price+' تومان</span>' +
                '<input type="hidden" name="title[]" value="'+data_title+'">' +
                '<input type="hidden" name="price[]" value="'+data_price+'">' +
                '<input type="hidden" name="sale_price[]" value="'+data_sale_price+'">' +
                '<span class="remove_time pull-right"><i class="fa fa-remove"></i></span>' +
                '</div>');
            $('#set_title').val('');
            $('#set_price').val('');
            $('#set_sale_price').val('');
        });

        $(document).delegate('.remove_time', 'click', function () {
            $(this).parent().remove();
        });
    </script>


@endsection
@extends('admin.layouts.app')

@section('page_name', 'لنز ها')

@section('content')
    <div class="white-box">
        <div class="portlet-body">
            <div class="clearfix"></div>
            <br>

            <form class="filter_list" method="get" style="direction: rtl">

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_sku">کد محصول </label>
                        <input type="text" class="form-control" id="filter_sku" name="filter_sku" value="{{ @$_GET['filter_sku'] }}">
                    </div>
                </div>

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_status">وضعیت</label>
                        <select  id="filter_status" class="form-control" name="filter_status">
                            <option value="">لطفا انتخاب کنید</option>
                            <option value="active" {{ (@$_GET['filter_status'] == 'active') ? 'selected' : '' }}>فعال</option>
                            <option value="inactive" {{ (@$_GET['filter_status'] == 'inactive') ? 'selected' : '' }}>غیر فعال</option>
                        </select>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2 col-xs-12">
                    <button type="submit" class="btn btn-block btn-success btn-rounded">جستجو</button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>

    <div class="white-box">

        <div class="portlet-body">

            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">

                    <tr role="row" class="heading">
                        <th>تصویر</th>
                        <th>فروشنده</th>
                        <th>کد محصول</th>
                        <th>کشور</th>
                        <th>برند</th>
                        <th>ساختار </th>
                        <th>دوره مصرف</th>
                        <th>موجودی در هربسته</th>
                        <th>قیمت</th>
                        <th>وضعیت</th>
                        <th>اعمال</th>
                    </tr>

                    @foreach($request as $k => $item)
                        <tr role="row" class="filter">
                            <?php
                            $bonakdar = App\User::where('role' , 'bonakdar')->where('id' , $item['seller_id'])->orderBy('id', 'desc')->first();
                            $brand = App\Brand::where('id' , $item['brand_id'])->orderBy('name', 'desc')->first();
                            ?>
                            <td><img style="width: 100px;margin: -10px 0;" src="{{ $item['image'] }}"></td>
                            <td>{{ ($bonakdar) ? $bonakdar->name : 'بازار اپتیک' }}</td>
                            <td>{{ $item['sku'] }}</td>
                            <td>{{ $item['country'] }}</td>
                            <td>{{ ($brand) ? $brand->name : '-' }}</td>
                            <td>{{ $item['structure'] }}</td>
                            <td>{{ $item['consumption_period'] }}</td>
                            <td>{{ $item['number'] }} عدد</td>
                            <td>{{ number_format($item['price']) }} تومان</td>
                            <td>{!! ($item['status'] === "active") ? '<div class="active_status">فعال</div>' : '<div class="in active_status">غیر فعال</div>'  !!}</td>

                            <td>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/product/edit_lens/' . $item['id']) }}"> ویرایش </a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/product/delete/' . $item['id']) }}"> حذف </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
            {!! $request->render() !!}
            <div class="clearfix"></div>
        </div>



        <script>
            $('.delete-warning').click(function(e){
                e.preventDefault();
                var hreflink = $(this).attr('href');
                swal({
                    title: "",
                    text: "آیا از حذف این لنز مطمئن هستید ؟",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "خیر نیازی نیست",
                    confirmButtonText: "بله اطمینان دارم",
                    closeOnConfirm: false
                }, function(){
                    window.location.href = hreflink;
                });
            });

        </script>

    </div>
@endsection
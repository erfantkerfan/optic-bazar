@extends('admin.layouts.app')

@section('page_name', 'همه لابراتور ها')

@section('content')
    <div class="white-box">
        <div class="portlet-body">
            <div class="clearfix"></div>
            <br>

            <form class="filter_list" method="get" style="direction: rtl">

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_name">نام و نام خانوادگی	</label>
                        <input type="text" class="form-control" id="filter_name" name="filter_name" value="{{ @$_GET['filter_name'] }}">
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
                        <th>نام و نام خانوادگی</th>
                        <th>شماره موبایل</th>
                        <th>اعمال</th>
                    </tr>

                    @foreach($request as $k => $item)
                        <tr role="row" class="filter">
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['mobile'] }}</td>
                            <td>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/services/edit/' . $item['id']) }}"> ویرایش </a>
                                </div>
                                {{--<div class="col-xs-6">
                                    <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/services/delete/' . $item['id']) }}"> حذف </a>
                                </div>--}}
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
                    text: "آیا از حذف خدمات این کاربر مطمئن هستید ؟",
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
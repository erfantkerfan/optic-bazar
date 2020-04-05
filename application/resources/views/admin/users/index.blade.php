@extends('admin.layouts.app')

@section('page_name', 'کاربران')

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

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_mobile">شماره موبایل</label>
                        <input type="text" class="form-control" id="filter_mobile" name="filter_mobile" value="{{ @$_GET['filter_mobile'] }}">
                    </div>
                </div>

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_role">نقش</label>
                        <select  id="filter_role" class="form-control" name="filter_role">
                            <option value="">لطفا انتخاب کنید</option>
                            <option value="user" {{ (@$_GET['filter_role'] == 'user') ? 'selected' : '' }}>کاربر</option>
                            <option value="labrator" {{ (@$_GET['filter_role'] == 'labrator') ? 'selected' : '' }}>لابراتور</option>
                            <option value="amel" {{ (@$_GET['filter_role'] == 'amel') ? 'selected' : '' }}>عامل</option>
                            <option value="bonakdar" {{ (@$_GET['filter_role'] == 'bonakdar') ? 'selected' : '' }}>بنکدار</option>
                            <option value="admin" {{ (@$_GET['filter_role'] == 'admin') ? 'selected' : '' }}>ادمین</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_status">وضعیت</label>
                        <select  id="filter_status" class="form-control" name="filter_status">
                            <option value="">لطفا انتخاب کنید</option>
                            <option value="active" {{ (@$_GET['filter_status'] == 'active') ? 'selected' : '' }}>فعال</option>
                            <option value="inactive" {{ (@$_GET['filter_status'] == 'inactive') ? 'selected' : '' }}>غیر فعال</option>
                            <option value="not_active" {{ (@$_GET['filter_status'] == 'not_active') ? 'selected' : '' }}>تایید نشده</option>
                            <option value="not_verified" {{ (@$_GET['filter_status'] == 'not_verified') ? 'selected' : '' }}>شماره موبایل تایید نشده</option>
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
                        <th>ایمیل</th>
                        <th>نام و نام خانوادگی</th>
                        <th>شماره موبایل</th>
                        <th>نقش</th>
                        <th>وضعیت</th>
                        <th>اعمال</th>
                    </tr>

                    @foreach($request as $k => $item)
                        <tr role="row" class="filter">
                            <td>{{ $item['email'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['mobile'] }}</td>
                            <td>
                                @switch($item['role'])
                                    @case('admin')
                                        مدیر
                                    @break
                                    @case('user')
                                    کاربر
                                    @break
                                    @case('labrator')
                                    لابراتور
                                    @break
                                    @case('amel')
                                    عامل
                                    @break
                                    @case('bonakdar')
                                    بنکدار
                                    @break
                                @endswitch
                            </td>

                            <td>
                                @switch($item['status'])
                                @case('not_active')
                                تایید نشده
                                @break
                                @case('not_verified')
                                    شماره موبایل تایید نشده
                                @break
                                @case('active')
                                فعال
                                @break
                                @case('inactive')
                                غیر فعال
                                @break
                                @endswitch
                            </td>
                            <td>
                                <div class="col-xs-12">
                                    <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/user/edit/' . $item['id']) }}"> ویرایش </a>
                                </div>
                                {{--<div class="col-xs-6">
                                    <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/user/delete/' . $item['id']) }}"> حذف </a>
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
                    text: "آیا از حذف این کاربر مطمئن هستید ؟",
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
@extends('admin.layouts.app')

@section('page_name', 'برگه ها')

@section('content')

    <div class="white-box">

        <div class="portlet-body">

            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">
                    <colgroup> <col class="col-xs-1"> <col class="col-xs-7">  <col class="col-xs-4"> </colgroup>
                    <tbody>
                    <tr role="row" class="heading">
                        <th>تصویر</th>
                        <th>عنوان</th>
                        <th>اعمال</th>
                    </tr>

                    @foreach($request as $k => $item)
                        <tr role="row" class="filter">
                            <td><img style="width: 133px;margin: -10px 0;" src="{{ $item['image'] }}"></td>
                            <td>{{ $item['title'] }}</td>
                            <td>
                                <div class="col-xs-4">
                                    <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/post/edit/' . $item['id']) }}"> ویرایش </a>
                                </div>
                                <div class="col-xs-4">
                                    <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/post/delete/' . $item['id']) }}"> حذف </a>
                                </div>
                                <div class="col-xs-4">
                                    <a target="_blank" class="btn btn-block btn-success btn-rounded" href="{{ url('post/' . $item['slug']) }}"> مشاهده </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
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
                    text: "آیا از حذف این اسلاید مطمئن هستید ؟",
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
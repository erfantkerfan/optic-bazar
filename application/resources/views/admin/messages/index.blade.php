@extends('admin.layouts.app')

@section('page_name', 'برگه ها')

@section('content')

    <div class="white-box">

        <div class="portlet-body">

            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr role="row" class="heading">
                        <th>نام ارسال کننده</th>
                        <th>نام گیرنده</th>
                        <th>وضعیت</th>
                        <th>تاریخ ارسال</th>
                        <th>اعمال</th>
                    </tr>

                    @foreach($request as $k => $item)

                        <?php
                        $userSend = \App\user::where('id', $item['send_id'])->first();
                        if($userSend) $userSend = $userSend->name;

                        $user = \App\user::where('id', $item['user_id'])->first();
                        if($user) $user = $user->name;
                        ?>

                        <tr role="row" class="filter">
                            <td>{{ $userSend }}</td>
                            <td>{{ $user }}</td>
                            <td>
                                @switch($item['status'])
                                    @case('pending')
                                        <span>خوانده نشده</span>
                                    @break
                                    @case('seen')
                                        <span>خوانده شده</span>
                                    @break
                                    @case('no_reply')
                                        <span>در انتضار پاسخ</span>
                                    @break
                                    @case('reply')
                                        <span>پاسخ داده شده</span>
                                    @break
                                @endswitch
                            </td>
                            <td>{{ $item['created_at'] }}</td>
                            <td>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/message/replies/' . $item['id']) }}"> پاسخ </a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/message/delete/' . $item['id']) }}"> حذف </a>
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
                    text: "آیا از حذف این پیغام مطمئن هستید ؟",
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
@extends('admin.layouts.app')

@section('page_name', 'تحویل تقویمی')

@section('content')

    <div class="white-box">

        <div class="portlet-body">

            <div class="row">

                <div class="col-sm-2 col-xs-12">
                    <a href="{{ url('cp-manager/delivery/calender/add') }}" class="btn btn-block btn-success btn-rounded">افزودن برنامه جدید</a>
                    <br>
                    <br>
                </div>

            </div>

            <div class="clearfix"></div>

            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">
                    <tr role="row" class="heading">
                        <th>مناطق</th>
                        <th style="width: 25%">اعمال</th>
                    </tr>

                    @if(count($request) > 0)
                        @foreach($request as $item)
                            <tr role="row" class="filter">
                                <td>{{ join(', ' , json_decode($item['areas'])) }}</td>
                                <td>
                                    <div class="col-xs-6">
                                        <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/delivery/calender/edit/' . $item['id']) }}"> ویرایش </a>
                                    </div>
                                    <div class="col-xs-6">
                                        <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/delivery/calender/delete/' . $item['id']) }}"> حذف </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif

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
                    text: "آیا از حذف این مناطق مطمئن هستید ؟",
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
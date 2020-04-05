@extends('admin.layouts.app')

@section('page_name', 'پاسخ به پیام')

@section('content')

    <div class="white-box">

        <form method="post" action="" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="title" class="col-md-2 col-form-label text-md-right">نام ارسال کننده </label>

                <div class="col-md-9">
                    {{ $userSend }}
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-md-2 col-form-label text-md-right"> نام گیرنده </label>

                <div class="col-md-9">
                    {{ $userGet }}
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-md-2 col-form-label text-md-right"> پیغام </label>

                <div class="col-md-9">
                    {!! $request->message !!}
                </div>
            </div>


            @if(count($messageReply) > 0)
                <hr>
                @foreach($messageReply as $reply)

                    <?php
                    $user = \App\user::where('id', $reply['user_id'])->first();
                    if($user) $user = $user->name;
                    ?>

                    <div class="meesage_box" >
                        <div class="row" >
                            <label for="slug" class="col-md-2 col-form-label text-md-right"> پاسخ {{ $user }} </label>

                            <div class="col-md-9">
                                {!! $reply->message !!}
                            </div>
                        </div>
                    </div>

                @endforeach
            @else

            <hr>

            <div class="form-group row">
                <div class="col-md-12">
                    <div class="alert alert-warning alert-info">این پیام هیچ پاسخی برایش ارسال نشده است</div>
                </div>
            </div>
            @endif

            <hr>

            <div class="form-group row">
                <label for="content" class="col-md-2 col-form-label text-md-right"> پاسخ</label>

                <div class="col-md-10">
                    <textarea id="content" rows="18"  class="form-control textarea_editor {{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" >{{ old('content') }}</textarea>

                    @if ($errors->has('content'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-9 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت پاسخ</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

@endsection
@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages baclite">
        <div class="product_page_list">

            <div class="zm_order_lists">
                <div class="container">
                    <div class="row">

                        @if($request->image && !file_exists($request->image))
                            <div class="image_cover"><img src="{{ url($request->image) }}"></div>
                        @endif

                        <div class="zm_step_box" style="padding: 15px">
                            <h1 class="c-info-page__title">{{ $request->title }}</h1>

                            <div class="c-info-page">{!! $request->content !!}</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

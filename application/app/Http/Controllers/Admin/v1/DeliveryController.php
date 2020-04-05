<?php

namespace App\Http\Controllers\Admin\v1;

use App\DeliverAreas;
use App\DeliveryCalender;
use App\DeliveryCalenderArea;
use App\DeliveryNormal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryController extends Controller
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('admin');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }

    public function instant_or_normal_list(){

        $request = DeliveryNormal::paginate(35);

        return view('admin/delivery/instant_or_normal/index', ['request' => $request]);

    }

    public function instant_or_normal_add(){

        return view('admin/delivery/instant_or_normal/add');

    }
    public function SaveNormalAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'areas' => 'required',
            'time_difference_delivery_without_shaving' => 'required|numeric',
            'time_difference_delivery_with_shaving' => 'required|numeric',
            'difference_delivery' => 'required|numeric',
        ]);

        foreach ($ValidData['areas'] as $area) {
            if(!is_numeric($area)){
                return back()->with('error' ,  'منطقه ' . $area . ' به عدد وارد نشده است')->withInput();
            }
            $request = DeliverAreas::where('area' , $area)->orderBy('id', 'desc')->count();
            if($request){
                return back()->with('error' ,  'منطقه ' . $area . ' قبلا ثبت شده است.')->withInput();
            }
        }

        $areas = json_encode($ValidData['areas']);

        $without_time = $this->request->get('delivery_without_time');
        $without_difference_time = $this->request->get('delivery_without_difference_time');
        $without_date = $this->request->get('delivery_without_date');
        $without_price = $this->request->get('delivery_without_price');
        $withouts = [];
        if ($without_time ) {
        foreach ($without_time as $key => $item) {

            if($without_date[$key]){

                $ex = explode('/' , tr_num($without_date[$key]));
                $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                $withouts[] = [
                    'start' => $item,
                    'end' => $without_difference_time[$key],
                    'date' => $date,
                    'price' => $without_price[$key]
                ];

            }
        }
        }


        $with_time = $this->request->get('delivery_with_time');
        $with_difference_time = $this->request->get('delivery_with_difference_time');
        $with_date = $this->request->get('delivery_with_date');
        $with_price = $this->request->get('delivery_with_price');
        $withs = [];
        if ($with_time ) {
            foreach ($with_time as $key => $item) {

                if($with_date[$key]){

                    $ex = explode('/' , tr_num($with_date[$key]));
                    $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                    $withs[] = [
                        'start' => $item,
                        'end' => $with_difference_time[$key],
                        'date' => $date,
                        'price' => $with_price[$key]
                    ];

                }
            }
        }


        $receipt_date = $this->request->get('date_of_receipt');
        $receipt_time = $this->request->get('hour_of_receipt');
        $receipt_difference_time = $this->request->get('hour_of_receipt_diff');
        $receipts = [];
        if ($receipt_date ) {
            foreach ($receipt_date as $item) {
                foreach ($receipt_time as $key => $time) {

                    if($item){

                        $ex = explode('/' , tr_num($item));
                        $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                        $receipts[] = [
                            'start' => $receipt_time[$key],
                            'end' => $receipt_difference_time[$key],
                            'date' => $date,
                        ];

                    }

                }
            }
        }

        $NewDelivery = new DeliveryNormal;
        $NewDelivery->areas = $areas;
        $NewDelivery->without_shaving = $ValidData['time_difference_delivery_without_shaving'];
        $NewDelivery->with_shaving = $ValidData['time_difference_delivery_with_shaving'];
        $NewDelivery->difference = $ValidData['difference_delivery'];

        if($NewDelivery->save()){

            foreach ($ValidData['areas'] as $area) {

                foreach ($withouts as $without) {

                    $NewDeliveryAreas = new DeliverAreas;
                    $NewDeliveryAreas->delivery_id = $NewDelivery->id;
                    $NewDeliveryAreas->type = 'without';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->without_time = $without['start'];
                    $NewDeliveryAreas->without_difference_time = $without['end'];
                    $NewDeliveryAreas->without_date = date('Y-m-d', strtotime($without['date']));
                    $NewDeliveryAreas->without_price = $without['price'];
                    $NewDeliveryAreas->save();

                }

                foreach ($withs as $with) {

                    $NewDeliveryAreas = new DeliverAreas;
                    $NewDeliveryAreas->delivery_id = $NewDelivery->id;
                    $NewDeliveryAreas->type = 'with';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->with_time = $with['start'];
                    $NewDeliveryAreas->with_difference_time = $with['end'];
                    $NewDeliveryAreas->with_date = date('Y-m-d', strtotime($with['date']));
                    $NewDeliveryAreas->with_price = $with['price'];
                    $NewDeliveryAreas->save();

                }

                foreach ($receipts as $receipt) {

                    $NewDeliveryAreas = new DeliverAreas;
                    $NewDeliveryAreas->delivery_id = $NewDelivery->id;
                    $NewDeliveryAreas->type = 'receipt';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->receipt_time = $receipt['start'];
                    $NewDeliveryAreas->receipt_difference_time = $receipt['end'];
                    $NewDeliveryAreas->receipt_date = date('Y-m-d', strtotime($receipt['date']));
                    $NewDeliveryAreas->save();

                }

            }

        }


        return redirect('cp-manager/delivery/instant_or_normal')->with('success' ,  'اطلاعات با موفقیت ثبت شد.')->withInput();

    }


    public function instant_or_normal_edit(){

        $id = $this->request->id;

        $request = DeliveryNormal::where('id' , $id)->orderBy('id', 'desc')->first();
        if($request){

            $areas_json = json_decode($request->areas);

            $receipt_time = [];
            $areas = DeliverAreas::where('delivery_id' , $id)->where('area' , $areas_json[0])->get();
            $receipt_time = DeliverAreas::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'receipt')->first();
            if($receipt_time){
                $receipt_time = DeliverAreas::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'receipt')->where('receipt_date' , $receipt_time->receipt_date)->get();
            }

            $receipt_time_date = DeliverAreas::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'receipt')->get();

            $receipt_date = [];
            if($receipt_time_date){
                foreach ($receipt_time_date as $item) {
                    if(!in_array($item['receipt_date'], $receipt_date)){
                        $receipt_date[] = $item['receipt_date'];
                    }
                }
            }

            return view('admin/delivery/instant_or_normal/edit', ['request' => $request, 'areas' => $areas, 'receipt_time' => $receipt_time, 'receipt_date' => $receipt_date]);

        }

        return back('/');

    }

    public function SaveNormalEdit(){

        $id = $this->request->id;

        $request = DeliveryNormal::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request){
            return back('/');
        }

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'areas' => 'required',
            'time_difference_delivery_without_shaving' => 'required|numeric',
            'time_difference_delivery_with_shaving' => 'required|numeric',
            'difference_delivery' => 'required|numeric',
        ]);

        foreach ($ValidData['areas'] as $area) {
            if(!is_numeric($area)){
                return back()->with('error' ,  'منطقه ' . $area . ' به عدد وارد نشده است')->withInput();
            }
            $request_sd = DeliverAreas::where('area' , $area)->where('delivery_id' , '!=' , $id)->orderBy('id', 'desc')->count();
            if($request_sd){
                return back()->with('error' ,  'منطقه ' . $area . ' قبلا ثبت شده است.')->withInput();
            }
        }

        $areas = json_encode($ValidData['areas']);

        $without_time = $this->request->get('delivery_without_time');
        $without_difference_time = $this->request->get('delivery_without_difference_time');
        $without_date = $this->request->get('delivery_without_date');
        $without_price = $this->request->get('delivery_without_price');
        $withouts = [];
        if ($without_time ) {
            foreach ($without_time as $key => $item) {

                if($without_date[$key]){

                    $ex = explode('/' , tr_num($without_date[$key]));
                    $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                    $withouts[] = [
                        'start' => $item,
                        'end' => $without_difference_time[$key],
                        'date' => $date,
                        'price' => $without_price[$key]
                    ];

                }
            }
        }


        $with_time = $this->request->get('delivery_with_time');
        $with_difference_time = $this->request->get('delivery_with_difference_time');
        $with_date = $this->request->get('delivery_with_date');
        $with_price = $this->request->get('delivery_with_price');
        $withs = [];
        if ($with_time ) {
            foreach ($with_time as $key => $item) {

                if($with_date[$key]){

                    $ex = explode('/' , tr_num($with_date[$key]));
                    $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                    $withs[] = [
                        'start' => $item,
                        'end' => $with_difference_time[$key],
                        'date' => $date,
                        'price' => $with_price[$key]
                    ];

                }
            }
        }


        $receipt_date = $this->request->get('date_of_receipt');
        $receipt_time = $this->request->get('hour_of_receipt');
        $receipt_difference_time = $this->request->get('hour_of_receipt_diff');
        $receipts = [];
        if ($receipt_date ) {
            foreach ($receipt_date as $item) {
                foreach ($receipt_time as $key => $time) {

                    if($item){

                        $ex = explode('/' , tr_num($item));
                        $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                        $receipts[] = [
                            'start' => $receipt_time[$key],
                            'end' => $receipt_difference_time[$key],
                            'date' => $date,
                        ];

                    }

                }
            }
        }

        $request->areas = $areas;
        $request->without_shaving = $ValidData['time_difference_delivery_without_shaving'];
        $request->with_shaving = $ValidData['time_difference_delivery_with_shaving'];
        $request->difference = $ValidData['difference_delivery'];

        if($request->save()){

            $receipt_full_areas = DeliverAreas::where('delivery_id' , $id)->get();
            if($receipt_full_areas){
                foreach ($receipt_full_areas as $v) {
                    $v->delete();
                }
            }

            foreach ($ValidData['areas'] as $area) {

                foreach ($withouts as $without) {

                    $NewDeliveryAreas = new DeliverAreas;
                    $NewDeliveryAreas->delivery_id = $request->id;
                    $NewDeliveryAreas->type = 'without';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->without_time = $without['start'];
                    $NewDeliveryAreas->without_difference_time = $without['end'];
                    $NewDeliveryAreas->without_date = date('Y-m-d', strtotime($without['date']));
                    $NewDeliveryAreas->without_price = $without['price'];
                    $NewDeliveryAreas->save();

                }

                foreach ($withs as $with) {

                    $NewDeliveryAreas = new DeliverAreas;
                    $NewDeliveryAreas->delivery_id = $request->id;
                    $NewDeliveryAreas->type = 'with';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->with_time = $with['start'];
                    $NewDeliveryAreas->with_difference_time = $with['end'];
                    $NewDeliveryAreas->with_date = date('Y-m-d', strtotime($with['date']));
                    $NewDeliveryAreas->with_price = $with['price'];
                    $NewDeliveryAreas->save();

                }

                foreach ($receipts as $receipt) {

                    $NewDeliveryAreas = new DeliverAreas;
                    $NewDeliveryAreas->delivery_id = $request->id;
                    $NewDeliveryAreas->type = 'receipt';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->receipt_time = $receipt['start'];
                    $NewDeliveryAreas->receipt_difference_time = $receipt['end'];
                    $NewDeliveryAreas->receipt_date = date('Y-m-d', strtotime($receipt['date']));
                    $NewDeliveryAreas->save();

                }

            }

        }


        return redirect('cp-manager/delivery/instant_or_normal')->with('success' ,  'اطلاعات با موفقیت ویرایش شد.')->withInput();

    }

    public function instant_or_normal_delete(){

        $id = $this->request->id;

        $request = DeliveryNormal::where('id' , $id)->orderBy('id', 'desc')->first();
        if($request){

            $areas = DeliverAreas::where('delivery_id' , $id)->get();
            if($areas){
                foreach ($areas as $item) {
                    $item->delete();
                }
            }

            $request->delete();


            return redirect('cp-manager/delivery/instant_or_normal')->with('success' ,  'اطلاعات با موفقیت حذف شد.')->withInput();
        }

        return back('/');

    }





    public function calender_list(){

        $request = DeliveryCalender::paginate(35);

        return view('admin/delivery/calender/index', ['request' => $request]);

    }
    public function calender_add(){

        return view('admin/delivery/calender/add');

    }
    public function SaveCalenderAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'areas' => 'required',
            'capacity' => 'required|numeric',
            'difference_receive' => 'required|numeric',
            'difference_delivery' => 'required|numeric',
        ]);

        foreach ($ValidData['areas'] as $area) {
            if(!is_numeric($area)){
                return back()->with('error' ,  'منطقه ' . $area . ' به عدد وارد نشده است')->withInput();
            }
            $request = DeliveryCalenderArea::where('area' , $area)->orderBy('id', 'desc')->count();
            if($request){
                return back()->with('error' ,  'منطقه ' . $area . ' قبلا ثبت شده است.')->withInput();
            }
        }

        $areas = json_encode($ValidData['areas']);



        $receipt_date = $this->request->get('date_of_receipt');
        $receipt_time = $this->request->get('hour_of_receipt');
        $receipt_difference_time = $this->request->get('hour_of_receipt_diff');
        $receipts = [];
        if ($receipt_date ) {
            foreach ($receipt_date as $item) {
                foreach ($receipt_time as $key => $time) {

                    if($item){

                        $ex = explode('/' , tr_num($item));
                        $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                        $receipts[] = [
                            'start' => $receipt_time[$key],
                            'end' => $receipt_difference_time[$key],
                            'date' => $date,
                        ];

                    }

                }
            }
        }

        $delivery_date = $this->request->get('date_of_delivery');
        $delivery_time = $this->request->get('hour_of_delivery');
        $delivery_difference_time = $this->request->get('hour_of_delivery_diff');
        $deliverys = [];
        if ($delivery_date ) {
            foreach ($delivery_date as $item) {
                foreach ($delivery_time as $key => $time) {

                    if($item){

                        $ex = explode('/' , tr_num($item));
                        $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                        $deliverys[] = [
                            'start' => $delivery_time[$key],
                            'end' => $delivery_difference_time[$key],
                            'date' => $date,
                        ];

                    }

                }
            }
        }



        $NewDelivery = new DeliveryCalender;
        $NewDelivery->areas = $areas;
        $NewDelivery->capacity = $ValidData['capacity'];
        $NewDelivery->difference_receive = $ValidData['difference_receive'];
        $NewDelivery->difference_delivery = $ValidData['difference_delivery'];

        if($NewDelivery->save()){

            foreach ($ValidData['areas'] as $area) {

                foreach ($receipts as $receipt) {

                    $NewDeliveryAreas = new DeliveryCalenderArea;
                    $NewDeliveryAreas->delivery_id = $NewDelivery->id;
                    $NewDeliveryAreas->type = 'receipt';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->start = $receipt['start'];
                    $NewDeliveryAreas->end = $receipt['end'];
                    $NewDeliveryAreas->date = date('Y-m-d', strtotime($receipt['date']));
                    $NewDeliveryAreas->save();

                }

                foreach ($deliverys as $delivery) {

                    $NewDeliveryAreas = new DeliveryCalenderArea;
                    $NewDeliveryAreas->delivery_id = $NewDelivery->id;
                    $NewDeliveryAreas->type = 'delivery';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->start = $delivery['start'];
                    $NewDeliveryAreas->end = $delivery['end'];
                    $NewDeliveryAreas->date = date('Y-m-d', strtotime($delivery['date']));
                    $NewDeliveryAreas->save();

                }

            }

        }


        return redirect('cp-manager/delivery/calender')->with('success' ,  'اطلاعات با موفقیت ثبت شد.')->withInput();

    }

    public function calender_edit(){

        $id = $this->request->id;

        $request = DeliveryCalender::where('id' , $id)->orderBy('id', 'desc')->first();
        if($request){

            $areas_json = json_decode($request->areas);

            $receipt_time = [];
            $areas = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->get();
            $receipt_time = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'receipt')->first();
            if($receipt_time){
                $receipt_time = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'receipt')->where('date' , $receipt_time->date)->get();
            }

            $receipt_time_date = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'receipt')->get();

            $receipt_date = [];
            if($receipt_time_date){
                foreach ($receipt_time_date as $item) {
                    if(!in_array($item['date'], $receipt_date)){
                        $receipt_date[] = $item['date'];
                    }
                }
            }


            $delivery_time = [];
            $areas = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->get();
            $delivery_time = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'delivery')->first();
            if($delivery_time){
                $delivery_time = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'delivery')->where('date' , $delivery_time->date)->get();
            }

            $delivery_time_date = DeliveryCalenderArea::where('delivery_id' , $id)->where('area' , $areas_json[0])->where('type' , 'delivery')->get();

            $delivery_date = [];
            if($delivery_time_date){
                foreach ($delivery_time_date as $item) {
                    if(!in_array($item['date'], $delivery_date)){
                        $delivery_date[] = $item['date'];
                    }
                }
            }

            return view('admin/delivery/calender/edit', ['request' => $request, 'areas' => $areas, 'receipt_time' => $receipt_time, 'receipt_date' => $receipt_date, 'delivery_time' => $delivery_time, 'delivery_date' => $delivery_date]);

        }

        return back('/');

    }
    public function SaveCalenderEdit(){

        $id = $this->request->id;

        $request = DeliveryCalender::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request){
            return back('/');
        }

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'areas' => 'required',
            'capacity' => 'required|numeric',
            'difference_receive' => 'required|numeric',
            'difference_delivery' => 'required|numeric',
        ]);

        foreach ($ValidData['areas'] as $area) {
            if(!is_numeric($area)){
                return back()->with('error' ,  'منطقه ' . $area . ' به عدد وارد نشده است')->withInput();
            }
            $request_sd = DeliveryCalenderArea::where('area' , $area)->where('delivery_id' , '!=' , $id)->orderBy('id', 'desc')->count();
            if($request_sd){
                return back()->with('error' ,  'منطقه ' . $area . ' قبلا ثبت شده است.')->withInput();
            }
        }

        $areas = json_encode($ValidData['areas']);

        $receipt_date = $this->request->get('date_of_receipt');
        $receipt_time = $this->request->get('hour_of_receipt');
        $receipt_difference_time = $this->request->get('hour_of_receipt_diff');
        $receipts = [];
        if ($receipt_date ) {
            foreach ($receipt_date as $item) {
                foreach ($receipt_time as $key => $time) {

                    if($item){

                        $ex = explode('/' , tr_num($item));
                        $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                        $receipts[] = [
                            'start' => $receipt_time[$key],
                            'end' => $receipt_difference_time[$key],
                            'date' => $date,
                        ];

                    }

                }
            }
        }

        $delivery_date = $this->request->get('date_of_delivery');
        $delivery_time = $this->request->get('hour_of_delivery');
        $delivery_difference_time = $this->request->get('hour_of_delivery_diff');
        $deliverys = [];
        if ($delivery_date ) {
            foreach ($delivery_date as $item) {
                foreach ($delivery_time as $key => $time) {

                    if($item){

                        $ex = explode('/' , tr_num($item));
                        $date = jalali_to_gregorian($ex[0], $ex[1],  $ex[2], '/');

                        $deliverys[] = [
                            'start' => $delivery_time[$key],
                            'end' => $delivery_difference_time[$key],
                            'date' => $date,
                        ];

                    }

                }
            }
        }

        $request->areas = $areas;
        $request->capacity = $ValidData['capacity'];
        $request->difference_receive = $ValidData['difference_receive'];
        $request->difference_delivery = $ValidData['difference_delivery'];

        if($request->save()){

            $receipt_full_areas = DeliveryCalenderArea::where('delivery_id' , $id)->get();
            if($receipt_full_areas){
                foreach ($receipt_full_areas as $v) {
                    $v->delete();
                }
            }

            foreach ($ValidData['areas'] as $area) {

                foreach ($receipts as $receipt) {

                    $NewDeliveryAreas = new DeliveryCalenderArea;
                    $NewDeliveryAreas->delivery_id = $request->id;
                    $NewDeliveryAreas->type = 'receipt';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->start = $receipt['start'];
                    $NewDeliveryAreas->end = $receipt['end'];
                    $NewDeliveryAreas->date = date('Y-m-d', strtotime($receipt['date']));
                    $NewDeliveryAreas->save();

                }

                foreach ($deliverys as $delivery) {

                    $NewDeliveryAreas = new DeliveryCalenderArea;
                    $NewDeliveryAreas->delivery_id = $request->id;
                    $NewDeliveryAreas->type = 'delivery';
                    $NewDeliveryAreas->area = $area;
                    $NewDeliveryAreas->start = $delivery['start'];
                    $NewDeliveryAreas->end = $delivery['end'];
                    $NewDeliveryAreas->date = date('Y-m-d', strtotime($delivery['date']));
                    $NewDeliveryAreas->save();

                }


            }

        }


        return redirect('cp-manager/delivery/calender')->with('success' ,  'اطلاعات با موفقیت ویرایش شد.')->withInput();

    }

    public function calender_delete(){

        $id = $this->request->id;

        $request = DeliveryCalender::where('id' , $id)->orderBy('id', 'desc')->first();
        if($request){

            $areas = DeliveryCalenderArea::where('delivery_id' , $id)->get();
            if($areas){
                foreach ($areas as $item) {
                    $item->delete();
                }
            }

            $request->delete();


            return redirect('cp-manager/delivery/calender')->with('success' ,  'اطلاعات با موفقیت حذف شد.')->withInput();
        }

        return back('/');

    }


}

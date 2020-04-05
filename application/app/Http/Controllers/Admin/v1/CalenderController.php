<?php

namespace App\Http\Controllers\Admin\v1;

use App\calender;
use App\Http\Controllers\SettingController;
use App\occupiedTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CalenderController extends Controller
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('admin');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }

    public function normalDelivery(){

        $order_time = SettingController::get_package_optien('calender_order_time');
        $calender_order_time = '';
        if($order_time){
            $calender_order_time = json_decode($order_time);
        }

        $order_time = SettingController::get_package_optien('calender_order_get_time');
        $calender_order_get_time = '';
        if($order_time){
            $calender_order_get_time = json_decode($order_time);
        }

        $occupiedTimeList = array();
        $occupiedTime = occupiedTime::where('date' , '>=' , date('Y-m-d'))->select('date')->orderBy('id', 'desc')->get();
        if($occupiedTime){
            foreach ($occupiedTime as $date){
                $dateTime = Carbon::parse($date['date']);
                $dateTime = $dateTime->year . '/' . $dateTime->month . '/' . $dateTime->day;
                $occupiedTimeList[] = $dateTime;
            }
        }



        return view('admin/calender/normal-delivery', ['calender_order_time' => $calender_order_time, 'calender_order_get_time' => $calender_order_get_time, 'occupiedTime' => $occupiedTimeList]);
    }
    public function actionNormalDelivery(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'calender_order_get_time' => 'required',
            'calender_order_time' => 'required',
            'calender_timepic_get' => 'required',
            'calender_timepic_send_notlathe' => 'required',
            'calender_timepic_send_lathe' => 'required',
            'calender_order_counter_get' => 'required',
            'calender_order_counter_send' => 'required',
        ]);

        if($ValidData['calender_order_time']){

            $calender_time = array();
            $calender_order_time_unit = $this->request->get('calender_order_time_unit');
            $calender_order_time_price = $this->request->get('calender_order_time_price');
            foreach ($ValidData['calender_order_time'] as $key => $validDatum) {
                $calender_time[] = ['time' => $validDatum, 'unit' => $calender_order_time_unit[$key], 'price' => $calender_order_time_price[$key]];
            }

            //$sort = collect($calender_order_time['time'])->sort()->values()->all();

            $calender_order_time = json_encode($calender_time) ;
            SettingController::update_package_optien('calender_order_time', $calender_order_time);
        }

        if($ValidData['calender_order_get_time']){

            $calender_time = array();
            $calender_order_get_time_unit = $this->request->get('calender_order_get_time_unit');
            foreach ($ValidData['calender_order_get_time'] as $key => $validDatum) {
                $calender_time[] = ['time' => $validDatum, 'unit' => $calender_order_get_time_unit[$key]];
            }


            $calender_order_get_time = json_encode($calender_time) ;
            SettingController::update_package_optien('calender_order_get_time', $calender_order_get_time);
        }


        /*if($this->request->get('calender_date_off')){

            $calender_date_off = $this->request->get('calender_date_off');

            $occupiedTime = occupiedTime::where('date' , '!=' , $calender_date_off)->orderBy('id', 'desc')->get();
            if($occupiedTime){
                foreach ($occupiedTime as $date){
                    $date->delete();
                }
            }

            foreach ($calender_date_off as $date){
                $occupiedTime = occupiedTime::where('date' , $date)->orderBy('id', 'desc')->first();
                if(!$occupiedTime){
                    $newOccupiedTime = new occupiedTime;
                    $newOccupiedTime->date = $date;
                    $newOccupiedTime->unit = '';
                    $newOccupiedTime->start_time = 1;
                    $newOccupiedTime->end_time = 24;
                    $newOccupiedTime->save();
                }
            }
        }*/

        SettingController::update_package_optien('calender_timepic_get', $ValidData['calender_timepic_get']);
        SettingController::update_package_optien('calender_timepic_send_notlathe', $ValidData['calender_timepic_send_notlathe']);
        SettingController::update_package_optien('calender_timepic_send_lathe', $ValidData['calender_timepic_send_lathe']);
        SettingController::update_package_optien('calender_order_counter_get', $ValidData['calender_order_counter_get']);
        SettingController::update_package_optien('calender_order_counter_send', $ValidData['calender_order_counter_send']);

        return redirect('cp-manager/calender/normal-delivery')->with('success' ,  'اطلاعات تنظیمات بروز رسانی شد.')->withInput();
    }

    public function holidays(){

        $occupiedTimeList = array();
        $occupiedTime = occupiedTime::where('date' , '>=' , date('Y-m-d'))->select('date')->orderBy('id', 'desc')->get();
        if($occupiedTime){
            foreach ($occupiedTime as $date){
                $dateTime = Carbon::parse($date['date']);
                $dateTime = gregorian_to_jalali($dateTime->year , $dateTime->month , $dateTime->day, '/');
                $occupiedTimeList[] = $dateTime;
            }
        }



        return view('admin/calender/holidays', ['occupiedTime' => $occupiedTimeList]);
    }
    public function actionHolidays(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'calender_date_off' => 'required',
        ]);

        if($this->request->get('calender_date_off')){

            $calender_date_off = $this->request->get('calender_date_off');
            $calender_date_off_n = array();
            if($calender_date_off){
                foreach ($calender_date_off as $date){
                    $date = explode('/', $date);
                    $calender_date_off_n[] = jalali_to_gregorian($date[0], $date[1], $date[2] , '/');
                }
                $calender_date_off = $calender_date_off_n;
            }

            $occupiedTime = occupiedTime::where('date' , '!=' , $calender_date_off)->orderBy('id', 'desc')->get();
            if($occupiedTime){
                foreach ($occupiedTime as $date){
                    $date->delete();
                }
            }

            foreach ($calender_date_off as $date){
                $occupiedTime = occupiedTime::where('date' , $date)->orderBy('id', 'desc')->first();
                if(!$occupiedTime){
                    $newOccupiedTime = new occupiedTime;
                    $newOccupiedTime->date = $date;
                    $newOccupiedTime->unit = '';
                    $newOccupiedTime->start_time = 1;
                    $newOccupiedTime->end_time = 24;
                    $newOccupiedTime->save();
                }
            }
        }

        return redirect('cp-manager/calender/holidays')->with('success' ,  'اطلاعات تنظیمات بروز رسانی شد.')->withInput();
    }

    public function calenders(){

        $date = $this->request->get('date');
        $unit = $this->request->get('unit');
        if(!$date){
            $date = date('Y/m/d');
        }
        if(!$unit){
            $unit = 'group_1';
        }

        $calender_get_time = '';
        $calender_send_time = array();

        $calender = calender::where('date' , $date)->where('unit' , $unit)->orderBy('id', 'desc')->first();
        if($calender) {

            $occupiedTime = occupiedTime::where('date' , $calender->date)->orderBy('id', 'desc')->first();
            if(!$occupiedTime){

                if($calender->get_time){
                    $calender_get_time = json_decode($calender->get_time);
                }
                if($calender->send_time){
                    $calender_send_time = json_decode($calender->send_time);
                }

            }

        }else{

            $order_time = SettingController::get_package_optien('calender_order_time');
            $calender_time_pic = SettingController::get_package_optien('calender_time_pic');
            $calender_send_time_array = array();
            $calender_send_time = array();
            $calender_get_time = array();
            if($order_time){
                $calender_send_time_array = json_decode($order_time);

                if($calender_send_time_array){
                    foreach ($calender_send_time_array as $v){
                        if($v->unit == $unit){
                            $calender_send_time[] = $v->time + $calender_time_pic;
                            $calender_get_time[] = $v->time;
                        }
                    }
                }
            }

        }



        return view('admin/calender/calenders', ['calender_get_time' => $calender_get_time , 'calender_send_time' => $calender_send_time , 'date' => $date , 'unit' => $unit]);
    }
    public function actionCalenders(){

        $ValidData = $this->validate($this->request,[
            'date' => 'required',
            'unit' => 'required',
            'calender_get_time' => 'required',
            'calender_send_time' => 'required',
        ]);

        $calender_get_time = '';
        $calender_send_time = '';

        if($ValidData['calender_get_time']){
            $sort = collect($ValidData['calender_get_time'])->sort()->values()->all();
            $calender_get_time = json_encode($sort) ;
        }
        if($ValidData['calender_send_time']){
            $sort = collect($ValidData['calender_send_time'])->sort()->values()->all();
            $calender_send_time = json_encode($sort) ;
        }

        $calender = calender::where('date' , $ValidData['date'])->orderBy('id', 'desc')->first();
        if(!$calender){
            $newCalender = new calender;
            $newCalender->date = $ValidData['date'];
            $newCalender->unit = $ValidData['unit'];
            $newCalender->get_time = $calender_get_time;
            $newCalender->send_time = $calender_send_time;
            $newCalender->save();
        }else{
            $calender->get_time = $calender_get_time;
            $calender->send_time = $calender_send_time;
            $calender->save();
        }

        return redirect('cp-manager/calenders?date='.$ValidData['date'].'&unit=' . $ValidData['unit'])->with('success' ,  'اطلاعات تنظیمات بروز رسانی شد.')->withInput();

    }


    public function calenderDelivery(){

        return view('admin/calender/calender-delivery');

    }
    public function actionCalenderDelivery(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'areas' => 'required',
            'calender_getdv_time' => 'required',
            'calender_senddv_time' => 'required',
            'calender_timepic_getdv' => 'required',
            'calender_timepic_senddv' => 'required',
            'calender_dv_count' => 'required',
        ]);


        $calender_dv_areas = $this->request->get('areas');
        $calender_dv_areas = json_encode($calender_dv_areas) ;
        SettingController::update_package_optien('calender_dv_areas', $calender_dv_areas);

        $calender_timepic_getdv = $this->request->get('calender_timepic_getdv');
        SettingController::update_package_optien('calender_timepic_getdv', $calender_timepic_getdv);

        $calender_timepic_getdv = $this->request->get('calender_timepic_senddv');
        SettingController::update_package_optien('calender_timepic_senddv', $calender_timepic_getdv);

        $calender_dv_count = $this->request->get('calender_dv_count');
        SettingController::update_package_optien('calender_dv_count', $calender_dv_count);



        if($ValidData['calender_getdv_time']){

            $calender_time = array();
            $calender_getdv_time_date = $this->request->get('calender_getdv_time_date');
            $calender_getdv_time_price = $this->request->get('calender_getdv_time_price');
            foreach ($ValidData['calender_getdv_time'] as $key => $validDatum) {
                $calender_time[] = ['time' => $validDatum, 'date' => $calender_getdv_time_date[$key], 'price' => $calender_getdv_time_price[$key]];
            }

            //$sort = collect($calender_order_time['time'])->sort()->values()->all();

            $calender_order_time = json_encode($calender_time) ;
            SettingController::update_package_optien('calender_getdv_time', $calender_order_time);
        }

        if($ValidData['calender_senddv_time']){

            $calender_time = array();
            $calender_getdv_time_date = $this->request->get('calender_senddv_time_date');
            $calender_getdv_time_price = $this->request->get('calender_senddv_time_price');
            foreach ($ValidData['calender_senddv_time'] as $key => $validDatum) {
                $calender_time[] = ['time' => $validDatum, 'date' => $calender_getdv_time_date[$key], 'price' => $calender_getdv_time_price[$key]];
            }

            //$sort = collect($calender_order_time['time'])->sort()->values()->all();

            $calender_order_time = json_encode($calender_time) ;
            SettingController::update_package_optien('calender_senddv_time', $calender_order_time);
        }


        return back()->with('success' ,  'اطلاعات تنظیمات بروز رسانی شد.')->withInput();
    }

}

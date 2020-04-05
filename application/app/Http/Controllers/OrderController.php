<?php

namespace App\Http\Controllers;

use App\Brand;
use App\calender;
use App\country;
use App\coupon;
use App\couponUsed;
use App\DeliverAreas;
use App\DeliveryCalender;
use App\DeliveryCalenderArea;
use App\DeliveryNormal;
use App\Favorite;
use App\labratorService;
use App\Lens_detail;
use App\lensPrice;
use App\occupiedTime;
use App\optical_glassPrice;
use App\Order;
use App\Order_detail;
use App\Prescription;
use App\Product;
use App\SendSMS;
use App\transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SoapClient;

class OrderController extends ProductSmartController
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('user');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }


    public static function update_optien($order_id, $key , $value){

        $request = Order_detail::where('order_id', $order_id)->where('key', $key)->first();
        if($request){
            $request->val = $value;
            $request->save();

            return $request->id;
        }else{
            $requestNew = new Order_detail;
            $requestNew->order_id = $order_id;
            $requestNew->key = $key;
            $requestNew->val = $value;
            $requestNew->save();

            return $requestNew->id;
        }

    }

    public static function get_optien($order_id, $key){

        $request = Order_detail::where('order_id', $order_id)->where('key', $key)->first();
        if($request) return $request->val;

        return false;

    }

    /*order blank page*/

    public function index(){
        return redirect('order/new');
    }

    public function NewOrder(){

        $UserLogin = Auth::user();
        $prescriptions = Prescription::where('user_id', $UserLogin->id)->orderBy('created_at', 'desc')->get();

        $followUp = array();

        $orderKey = $this->request->get('follow-up');
        if($orderKey){
            $request = Order::where('order_key' , $orderKey)->where('user_id' , $UserLogin->id)->orderBy('id', 'desc')->first();
            if(!$request) return redirect('order/new')->with('error' , 'اطلاعات ارسال شده اشتباه است.');

            $lock = OrderController::get_optien($request->id, 'presentation_box_op_lock');
            if($lock == 'closed'){
                $followUp['presentation'] = $request->prescription_id;
            }

            $lock = OrderController::get_optien($request->id, 'products_box_op_lock');
            if($lock == 'closed'){
                $followUp['product'] = $request->product_id;
            }

            $lock = OrderController::get_optien($request->id, 'data_box_op_lock');
            if($lock == 'closed'){
                $followUp['date']['get_box_date'] = $request->get_box_date;
                $followUp['date']['get_box_time'] = $request->get_box_time;
                $followUp['date']['send_box_date'] = $request->send_box_date;
                $followUp['date']['send_box_time'] = $request->send_box_time;
            }

            $followUp['follow'] = $orderKey;
        }

        $count = $this->LensProductNumber();

        $sph_lens = $this->SearchSph();
        $cyl_lens = $this->SearchCyl();
        $axis_lens = $this->SearchAxis();

        $sph_optic = $this->SearchSphOptic();
        $cyl_optic = $this->SearchCylOptic();
        $axis_optic = $this->SearchAxisOptic();
        $add_optic = $this->SearchAddOptic();

        $pd = $this->SearchPd();


        return view('site/order/new' , ['prescriptions' => $prescriptions, 'followUp' => $followUp, 'count' => $count,
            'sph' => $sph_lens, 'cyl' => $cyl_lens, 'axis' => $axis_lens,
            'sph_optic' => $sph_optic, 'cyl_optic' => $cyl_optic, 'axis_optic' => $axis_optic, 'add_optic' => $add_optic, 'pd' => $pd
        ]);
    }

    public function ActionNewOrder(){
        return redirect('order/new/lens');
    }

    /*order group*/

    public function NewOrderLens(){
        return $this->ShowNewOrder('lens');
    }

    public function NewOrderOptical(){
        return $this->ShowNewOrder('optical');
    }

    /*show order dinghies*/

    public function ShowNewOrder($status){

        $fav_product = $this->request->get('fav_product');
        $search_name = $this->request->get('search_name');

        $product = array();
        $activeFillter = array();

        $whereFillter = array();

        $UserLogin = Auth::user();

        if($search_name){
            $search_name_ex = explode(' - ', $search_name);
            if(isset($search_name_ex[1])){

                $whereFillter[] = array('products.sku', $search_name_ex[1]);
                $whereFillter[] = array('brands.name', $search_name_ex[0]);

            }elseif(is_numeric($search_name)){
                $whereFillter[] = array('products.sku', $search_name);
            }else{
                $whereFillter[] = array('brands.name', $search_name);
            }
        }


        switch ($status){
            case "lens" :

                if($fav_product == 'yes'){

                    $product = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->join('favorites', 'favorites.product_id' , '=' , 'products.id')
                        ->where($whereFillter)
                        ->where('products.type', 1)
                        ->where('favorites.status', 'active')
                        ->where('favorites.user_id', $UserLogin->id)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.price','products.inventory','brands.name')
                        ->paginate(36);

                }else{
                    $product = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->where($whereFillter)
                        ->where('products.type', 1)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.price','products.inventory','brands.name')
                        ->paginate(36);
                }

                break;
            case "optical" :

                if($fav_product == 'yes'){

                    $product = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->join('favorites', 'favorites.product_id' , '=' , 'products.id')
                        ->where($whereFillter)
                        ->where('products.type', 2)
                        ->where('favorites.stuts', 'active')
                        ->where('favorites.user_id', $UserLogin->id)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.price','products.inventory','brands.name')
                        ->paginate(36);

                }else{

                    $product = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->where($whereFillter)
                        ->where('products.type', 2)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.price','products.inventory','brands.name')
                        ->paginate(36);

                }

                break;
        }

        $favoriteList = array();
        if($product){
            foreach ($product as $pro){
                $requestFavorite = Favorite::where('product_id' , $pro->id)->where('user_id' , $UserLogin->id)->first();
                if($requestFavorite){
                    $favoriteList[] = $requestFavorite->status;
                }else{
                    $favoriteList[] = 'inactive';
                }
            }
        }


        return view('site/order/new' , ['tabKey' => $status, 'product' => $product, 'favoriteList' => $favoriteList, 'activeFillter' => $activeFillter]);
    }

    public function NewOrderProductAjax(){

        $fav_product = $this->request->get('fav_product');
        $search_name = $this->request->get('search_name');
        $status = ($this->request->get('status')) ? $this->request->get('status') : 'lens';
        $orderKey = $this->request->get('order_key');
        $filterSages = $this->request->get('json');

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        $prescriptions = Prescription::where('id' , $request->prescription_id)->orderBy('id', 'desc')->first();
        if(!$prescriptions) return response()->json([
            'staus' => 'error',
            'error' => 'نسخه این سفارش یافت نشد.'
        ]);

        if($prescriptions->type_product){
            $status = ($prescriptions->type_product == 'lens') ? 'lens' : 'optical';
        }

        $product = array();
        $activeFillter = array();
        $filters = array();

        $whereFillter = array();
        $FillterAcrive = array();

        $UserLogin = Auth::user();


        if($filterSages){
            $data = json_decode($filterSages);

            if (isset($data->filter_brand) && !empty($data->filter_brand)) {
                $whereFillter[] = array('brands.name', $data->filter_brand);
            }

            if (isset($data->filter_country) && !empty($data->filter_country)) {
                $whereFillter[] = array('products.country', $data->filter_country);
            }

            if($status == 'lens') {

                if (isset($data->filter_astigmatism) && !empty($data->filter_astigmatism)) {
                    $whereFillter[] = array('lens_details.astigmatism', $data->filter_astigmatism);
                }

                if (isset($data->filter_consumption_period) && !empty($data->filter_consumption_period)) {
                    $whereFillter[] = array('lens_details.consumption_period', $data->filter_consumption_period);
                }

                if (isset($data->filter_curvature) && !empty($data->filter_curvature)) {
                    $whereFillter[] = array('lens_details.curvature', $data->filter_curvature);
                }

                if (isset($data->filter_diagonal) && !empty($data->filter_diagonal)) {
                    $whereFillter[] = array('lens_prices.diagonal', $data->filter_diagonal);
                }

                if (isset($data->filter_number) && !empty($data->filter_number)) {
                    $whereFillter[] = array('lens_details.number', $data->filter_number);
                }

                if (isset($data->filter_structure) && !empty($data->filter_structure)) {
                    $whereFillter[] = array('lens_details.structure', $data->filter_structure);
                }


                if (isset($data->max_filter_abatement) && !empty($data->max_filter_abatement)) {
                    $whereFillter[] = array('lens_details.abatement', '<=', $data->max_filter_abatement);
                }

                if (isset($data->min_filter_abatement) && !empty($data->min_filter_abatement)) {
                    $whereFillter[] = array('lens_details.abatement', '>=', $data->min_filter_abatement);
                }

                if (isset($data->max_filter_oxygen_supply) && !empty($data->max_filter_oxygen_supply)) {
                    $whereFillter[] = array('lens_details.oxygen_supply', '<=', $data->max_filter_oxygen_supply);
                }

                if (isset($data->min_filter_oxygen_supply) && !empty($data->min_filter_oxygen_supply)) {
                    $whereFillter[] = array('lens_details.oxygen_supply', '>=', $data->min_filter_oxygen_supply);
                }

                if (isset($data->max_filter_thickness) && !empty($data->max_filter_thickness)) {
                    $whereFillter[] = array('lens_details.thickness', '<=', $data->max_filter_thickness);
                }

                if (isset($data->min_filter_thickness) && !empty($data->min_filter_thickness)) {
                    $whereFillter[] = array('lens_details.thickness', '>=', $data->min_filter_thickness);
                }

                if (isset($data->max_filter_price) && !empty($data->max_filter_price)) {
                    $whereFillter[] = array('lens_prices.price', '<=', $data->max_filter_price);
                }

                if (isset($data->min_filter_price) && !empty($data->min_filter_price)) {
                    $whereFillter[] = array('lens_prices.price', '>=', $data->min_filter_price);
                }
            }
            else{

                if (isset($data->filter_size) && !empty($data->filter_size)) {
                    $whereFillter[] = array('optical_glass_details.size', $data->filter_size);
                }

                if (isset($data->filter_type) && !empty($data->filter_type)) {
                    $whereFillter[] = array('optical_glass_details.type', $data->filter_type);
                }

                if (isset($data->filter_curvature) && !empty($data->filter_curvature)) {
                    $whereFillter[] = array('optical_glass_details.curvature', $data->filter_curvature);
                }

                if (isset($data->filter_refractive_index) && !empty($data->filter_refractive_index)) {
                    $whereFillter[] = array('optical_glass_details.refractive_index', $data->filter_refractive_index);
                }

                if (isset($data->filter_anti_reflex_color) && !empty($data->filter_anti_reflex_color)) {
                    $whereFillter[] = array('optical_glass_details.anti_reflex_color', $data->filter_anti_reflex_color);
                }

                if (isset($data->filter_block) && !empty($data->filter_block)) {
                    $whereFillter[] = array('optical_glass_details.block', $data->filter_block);
                }

                if (isset($data->filter_bloc_troll) && !empty($data->filter_bloc_troll)) {
                    $whereFillter[] = array('optical_glass_details.bloc_troll', $data->filter_bloc_troll);
                }

                if (isset($data->filter_photocrophy) && !empty($data->filter_photocrophy)) {
                    $whereFillter[] = array('optical_glass_details.photocrophy', $data->filter_photocrophy);
                }

                if (isset($data->filter_photo_color) && !empty($data->filter_photo_color)) {
                    $whereFillter[] = array('optical_glass_details.photo_color', $data->filter_photo_color);
                }

                if (isset($data->filter_polycarbonate) && !empty($data->filter_polycarbonate)) {
                    $whereFillter[] = array('optical_glass_details.polycarbonate', $data->filter_polycarbonate);
                }

                if (isset($data->filter_polycarbonate) && !empty($data->filter_polycarbonate)) {
                    $whereFillter[] = array('optical_glass_details.polycarbonate', $data->filter_polycarbonate);
                }

                if (isset($data->filter_poly_break) && !empty($data->filter_poly_break)) {
                    $whereFillter[] = array('optical_glass_details.poly_break', $data->filter_poly_break);
                }

                if (isset($data->filter_color_white) && !empty($data->filter_color_white)) {
                    $whereFillter[] = array('optical_glass_details.color_white', $data->filter_color_white);
                }

                if (isset($data->filter_colored_score) && !empty($data->filter_colored_score)) {
                    $whereFillter[] = array('optical_glass_details.colored_score', $data->filter_colored_score);
                }

                if (isset($data->filter_watering) && !empty($data->filter_watering)) {
                    $whereFillter[] = array('optical_glass_details.watering', $data->filter_watering);
                }

                if (isset($data->filter_structure) && !empty($data->filter_structure)) {
                    $whereFillter[] = array('optical_glass_details.structure', $data->filter_structure);
                }

                if (isset($data->filter_yu_vie) && !empty($data->filter_yu_vie)) {
                    $whereFillter[] = array('optical_glass_details.yu_vie', $data->filter_yu_vie);
                }

                if (isset($data->max_filter_price) && !empty($data->max_filter_price)) {
                    $whereFillter[] = array('optical_glass_prices.price', '<=', $data->max_filter_price);
                }

                if (isset($data->min_filter_price) && !empty($data->min_filter_price)) {
                    $whereFillter[] = array('optical_glass_prices.price', '>=', $data->min_filter_price);
                }

            }

            foreach ($data as $key => $v){
                if($v) {
                    $FillterAcrive[$key] = $v;
                }
            }
        }

        if($search_name){
            $search_name_ex = explode(' - ', $search_name);
            if(isset($search_name_ex[1])){

                $whereFillter[] = array('products.sku', $search_name_ex[1]);
                $whereFillter[] = array('brands.name', 'LIKE' , '%' . $search_name_ex[0] . '%');

            }elseif(is_numeric($search_name)){
                $whereFillter[] = array('products.sku', $search_name);
            }else{
                $whereFillter[] = array('brands.name', 'LIKE' , '%' . $search_name . '%');
            }
        }

        switch ($status){
            case "lens" :



                $rsph = '';
                $rcyl = '';
                $lsph = '';
                $lcyl = '';


                if($prescriptions->rcount != 'no_select' && $prescriptions->rcount > 0) {

                    /* daryaft sph right */

                    $Group = $this->LensProductGroupSph();
                    $set_rsph = false;

                    if (((float)$prescriptions->rsph) > 2) {

                        foreach ($Group as $item) {
                            if (!$set_rsph && (((float)$prescriptions->rsph) < (float)$item)) {

                                $nsph = str_replace('+', '', (string) ($item - 1));
                                $rsph = '+' . $nsph . ' +' . str_replace('+', '', (string) $item);
                                $set_rsph = true;

                            }
                        }

                    } elseif (((float)$prescriptions->rsph) < -2) {

                        foreach ($Group as $item) {

                            $item = ((int)$item) * -1;

                            if (!$set_rsph && (((float)$prescriptions->rsph) > $item)) {

                                $nsph = str_replace('-', '', (string) ($item + 1));
                                $rsph = '-' . $nsph . ' -' . str_replace('-', '', (string) $item);
                                $set_rsph = true;

                            }
                        }


                    } else {

                        if (((float)$prescriptions->rsph) > 0) {
                            $rsph = '0 +1';
                        }else{
                            $rsph = '0 -1';
                        }

                    }



                    /* daryaft rcyl right */

                    if ($prescriptions->rcyl != 'no_select') {
                        if (((float)$prescriptions->rcyl) != 0) {


                            $rcyl = $prescriptions->rcyl;


                        } else {

                            $rcyl = "-2";

                        }
                    }

                }

                if($prescriptions->lcount != 'no_select' && $prescriptions->lcount > 0) {

                    /* daryaft sph left */

                    $Group = $this->LensProductGroupSph();
                    $set_lsph = false;

                    if (((float)$prescriptions->lsph) > 2) {

                        foreach ($Group as $item) {
                            if (!$set_lsph && (((float)$prescriptions->lsph) < (float)$item)) {

                                $nsph = str_replace('+', '', (string) ($item - 1));
                                $lsph = '+' . $nsph . ' +' . str_replace('+', '', (string) $item);
                                $set_lsph = true;

                            }
                        }

                    } elseif (((float)$prescriptions->lsph) < -2) {


                        foreach ($Group as $item) {
                            if (!$set_lsph && (((float)$prescriptions->lsph) < (float)$item)) {

                                $nsph = str_replace('-', '', (string) ($item + 1));
                                $lsph = '-' . $nsph . ' -' . str_replace('-', '', (string) $item);
                                $set_lsph = true;

                            }
                        }


                    } else {

                        if (((float)$prescriptions->lsph) > 0) {
                            $lsph = '0 +1';
                        }else{
                            $lsph = '0 -1';
                        }
                    }




                    /* daryaft lcyl right */

                    if ($prescriptions->lcyl != 'no_select') {

                        if (((float)$prescriptions->lcyl) != 0) {


                            $lcyl = $prescriptions->lcyl;


                        } else {

                            $lcyl = "-2";

                        }
                    }


                }



                // daryaft mahsolat


                $product = [];
                $productID = [];
                $productSPH = [];
                if($fav_product == 'yes'){

                    if($prescriptions->rcount > 0){


                        $productListRight = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                            ->join('favorites', 'favorites.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('lens_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rcyl, $lcyl) {
                                if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', $lcyl)->orWhere('cyl', null);
                                }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', null);
                                }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){
                                    $query->where('cyl', $lcyl)->orWhere('cyl', null);
                                }
                            })
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $rsph);
                            })
                            ->where('products.type', 1)
                            ->where('products.status', 'active')
                            ->where('favorites.status', 'active')
                            ->where('lens_prices.price', '>' , 0)
                            ->where('favorites.user_id', $UserLogin->id)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','lens_prices.sph','lens_prices.id as price_id','lens_prices.original_price','lens_prices.price')
                            ->get();


                    }
                    if($prescriptions->lcount > 0){


                        $productListLeft = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                            ->join('favorites', 'favorites.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('lens_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rcyl, $lcyl) {
                                if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', $lcyl)->orWhere('cyl', null);
                                }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', null);
                                }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){
                                    $query->where('cyl', $lcyl)->orWhere('cyl', null);
                                }
                            })
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $lsph);
                            })
                            ->where('products.type', 1)
                            ->where('products.status', 'active')
                            ->where('favorites.status', 'active')
                            ->where('lens_prices.price', '>' , 0)
                            ->where('favorites.user_id', $UserLogin->id)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','lens_prices.sph','lens_prices.id as price_id','lens_prices.original_price','lens_prices.price')
                            ->get();

                    }

                }
                else{


                    if($prescriptions->rcount > 0){

                        $productListRight = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('lens_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rcyl, $lcyl) {
                                if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', $lcyl)->orWhere('cyl', null);
                                }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', null);
                                }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){
                                    $query->where('cyl', $lcyl)->orWhere('cyl', null);
                                }
                            })
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $rsph);
                            })
                            ->where('products.type', 1)
                            ->where('products.status', 'active')
                            ->where('lens_prices.price', '>' , 0)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','lens_prices.sph','lens_prices.id as price_id','lens_prices.original_price','lens_prices.price')
                            ->get();

                    }
                    if($prescriptions->lcount > 0){


                        $productListLeft = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('lens_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rcyl, $lcyl) {
                                if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', $lcyl)->orWhere('cyl', null);
                                }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){
                                    $query->where('cyl', $rcyl)->orWhere('cyl', null);
                                }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){
                                    $query->where('cyl', $lcyl)->orWhere('cyl', null);
                                }
                            })
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $lsph);
                            })
                            ->where('products.type', 1)
                            ->where('products.status', 'active')
                            ->where('lens_prices.price', '>' , 0)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','lens_prices.sph','lens_prices.id as price_id','lens_prices.original_price','lens_prices.price')
                            ->get();

                    }

                }


                break;
            case "optical" :

                $rsph = '';
                $rcyl = '';
                $lsph = '';
                $lcyl = '';


                if($prescriptions->rcount != 'no_select' && $prescriptions->rcount > 0) {

                    /* daryaft sph right */

                    $Group = $this->OpticalGlassProductGroupSph();
                    $set_rsph = false;

                    if (((float)$prescriptions->rsph) > 2) {

                        if(($prescriptions->rsph == (int) $prescriptions->rsph) && ((int) $prescriptions->rsph) % 2 == 0){

                            $sph = (int) $prescriptions->rsph;
                            $rsph = '+' . str_replace('+', '', (string) $sph);

                        }else {

                            foreach ($Group as $item) {
                                if (!$set_rsph && (((float)$prescriptions->rsph) < (float)$item)) {

                                    $rsph = '+' . str_replace('+', '', $item);
                                    $set_rsph = true;

                                }
                            }
                        }

                    } elseif (((float)$prescriptions->rsph) < -2) {

                        if(($prescriptions->rsph == (int) $prescriptions->rsph) && ((int) $prescriptions->rsph) % 2 == 0){

                            $sph = (int) $prescriptions->rsph;
                            $rsph = '-' . str_replace('-', '', (string) $sph);

                        }else{

                            foreach ($Group as $item) {

                                $item = ((int)$item) * -1;

                                if (!$set_rsph && (((float)$prescriptions->rsph) > $item)) {

                                    $rsph = '-' . str_replace('-', '', $item);
                                    $set_rsph = true;

                                }
                            }

                        }


                    } else {

                        if (((float)$prescriptions->rsph) > 0) {
                            $rsph = '+2';
                        }else{
                            $rsph = '-2';
                        }

                    }



                    /* daryaft rcyl right */

                    if ($prescriptions->rcyl != 'no_select') {

                        $Group = $this->OpticalGlassProductGroupCyl();

                        $set_rcyl = false;

                        if (((float)$prescriptions->rcyl) > 2) {

                            if(($prescriptions->rcyl == (int) $prescriptions->rcyl) && ((int) $prescriptions->rcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->rcyl;
                                $rcyl = '+' . str_replace('+', '', (string) $cyl);

                            }else {

                                foreach ($Group as $item) {
                                    if (!$set_rcyl && (((float)$prescriptions->rcyl) < (float)$item)) {

                                        $rcyl = '+' . str_replace('+', '', (string) $item);
                                        $set_rcyl = true;

                                    }
                                }

                            }

                        } elseif (((float)$prescriptions->rcyl) < -2) {


                            if(($prescriptions->rcyl == (int) $prescriptions->rcyl) && ((int) $prescriptions->rcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->rcyl;
                                $rcyl = '-' . str_replace('-', '', (string) $cyl);

                            }else {

                                foreach ($Group as $item) {

                                    $item = ((int)$item) * -1;

                                    if (!$set_rcyl && (((float)$prescriptions->rcyl) > $item)) {

                                        $rcyl = '-' . str_replace('-', '', (string) $item);
                                        $set_rcyl = true;

                                    }
                                }

                            }


                        } else {


                            if (((float)$prescriptions->rcyl) > 0) {
                                $rcyl = '+2';
                            }else{
                                $rcyl = '-2';
                            }

                        }


                        /* join kardan rsph va rcyl baraye search */

                        if ($rcyl) {
                            $rsph = $rsph . ' ' . $rcyl;
                        }

                    }


                }

                if($prescriptions->lcount != 'no_select' && $prescriptions->lcount > 0) {

                    /* daryaft sph right */

                    $Group = $this->OpticalGlassProductGroupSph();
                    $set_lsph = false;

                    if (((float)$prescriptions->lsph) > 2) {

                        if(($prescriptions->lsph == (int) $prescriptions->lsph) && ((int) $prescriptions->lsph) % 2 == 0){

                            $sph = (int) $prescriptions->lsph;
                            $lsph = '+' . str_replace('+', '', (string) $sph);

                        }else {
                            foreach ($Group as $item) {
                                if (!$set_lsph && (((float)$prescriptions->lsph) < (float)$item)) {

                                    $lsph = '+' . str_replace('+', '', (string) $item);
                                    $set_lsph = true;

                                }
                            }
                        }

                    } elseif (((float)$prescriptions->lsph) < -2) {

                        if(($prescriptions->lsph == (int) $prescriptions->lsph) && ((int) $prescriptions->lsph) % 2 == 0){

                            $sph = (int) $prescriptions->lsph;
                            $lsph = '-' . str_replace('-', '', (string) $sph);

                        }else{

                            foreach ($Group as $item) {

                                $item = ((int)$item) * -1;

                                if (!$set_lsph && (((float)$prescriptions->lsph) > $item)) {

                                    $lsph = '-' . str_replace('-', '', (string) $item);
                                    $set_lsph = true;

                                }
                            }

                        }


                    } else {

                        if (((float)$prescriptions->lsph) > 0) {
                            $lsph = '+2';
                        }else{
                            $lsph = '-2';
                        }

                    }



                    /* daryaft lcyl right */

                    if ($prescriptions->lcyl != 'no_select') {

                        $Group = $this->OpticalGlassProductGroupCyl();

                        $set_lcyl = false;

                        if (((float)$prescriptions->lcyl) > 2) {

                            if(($prescriptions->lcyl == (int) $prescriptions->lcyl) && ((int) $prescriptions->lcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->lcyl;
                                $lcyl = '+' . str_replace('+', '', (string) $lcyl);

                            }else {

                                foreach ($Group as $item) {
                                    if (!$set_lcyl && (((float)$prescriptions->lcyl) < (float)$item)) {

                                        $lcyl = '+' . str_replace('+', '', (string) $item);
                                        $set_lcyl = true;

                                    }
                                }

                            }

                        } elseif (((float)$prescriptions->lcyl) < -2) {


                            if(($prescriptions->lcyl == (int) $prescriptions->lcyl) && ((int) $prescriptions->lcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->lcyl;
                                $lcyl = '-' . str_replace('-', '', (string) $lcyl);

                            }else {

                                foreach ($Group as $item) {

                                    $item = ((int)$item) * -1;

                                    if (!$set_lcyl && (((float)$prescriptions->lcyl) > $item)) {

                                        $lcyl = '-' . str_replace('-', '', (string) $item);
                                        $set_lcyl = true;

                                    }
                                }

                            }


                        } else {


                            if (((float)$prescriptions->lcyl) > 0) {
                                $lcyl = '+2';
                            }else{
                                $lcyl = "-2";
                            }

                        }


                        /* join kardan lsph va lcyl baraye search */

                        if ($lcyl) {
                            $lsph = $lsph . ' ' . $lcyl;
                        }

                    }


                }


                // daryaft mahsolat

                $product = [];
                $productID = [];
                $productSPH = [];
                if($fav_product == 'yes'){


                    if($prescriptions->rcount > 0){

                        $productListRight = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                            ->join('favorites', 'favorites.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('optical_glass_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $rsph);
                            })
                            ->where('products.type', 2)
                            ->where('products.status', 'active')
                            ->where('favorites.user_id', $UserLogin->id)
                            ->where('favorites.status', 'active')
                            ->where('optical_glass_prices.price', '>' , 0)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','optical_glass_prices.sph','optical_glass_prices.id as price_id','optical_glass_prices.original_price','optical_glass_prices.price')
                            ->get();

                    }
                    if($prescriptions->lcount > 0){

                        $productListLeft = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                            ->join('favorites', 'favorites.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('optical_glass_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $lsph);
                            })
                            ->where('products.type', 2)
                            ->where('products.status', 'active')
                            ->where('favorites.user_id', $UserLogin->id)
                            ->where('favorites.status', 'active')
                            ->where('optical_glass_prices.price', '>' , 0)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','optical_glass_prices.sph','optical_glass_prices.id as price_id','optical_glass_prices.original_price','optical_glass_prices.price')
                            ->get();

                    }



                }
                else{


                    if($prescriptions->rcount > 0){


                        $productListRight = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('optical_glass_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $rsph);
                            })
                            ->where('products.type', 2)
                            ->where('products.status', 'active')
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','optical_glass_prices.sph','optical_glass_prices.id as price_id','optical_glass_prices.original_price','optical_glass_prices.price')
                            ->get();

                    }
                    if($prescriptions->lcount > 0){


                        $productListLeft = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                            ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                            ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                            ->where($whereFillter)
                            ->where('optical_glass_prices.inventory', '>' , 0)
                            ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                                $query->where('sph', $lsph);
                            })
                            ->where('products.type', 2)
                            ->where('products.status', 'active')
                            ->where('optical_glass_prices.price', '>' , 0)
                            ->orderBy('products.created_at', 'desc')
                            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','optical_glass_prices.sph','optical_glass_prices.id as price_id','optical_glass_prices.original_price','optical_glass_prices.price')
                            ->get();

                    }



                }


                break;
        }


        /*get statue sale left or right*/
        $producRightSt = false;
        $producLeftSt = false;

        if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){

            $producRightSt = true;
            $producLeftSt = true;

        }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){

            $producRightSt = true;
            $producLeftSt = false;

        }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){

            $producRightSt = false;
            $producLeftSt = true;

        }

        /*get product list right*/
        if($producRightSt && $productListRight) {
            foreach ($productListRight as $pr) {

                if($pr['price'] > 0){
                    $product[$pr['id']]['right'] = [
                        'id' => $pr['id'],
                        'sku' => $pr['sku'],
                        'image' => $pr['image'],
                        'brand_id' => $pr['brand_id'],
                        'name' => $pr['name'],
                        'sph' => $pr['sph'],
                        'original_price' => $pr['original_price'],
                        'price' => $pr['price'],
                    ];
                }

            }
        }

        /*get product list left*/
        if($producLeftSt && $productListLeft) {
            foreach ($productListLeft as $pr) {

                if($pr['price'] > 0) {
                    $product[$pr['id']]['left'] = [
                        'id' => $pr['id'],
                        'sku' => $pr['sku'],
                        'image' => $pr['image'],
                        'brand_id' => $pr['brand_id'],
                        'name' => $pr['name'],
                        'sph' => $pr['sph'],
                        'original_price' => $pr['original_price'],
                        'price' => $pr['price'],
                    ];
                }

            }
        }

        /* hazf mahsolati ke chap va rast nist */
        if($producRightSt && $producLeftSt){
            if($product) {
                foreach ($product as $key => $pr) {
                    if(!isset($pr['right']) || !isset($pr['left'])) {
                        unset($product[$key]);
                    }
                }
            }
        }


        /* sum price */
        $productList = [];
        if($product) {
            foreach ($product as $key => $pr) {

                if(isset($pr['right'])) {
                    $pr['right']["original_price"] = $pr['right']["original_price"] * $prescriptions->rcount;
                    $pr['right']["price"] = $pr['right']["price"] * $prescriptions->rcount;
                }

                if(isset($pr['left'])) {
                    $pr['left']["original_price"] = $pr['left']["original_price"] * $prescriptions->lcount;
                    $pr['left']["price"] = $pr['left']["price"] * $prescriptions->lcount;
                }

                $productList[$key] = $pr;
            }
        }



        /* sink to one price */
        $product = [];
        if($productList) {
            foreach ($productList as $key => $pr) {

                $newproduct = [];
                $original_price = 0;
                $price = 0;

                if(isset($pr['right'])) {
                    $newproduct = $pr['right'];

                    $original_price = $pr['right']["original_price"];
                    $price = $pr['right']["price"];
                }

                if(isset($pr['left'])) {
                    $newproduct = $pr['left'];

                    $original_price += $pr['left']["original_price"];
                    $price += $pr['left']["price"];
                }



                $newproduct["original_price"] = $original_price;
                $newproduct["price"] = $price;
                $product[$key] = $newproduct;

            }
        }




        $brandArray = [];
        $max_price = 0;
        if($product){
            foreach ($product as $pro){
                $brandArray[] = $pro['brand_id'];
                if($max_price < $pro['price']){
                    $max_price = $pro['price'];
                }
            }
        }


        switch ($status) {
            case "lens" :

                $filters = array(
                    [
                        'type' => 'select',
                        'name' => 'country',
                        'title' => 'کشور سازنده',
                        'value' => country::orderBy('title', 'ASC')->get()
                    ],
                    [
                        'type' => 'select',
                        'name' => 'brand',
                        'title' => 'برند',
                        'value' => Brand::whereIn('id' , $brandArray)->orderBy('name', 'desc')->get()
                    ],
                    [
                        'type' => 'select',
                        'name' => 'diagonal',
                        'title' => 'قطر لنز',
                        'value' => $this->LensProductDiagonals()
                    ],
                    [
                        'type' => 'select',
                        'name' => 'curvature',
                        'title' => 'انحنا لنز',
                        'value' => $this->LensProductCurvatures()
                    ],
                    [
                        'type' => 'select',
                        'name' => 'structure',
                        'title' => 'ساختار',
                        'value' => $this->LensProductStructures()
                    ],
                    [
                        'type' => 'select',
                        'name' => 'consumption_period',
                        'title' => 'دوره مصرف',
                        'value' => $this->LensProductConsumptionPeriod()
                    ],
                    [
                        'type' => 'select',
                        'name' => 'number',
                        'title' => 'موجودی در هر بسته',
                        'value' => $this->LensProductNumber()
                    ],
                    [
                        'type' => 'number',
                        'name' => 'thickness',
                        'title' => 'ضخامت مرکزی',
                        'value' => 100
                    ],
                    [
                        'type' => 'number',
                        'name' => 'abatement',
                        'title' => 'اب رسانی',
                        'value' => 100
                    ],
                    [
                        'type' => 'number',
                        'name' => 'oxygen_supply',
                        'title' => 'اکسیژن رسانی',
                        'value' => 100
                    ],
                    [
                        'type' => 'number',
                        'name' => 'price',
                        'title' => 'قیمت',
                        'value' => $max_price
                    ]
                );

                break;
            case "optical" :

                if($prescriptions->dodid == 1){

                    $filters = array(
                        [
                            'type' => 'select',
                            'name' => 'country',
                            'title' => 'کشور سازنده',
                            'value' => country::orderBy('title', 'ASC')->get()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'brand',
                            'title' => 'برند',
                            'value' => Brand::whereIn('id' , $brandArray)->orderBy('name', 'desc')->get()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'size',
                            'title' => 'سایز',
                            'value' => $this->OpticalGlassProductSize()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'refractive_index',
                            'title' => 'ضریب شکست',
                            'value' => $this->OpticalGlassProductLightBreakdown()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'anti_reflex_color',
                            'title' => 'رنگ انتی رفلکس',
                            'value' => $this->OpticalGlassProductAntiReflexColors()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'block',
                            'title' => 'بلوکات',
                            'value' => $this->OpticalGlassProductBlocks()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'bloc_troll',
                            'title' => 'بلوکنترول',
                            'value' => $this->OpticalGlassProductBlocTrolls()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'photocrophy',
                            'title' => 'فتوکروم',
                            'value' => $this->OpticalGlassProductPhotocrophys()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'photo_color',
                            'title' => 'رنگ فتو',
                            'value' => $this->OpticalGlassProductPhoto_colors()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'polycarbonate',
                            'title' => 'پلی کربنات',
                            'value' => $this->OpticalGlassProductPolycarbonates()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'poly_break',
                            'title' => 'نشکن',
                            'value' => $this->OpticalGlassProductPolycarbonates()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'color_white',
                            'title' => 'سفید قابل رنگ',
                            'value' => $this->OpticalGlassProductColorWhites()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'colored_score',
                            'title' => 'رنگی طبی',
                            'value' => $this->OpticalGlassProductColored_scores()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'watering',
                            'title' => 'آب گریزی',
                            'value' => $this->OpticalGlassProductWaterings()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'structure',
                            'title' => 'ساختار',
                            'value' => $this->OpticalGlassProductStructures()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'yu_vie',
                            'title' => 'یو وی',
                            'value' => $this->OpticalGlassProductYuVies()
                        ],
                        [
                            'type' => 'number',
                            'name' => 'price',
                            'title' => 'قیمت',
                            'value' => $max_price
                        ]
                    );

                }
                else{

                    $filters = array(
                        [
                            'type' => 'select',
                            'name' => 'country',
                            'title' => 'کشور سازنده',
                            'value' => country::orderBy('title', 'ASC')->get()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'brand',
                            'title' => 'برند',
                            'value' => Brand::whereIn('id' , $brandArray)->orderBy('name', 'desc')->get()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'size',
                            'title' => 'سایز',
                            'value' => $this->OpticalGlassProductSize()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'refractive_index',
                            'title' => 'ضریب شکست',
                            'value' => $this->OpticalGlassProductLightBreakdown()
                        ],
                        [
                            'type' => 'select',
                            'name' => 'yu_vie',
                            'title' => 'یو وی',
                            'value' => $this->OpticalGlassProductYuVies()
                        ],
                        [
                            'type' => 'number',
                            'name' => 'price',
                            'title' => 'قیمت',
                            'value' => $max_price
                        ]
                    );

                }

                break;
        }


        $favoriteList = array();
        if($product){
            foreach ($product as $pro){
                $requestFavorite = Favorite::where('product_id' , $pro['id'])->where('user_id' , $UserLogin->id)->first();
                if($requestFavorite){
                    $favoriteList[$pro['id']] = $requestFavorite->status;
                }else{
                    $favoriteList[$pro['id']] = 'inactive';
                }
            }
        }


        $followUpProduct = 0;
        $orderKey = $this->request->get('follow-up');
        if($orderKey){
            $request_follow = Order::where('order_key', $orderKey)->where('user_id', $UserLogin->id)->orderBy('id', 'desc')->first();
            if (!$request_follow) return redirect('order/new')->with('error', 'اطلاعات ارسال شده اشتباه است.');

            $lock = OrderController::get_optien($request_follow->id, 'products_box_op_lock');
            if($lock == 'closed'){
                $followUpProduct = $request_follow->product_id;
            }
        }


        //return view('site/order/ajax_products' , ['tabKey' => $status, 'filterSages' => $filterSages , 'filters' => $filters, 'FillterAcrive' => $FillterAcrive, 'product' => $product,  'followUpProduct' => $followUpProduct, 'favoriteList' => $favoriteList, 'activeFillter' => $activeFillter])->render();

        if ($this->request->ajax()) {
            return view('site/order/ajax_products' , ['tabKey' => $status, 'filterSages' => $filterSages , 'filters' => $filters, 'FillterAcrive' => $FillterAcrive, 'product' => $product,  'followUpProduct' => $followUpProduct, 'favoriteList' => $favoriteList, 'activeFillter' => $activeFillter])->render();
        }

        return redirect('order/new');
    }

    /*order next proses*/

    public function AddToFav(){

        $product_id = $this->request->get('product_id');
        $detail_id = $this->request->get('detail_id');
        if(!$product_id && !is_numeric($product_id)){
            return 'false';
        }

        $request = Product::where('id' , $product_id)->first();
        if(!$request) return 'false';

        if($request->type == 1){
            $detailRequest = lensPrice::where('product_id' , $product_id)->orderBy('id', 'desc')->first();
        }else{
            $detailRequest = optical_glassPrice::where('product_id' , $product_id)->orderBy('id', 'desc')->first();
        }
        if(!$detailRequest) return 'false';

        $UserLogin = Auth::user();

        $event = $this->request->event;
        switch ($event){
            case 'add':

                $requestFavorite = Favorite::where('product_id' , $product_id)->where('user_id' , $UserLogin->id)->first();
                if($requestFavorite){
                    $requestFavorite->status = 'active';
                    $requestFavorite->save();
                }else{
                    $newFavorite = new Favorite;
                    $newFavorite->product_id = $product_id;
                    $newFavorite->detail_id = 0;
                    $newFavorite->user_id = $UserLogin->id;
                    $newFavorite->status = 'active';
                    $newFavorite->save();
                }
                return 'true';

                break;
            case 'remove':

                $requestFavorite = Favorite::where('product_id' , $product_id)->where('user_id' , $UserLogin->id)->first();
                if($requestFavorite){
                    $requestFavorite->status = 'inactive';
                    $requestFavorite->save();
                    return 'true';
                }
                return 'false';

                break;
            default:
                return 'false';
                break;
        }

    }

    public function AddToCard(){
        $product_id = $this->request->product_id;
        $orderKey = $this->request->get('order_key');
        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }
        if (!$this->request->ajax()) {
            return redirect('order/new');
        }


        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);


        //check Product validate
        $requestProduct = Product::where('id' , $product_id)->orderBy('id', 'desc')->first();
        if(!$requestProduct) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        $prescriptions = Prescription::where('id' , $request->prescription_id)->orderBy('id', 'desc')->first();
        if(!$prescriptions) return response()->json([
            'staus' => 'error',
            'error' => 'نسخه این سفارش یافت نشد.'
        ]);

        $status = '';
        if($prescriptions->type_product){
            $status = ($requestProduct->type == 1) ? 'lens' : 'optical';
        }

        switch ($status){
            case "lens" :


                $rsph = '';
                $rcyl = '';
                $lsph = '';
                $lcyl = '';

                if($prescriptions->rcount != 'no_select' && $prescriptions->rcount > 0) {

                    /* daryaft sph right */

                    $Group = $this->LensProductGroupSph();
                    $set_rsph = false;

                    if (((float)$prescriptions->rsph) > 2) {

                        foreach ($Group as $item) {
                            if (!$set_rsph && (((float)$prescriptions->rsph) < (float)$item)) {

                                $nsph = str_replace('+', '', (string) ($item - 1));
                                $rsph = '+' . $nsph . ' +' . str_replace('+', '', (string) $item);
                                $set_rsph = true;

                            }
                        }

                    } elseif (((float)$prescriptions->rsph) < -2) {

                        foreach ($Group as $item) {

                            $item = ((int)$item) * -1;

                            if (!$set_rsph && (((float)$prescriptions->rsph) > $item)) {

                                $nsph = str_replace('-', '', (string) ($item + 1));
                                $rsph = '-' . $nsph . ' -' . str_replace('-', '', (string) $item);
                                $set_rsph = true;

                            }
                        }


                    } else {

                        if (((float)$prescriptions->rsph) > 0) {
                            $rsph = '0 +1';
                        }else{
                            $rsph = '0 -1';
                        }

                    }



                    /* daryaft rcyl right */

                    if ($prescriptions->rcyl != 'no_select') {
                        if (((float)$prescriptions->rcyl) != 0) {


                            $rcyl = $prescriptions->rcyl;


                        } else {

                            $rcyl = "-2";

                        }
                    }

                }

                if($prescriptions->lcount != 'no_select' && $prescriptions->lcount > 0) {

                    /* daryaft sph left */

                    $Group = $this->LensProductGroupSph();
                    $set_lsph = false;

                    if (((float)$prescriptions->lsph) > 2) {

                        foreach ($Group as $item) {
                            if (!$set_lsph && (((float)$prescriptions->lsph) < (float)$item)) {

                                $nsph = str_replace('+', '', (string) ($item - 1));
                                $lsph = '+' . $nsph . ' +' . str_replace('+', '', (string) $item);
                                $set_lsph = true;

                            }
                        }

                    } elseif (((float)$prescriptions->lsph) < -2) {


                        foreach ($Group as $item) {
                            if (!$set_lsph && (((float)$prescriptions->lsph) < (float)$item)) {

                                $nsph = str_replace('-', '', (string) ($item + 1));
                                $lsph = '-' . $nsph . ' -' . str_replace('-', '', (string) $item);
                                $set_lsph = true;

                            }
                        }


                    } else {

                        if (((float)$prescriptions->lsph) > 0) {
                            $lsph = '0 +1';
                        }else{
                            $lsph = '0 -1';
                        }
                    }




                    /* daryaft lcyl right */

                    if ($prescriptions->lcyl != 'no_select') {

                        if (((float)$prescriptions->lcyl) != 0) {


                            $lcyl = $prescriptions->lcyl;


                        } else {

                            $lcyl = "-2";

                        }
                    }


                }

                $product = [];
                $productID = [];
                $productSPH = [];



                if($prescriptions->rcount > 0){

                    $productListRight = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                        ->where('lens_prices.inventory', '>' , 0)
                        ->where(function ($query) use ($prescriptions, $rcyl, $lcyl) {
                            if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){
                                $query->where('cyl', $rcyl)->orWhere('cyl', $lcyl)->orWhere('cyl', null);
                            }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){
                                $query->where('cyl', $rcyl)->orWhere('cyl', null);
                            }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){
                                $query->where('cyl', $lcyl)->orWhere('cyl', null);
                            }
                        })
                        ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                            $query->where('sph', $rsph);
                        })
                        ->where('products.type', 1)
                        ->where('products.status', 'active')
                        ->where('products.id', $product_id)
                        ->where('lens_prices.price', '>' , 0)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.brand_id','brands.name','lens_prices.sph','lens_prices.id as price_id','lens_prices.sale_price','lens_prices.original_price','lens_prices.price')
                        ->get();

                }
                if($prescriptions->lcount > 0){


                    $productListLeft = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                        ->where('lens_prices.inventory', '>' , 0)
                        ->where(function ($query) use ($prescriptions, $rcyl, $lcyl) {
                            if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){
                                $query->where('cyl', $rcyl)->orWhere('cyl', $lcyl)->orWhere('cyl', null);
                            }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){
                                $query->where('cyl', $rcyl)->orWhere('cyl', null);
                            }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){
                                $query->where('cyl', $lcyl)->orWhere('cyl', null);
                            }
                        })
                        ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                            $query->where('sph', $lsph);
                        })
                        ->where('products.type', 1)
                        ->where('products.status', 'active')
                        ->where('products.id', $product_id)
                        ->where('lens_prices.price', '>' , 0)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.brand_id','brands.name','lens_prices.sph','lens_prices.id as price_id','lens_prices.sale_price','lens_prices.original_price','lens_prices.price')
                        ->get();

                }




                break;
            case "optical" :



                $rsph = '';
                $rcyl = '';
                $lsph = '';
                $lcyl = '';



                if($prescriptions->rcount != 'no_select' && $prescriptions->rcount > 0) {

                    /* daryaft sph right */

                    $Group = $this->OpticalGlassProductGroupSph();
                    $set_rsph = false;

                    if (((float)$prescriptions->rsph) > 2) {

                        if(($prescriptions->rsph == (int) $prescriptions->rsph) && ((int) $prescriptions->rsph) % 2 == 0){

                            $sph = (int) $prescriptions->rsph;
                            $rsph = '+' . str_replace('+', '', (string) $sph);

                        }else {

                            foreach ($Group as $item) {
                                if (!$set_rsph && (((float)$prescriptions->rsph) < (float)$item)) {

                                    $rsph = '+' . str_replace('+', '', $item);
                                    $set_rsph = true;

                                }
                            }
                        }

                    } elseif (((float)$prescriptions->rsph) < -2) {

                        if(($prescriptions->rsph == (int) $prescriptions->rsph) && ((int) $prescriptions->rsph) % 2 == 0){

                            $sph = (int) $prescriptions->rsph;
                            $rsph = '-' . str_replace('-', '', (string) $sph);

                        }else{

                            foreach ($Group as $item) {

                                $item = ((int)$item) * -1;

                                if (!$set_rsph && (((float)$prescriptions->rsph) > $item)) {

                                    $rsph = '-' . str_replace('-', '', $item);
                                    $set_rsph = true;

                                }
                            }

                        }


                    } else {

                        if (((float)$prescriptions->rsph) > 0) {
                            $rsph = '+2';
                        }else{
                            $rsph = '-2';
                        }

                    }



                    /* daryaft rcyl right */

                    if ($prescriptions->rcyl != 'no_select') {

                        $Group = $this->OpticalGlassProductGroupCyl();

                        $set_rcyl = false;

                        if (((float)$prescriptions->rcyl) > 2) {

                            if(($prescriptions->rcyl == (int) $prescriptions->rcyl) && ((int) $prescriptions->rcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->rcyl;
                                $rcyl = '+' . str_replace('+', '', (string) $cyl);

                            }else {

                                foreach ($Group as $item) {
                                    if (!$set_rcyl && (((float)$prescriptions->rcyl) < (float)$item)) {

                                        $rcyl = '+' . str_replace('+', '', (string) $item);
                                        $set_rcyl = true;

                                    }
                                }

                            }

                        } elseif (((float)$prescriptions->rcyl) < -2) {


                            if(($prescriptions->rcyl == (int) $prescriptions->rcyl) && ((int) $prescriptions->rcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->rcyl;
                                $rcyl = '-' . str_replace('-', '', (string) $cyl);

                            }else {

                                foreach ($Group as $item) {

                                    $item = ((int)$item) * -1;

                                    if (!$set_rcyl && (((float)$prescriptions->rcyl) > $item)) {

                                        $rcyl = '-' . str_replace('-', '', (string) $item);
                                        $set_rcyl = true;

                                    }
                                }

                            }


                        } else {


                            if (((float)$prescriptions->rcyl) > 0) {
                                $rcyl = '+2';
                            }else{
                                $rcyl = '-2';
                            }

                        }


                        /* join kardan rsph va rcyl baraye search */

                        if ($rcyl) {
                            $rsph = $rsph . ' ' . $rcyl;
                        }

                    }


                }

                if($prescriptions->lcount != 'no_select' && $prescriptions->lcount > 0) {

                    /* daryaft sph right */

                    $Group = $this->OpticalGlassProductGroupSph();
                    $set_lsph = false;

                    if (((float)$prescriptions->lsph) > 2) {

                        if(($prescriptions->lsph == (int) $prescriptions->lsph) && ((int) $prescriptions->lsph) % 2 == 0){

                            $sph = (int) $prescriptions->lsph;
                            $lsph = '+' . str_replace('+', '', (string) $sph);

                        }else {
                            foreach ($Group as $item) {
                                if (!$set_lsph && (((float)$prescriptions->lsph) < (float)$item)) {

                                    $lsph = '+' . str_replace('+', '', (string) $item);
                                    $set_lsph = true;

                                }
                            }
                        }

                    } elseif (((float)$prescriptions->lsph) < -2) {

                        if(($prescriptions->lsph == (int) $prescriptions->lsph) && ((int) $prescriptions->lsph) % 2 == 0){

                            $sph = (int) $prescriptions->lsph;
                            $lsph = '-' . str_replace('-', '', (string) $sph);

                        }else{

                            foreach ($Group as $item) {

                                $item = ((int)$item) * -1;

                                if (!$set_lsph && (((float)$prescriptions->lsph) > $item)) {

                                    $lsph = '-' . str_replace('-', '', (string) $item);
                                    $set_lsph = true;

                                }
                            }

                        }


                    } else {

                        if (((float)$prescriptions->lsph) > 0) {
                            $lsph = '+2';
                        }else{
                            $lsph = '-2';
                        }

                    }



                    /* daryaft lcyl right */

                    if ($prescriptions->lcyl != 'no_select') {

                        $Group = $this->OpticalGlassProductGroupCyl();

                        $set_lcyl = false;

                        if (((float)$prescriptions->lcyl) > 2) {

                            if(($prescriptions->lcyl == (int) $prescriptions->lcyl) && ((int) $prescriptions->lcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->lcyl;
                                $lcyl = '+' . str_replace('+', '', (string) $lcyl);

                            }else {

                                foreach ($Group as $item) {
                                    if (!$set_lcyl && (((float)$prescriptions->lcyl) < (float)$item)) {

                                        $lcyl = '+' . str_replace('+', '', (string) $item);
                                        $set_lcyl = true;

                                    }
                                }

                            }

                        } elseif (((float)$prescriptions->lcyl) < -2) {


                            if(($prescriptions->lcyl == (int) $prescriptions->lcyl) && ((int) $prescriptions->lcyl) % 2 == 0){

                                $cyl = (int) $prescriptions->lcyl;
                                $lcyl = '-' . str_replace('-', '', (string) $lcyl);

                            }else {

                                foreach ($Group as $item) {

                                    $item = ((int)$item) * -1;

                                    if (!$set_lcyl && (((float)$prescriptions->lcyl) > $item)) {

                                        $lcyl = '-' . str_replace('-', '', (string) $item);
                                        $set_lcyl = true;

                                    }
                                }

                            }


                        } else {


                            if (((float)$prescriptions->lcyl) > 0) {
                                $lcyl = '+2';
                            }else{
                                $lcyl = "-2";
                            }

                        }


                        /* join kardan lsph va lcyl baraye search */

                        if ($lcyl) {
                            $lsph = $lsph . ' ' . $lcyl;
                        }

                    }


                }

                $product = [];
                $productID = [];
                $productSPH = [];

                if($prescriptions->rcount > 0){


                    $productListRight = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                        ->where('optical_glass_prices.inventory', '>' , 0)
                        ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                            $query->where('sph', $rsph);
                        })
                        ->where('products.type', 2)
                        ->where('products.status', 'active')
                        ->where('products.id', $product_id)
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.image','products.brand_id','brands.name','optical_glass_prices.sph','optical_glass_prices.id as price_id','optical_glass_prices.sale_price','optical_glass_prices.original_price','optical_glass_prices.price')
                        ->get();

                }
                if($prescriptions->lcount > 0){


                    $productListLeft = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                        ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                        ->where('optical_glass_prices.inventory', '>' , 0)
                        ->where(function ($query) use ($prescriptions, $rsph, $lsph) {
                            $query->where('sph', $lsph);
                        })
                        ->where('products.type', 2)
                        ->where('products.status', 'active')
                        ->where('products.id', $product_id)
                        ->where('optical_glass_prices.price', '>' , 0)
                        ->orderBy('products.created_at', 'desc')
                        ->select(
                            'products.id',
                            'products.sku',
                            'products.image',
                            'products.brand_id',
                            'brands.name',
                            'optical_glass_prices.sph',
                            'optical_glass_prices.id as price_id',
                            'optical_glass_prices.sale_price',
                            'optical_glass_prices.original_price',
                            'optical_glass_prices.price')
                        ->get();

                }





                break;
        }


        $producRightSt = false;
        $producLeftSt = false;

        if($prescriptions->rcount > 0 && $prescriptions->lcount > 0){

            $producRightSt = true;
            $producLeftSt = true;

        }else if($prescriptions->rcount > 0 && $prescriptions->lcount == 0){

            $producRightSt = true;
            $producLeftSt = false;

        }else if($prescriptions->lcount > 0 && $prescriptions->rcount == 0){

            $producRightSt = false;
            $producLeftSt = true;

        }

        /*get product list right*/
        if($producRightSt && $productListRight) {
            foreach ($productListRight as $pr) {

                if($pr['price'] > 0){
                    $product[$pr['id']]['right'] = [
                        'id' => $pr['id'],
                        'sku' => $pr['sku'],
                        'image' => $pr['image'],
                        'brand_id' => $pr['brand_id'],
                        'name' => $pr['name'],
                        'sph' => $pr['sph'],
                        'original_price' => $pr['original_price'],
                        'price' => $pr['price'],
                        'sale_price' => $pr['sale_price'],
                        'price_id' => $pr['price_id'],
                    ];
                }

            }
        }

        /*get product list left*/
        if($producLeftSt && $productListLeft) {
            foreach ($productListLeft as $pr) {

                if($pr['price'] > 0) {
                    $product[$pr['id']]['left'] = [
                        'id' => $pr['id'],
                        'sku' => $pr['sku'],
                        'image' => $pr['image'],
                        'brand_id' => $pr['brand_id'],
                        'name' => $pr['name'],
                        'sph' => $pr['sph'],
                        'original_price' => $pr['original_price'],
                        'price' => $pr['price'],
                        'sale_price' => $pr['sale_price'],
                        'price_id' => $pr['price_id'],
                    ];
                }

            }
        }

        /* hazf mahsolati ke chap va rast nist */
        if($producRightSt && $producLeftSt){
            if($product) {
                foreach ($product as $key => $pr) {
                    if(!isset($pr['right']) || !isset($pr['left'])) {
                        unset($product[$key]);
                    }
                }
            }
        }


        /* sum price */
        $productList = [];
        if($product) {
            foreach ($product as $key => $pr) {

                if(isset($pr['right'])) {
                    $pr['right']["original_price"] = $pr['right']["original_price"] * $prescriptions->rcount;
                    $pr['right']["price"] = $pr['right']["price"] * $prescriptions->rcount;
                    $pr['right']["sale_price"] = $pr['right']["sale_price"] * $prescriptions->rcount;
                }

                if(isset($pr['left'])) {
                    $pr['left']["original_price"] = $pr['left']["original_price"] * $prescriptions->lcount;
                    $pr['left']["price"] = $pr['left']["price"] * $prescriptions->lcount;
                    $pr['left']["sale_price"] = $pr['left']["sale_price"] * $prescriptions->lcount;
                }

                $productList[$key] = $pr;
            }
        }



        /* sink to one price */
        $product = [];
        if($productList) {
            foreach ($productList as $key => $pr) {

                $newproduct = [];
                $original_price = 0;
                $price = 0;
                $sale_price = 0;

                if(isset($pr['right'])) {
                    $newproduct = $pr['right'];

                    $original_price = $pr['right']["original_price"];
                    $price = $pr['right']["price"];
                    $sale_price = $pr['right']["sale_price"];
                }

                if(isset($pr['left'])) {
                    $newproduct = $pr['left'];

                    $original_price += $pr['left']["original_price"];
                    $price += $pr['left']["price"];
                    $sale_price += $pr['left']["sale_price"];
                }



                $newproduct["original_price"] = $original_price;
                $newproduct["price"] = $price;
                $newproduct["sale_price"] = $sale_price;
                $product[$key] = $newproduct;

            }
        }




        $price = 0;
        $sale_price = 0;
        $product_lists = [];
        if($product && isset($product[$product_id])){
            $price = $product[$product_id]["price"];
            $sale_price = $product[$product_id]["sale_price"];
            $product_lists[] = $product[$product_id]["price_id"];
        }else{
            return response()->json([
                'staus' => 'error',
                'error' => 'اطلاعات ارسال شده اشتباه است.'
            ]);
        }

        $amount_shop = $price - $sale_price;
        $amount_shop = ($amount_shop > 0) ? $amount_shop : 0;


        $request->product_id = $product_id;
        $request->detail_id = json_encode($product_lists);
        $request->price = $price;

        $request->paid_amount_shop = $amount_shop;
        $request->paid_amount_bonakdar = $sale_price;

        $request->save();

        return response()->json([
            'staus' => 'success',
        ]);

    }

    public function NewOrderEndStep(){

        $seller = User::where('role', 'seller')
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->paginate(36);

        $getboxday = array();
        $getboxtime = array();
        $sendboxday = array();
        $sendboxtime = array();

        $blankDay = 2;

        for ($i = 1 ; $i < 10 ; $i++){
            $getboxday[] = date('Y/m/d', strtotime('+'.$i.' day'));

            $nextDay = $blankDay + $i;
            $sendboxday[] = date('Y/m/d', strtotime('+'.$nextDay.' day'));
        }
        for ($i = 8 ; $i < 19 ; $i++){
            $getboxtime[] = $i . ":00";
            $sendboxtime[] = $i . ":00";
        }


        return view('site/order/new_end_step' , ['seller' => $seller, 'getboxday' => $getboxday, 'getboxtime' => $getboxtime, 'sendboxday' => $sendboxday, 'sendboxtime' => $sendboxtime]);
    }

    public function ActionNewOrderEndStep(){

        return redirect('order/new/invoice');

    }

    /*Order Prescription*/

    public function ActionNewPrescription(){
        $status = $this->request->status;

        $UserLogin = Auth::user();
        $PrescriptionID = 0;

        switch ($status){
            case "type" :

                $data = $this->request->get('json');
                $edit = $this->request->get('edit');
                if(!$data){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'لطفا اطلاعات را وارد کنید.'
                    ]);
                }

                $data = json_decode($data);

                if(!$data->name){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'لطفا اطلاعات بیمار را وارد کنید.'
                    ]);
                }

                if($data->rcount == 'no_select' && $data->lcount == 'no_select'){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'تعداد را در نسخه وارد کنید.'
                    ]);
                }

                if($data->rcount <= 0 && $data->lcount <= 0){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'تعداد نباید کمتر از یک باشد.'
                    ]);
                }

                if($data->rcount > 0){

                    if($data->rsph == 'no_select'){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'sph سمت راست را در نسخه وارد کنید.'
                        ]);
                    }

                    /*if($data->raxis == 'no_select'){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'axis سمت راست را در نسخه وارد کنید.'
                        ]);
                    }*/

                }

                if($data->lcount > 0){

                    if($data->lsph == 'no_select'){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'sph سمت چپ را در نسخه وارد کنید.'
                        ]);
                    }

                    /*if($data->laxis == 'no_select'){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'axis سمت چپ را در نسخه وارد کنید.'
                        ]);
                    }*/

                }

                $editSearchValidate = Prescription::where('id', $edit)->where('type', 'import')->first();
                if($edit && !$editSearchValidate) return response()->json([
                    'staus' => 'error',
                    'error' => 'ویرایش امکان پذیر نیست.'
                ]);

                if($edit && $editSearchValidate){
                    $newPrescription = $editSearchValidate;
                }else{
                    $newPrescription = new Prescription;
                    $newPrescription->user_id = $UserLogin->id;
                    $newPrescription->type = 'import';
                }

                $newPrescription->name = $data->name;
                $newPrescription->birth = $data->bir;
                $newPrescription->type_product = $data->product_type;


                $newPrescription->dodid = ($data->two_eyes) ? 1 : 0;
                $newPrescription->rcount = ($data->rcount == 'no_select') ? 0 : $data->rcount;
                $newPrescription->lcount = ($data->lcount == 'no_select') ? 0 : $data->lcount;
                $newPrescription->rsph = $data->rsph;
                $newPrescription->lsph = $data->lsph;
                $newPrescription->rcyl = $data->rcyl;
                $newPrescription->lcyl = $data->lcyl;
                $newPrescription->raxis = $data->raxis;
                $newPrescription->laxis = $data->laxis;
                $newPrescription->radd = isset($data->radd) ? $data->radd : "";
                $newPrescription->ladd = isset($data->ladd) ? $data->ladd : "";

                $newPrescription->dodid_rdia = isset($data->rdia) ? $data->rdia : "";
                $newPrescription->dodid_ldia = isset($data->ldia) ? $data->ldia : "";
                $newPrescription->dodid_ripd = isset($data->ripd) ? $data->ripd : "";
                $newPrescription->dodid_lipd = isset($data->lipd) ? $data->lipd : "";
                $newPrescription->dodid_rprism = isset($data->rprism) ? $data->rprism : "";
                $newPrescription->dodid_lprism = isset($data->lprism) ? $data->lprism : "";
                $newPrescription->dodid_rprism_base = isset($data->rprism_base) ? $data->rprism_base : "";
                $newPrescription->dodid_lprism_base = isset($data->lprism_base) ? $data->lprism_base : "";
                $newPrescription->dodid_rcorridor = isset($data->rcorridor) ? $data->rcorridor : "";
                $newPrescription->dodid_lcorridor = isset($data->lcorridor) ? $data->lcorridor : "";
                $newPrescription->dodid_rdec = isset($data->rdec) ? $data->rdec : "";
                $newPrescription->dodid_ldec = isset($data->ldec) ? $data->ldec : "";

                $newPrescription->prisma = ($data->prisma) ? 1 : 0;
                $newPrescription->prisma_rprisma1 = isset($data->rprisma1) ? $data->rprisma1 : "";
                $newPrescription->prisma_lprisma1 = isset($data->lprisma1) ? $data->lprisma1 : "";
                $newPrescription->prisma_rdegrees1 = isset($data->rdegrees1) ? $data->rdegrees1 : "";
                $newPrescription->prisma_ldegrees1 = isset($data->ldegrees1) ? $data->ldegrees1 : "";
                $newPrescription->prisma_rprisma2 = isset($data->rprisma2) ? $data->rprisma2 : "";
                $newPrescription->prisma_lprisma2 = isset($data->lprisma2) ? $data->lprisma2 : "";
                $newPrescription->prisma_rdegrees2 = isset($data->rdegrees2) ? $data->rdegrees2 : "";
                $newPrescription->prisma_ldegrees2 = isset($data->ldegrees2) ? $data->ldegrees2 : "";



                if($newPrescription->save()){
                    $PrescriptionID = $newPrescription->id;

                    $newOrder = new Order;
                    $newOrder->user_id = $UserLogin->id;
                    $newOrder->prescription_id = $PrescriptionID;
                    $newOrder->order_key = str_random(10);

                    if($data->lathe){
                        $newOrder->lathe = json_encode(['rpd' => $data->rpd, 'rheight' => $data->rheight, 'lpd' => $data->lpd, 'lheight' => $data->lheight]);
                    }

                    $newOrder->save();

                    return response()->json([
                        'staus' => 'success',
                        'order_key' => $newOrder->order_key
                    ]);
                }

                return response()->json([
                    'staus' => 'error',
                    'error' => 'خطا ، ذخیره سازی با مشکل روبه رو شد.'
                ]);

                break;
            case "image" :

                $data = $this->request->get('json');
                $edit = $this->request->get('edit');
                if(!$data){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'لطفا اطلاعات را وارد کنید.'
                    ]);
                }

                $data = json_decode($data);
                if(!$data->name || !$data->bir || !$data->images){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'لطفا یکی از نسخه ها را انتخاب کنید.'
                    ]);
                }

                $images = json_encode($data->images);

                $editSearchValidate = Prescription::where('id', $edit)->where('type', 'image')->first();
                if($edit && !$editSearchValidate) return response()->json([
                    'staus' => 'error',
                    'error' => 'ویرایش امکان پذیر نیست.'
                ]);

                if($edit && $editSearchValidate){
                    $newPrescription = $editSearchValidate;
                }else{
                    $newPrescription = new Prescription;
                    $newPrescription->user_id = $UserLogin->id;
                    $newPrescription->type = 'image';
                }

                $newPrescription->name = $data->name;
                $newPrescription->birth = $data->bir;
                $newPrescription->type_product = $data->product_type_image;
                $newPrescription->image = $images;

                if($newPrescription->save()){
                    $PrescriptionID = $newPrescription->id;

                    $newOrder = new Order;
                    $newOrder->user_id = $UserLogin->id;
                    $newOrder->prescription_id = $PrescriptionID;
                    $newOrder->order_key = str_random(10);
                    $newOrder->save();

                    return response()->json([
                        'staus' => 'success',
                        'order_key' => $newOrder->order_key
                    ]);
                }

                return response()->json([
                    'staus' => 'error',
                    'error' => 'خطا ، ذخیره سازی با مشکل روبه رو شد.'
                ]);

                break;
            case "archive" :

                $data = $this->request->get('json');
                if(!$data){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'لطفا اطلاعات را وارد کنید.'
                    ]);
                }

                $data = json_decode($data);
                if(!@$data->prescription_archive){
                    return response()->json([
                        'staus' => 'error',
                        'error' => 'لطفا یکی از نسخه ها را انتخاب کنید.'
                    ]);
                }

                $UserLogin = Auth::user();
                $prescriptions = Prescription::where('user_id', $UserLogin->id)->where('id', $data->prescription_archive)->first();
                if(!$prescriptions) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

                $orderKey = 0;
                $request_follow = array();

                if(@$data->follow_up){
                    $orderKey = $data->follow_up;
                    $request_follow = Order::where('order_key', $orderKey)->where('user_id', $UserLogin->id)->orderBy('id', 'desc')->first();
                    if (!$request_follow) return redirect('order/new')->with('error', 'اطلاعات ارسال شده اشتباه است.');
                }

                $PrescriptionID = $prescriptions->id;

                $newOrder = new Order;
                $newOrder->user_id = $UserLogin->id;
                $newOrder->prescription_id = $PrescriptionID;
                if($request_follow) {
                    $newOrder->lathe_id = $request_follow->lathe_id;

                    $lock = OrderController::get_optien($request_follow->id, 'products_box_op_lock');
                    if($lock == 'closed') {
                        $newOrder->product_id = $request_follow->product_id;
                    }

                    $lock = OrderController::get_optien($request_follow->id, 'data_box_op_lock');
                    if($lock == 'closed'){
                        $newOrder->get_box_date = $request_follow->get_box_date;
                        $newOrder->get_box_time = $request_follow->get_box_time;
                        $newOrder->send_box_date = $request_follow->send_box_date;
                        $newOrder->send_box_time = $request_follow->send_box_time;
                    }
                }
                $newOrder->order_key = str_random(10);
                $newOrder->save();

                if($request_follow){
                    OrderController::update_optien($newOrder->id, 'follow_up', $orderKey);
                }

                return response()->json([
                    'staus' => 'success',
                    'order_key' => $newOrder->order_key
                ]);

                break;
        }

        return response()->json([
            'staus' => 'error',
            'error' => 'خطا ، اطلاعات شما مورد قبول نمی باشد لطفا مجددا بررسی کنید.'
        ]);

    }

    public function ActionPrescriptionRefresh(){

        $UserLogin = Auth::user();
        $prescriptions = Prescription::where('user_id', $UserLogin->id)->orderBy('created_at', 'desc')->get();

        return view('site/order/refresh_prescription' , ['prescriptions' => $prescriptions]);

    }



    public function NewOrderGetSeller(){

        $orderKey = $this->request->get('order_key');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        $seller = User::where('role', 'amel')
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->paginate(800);


        return view('site/order/ajax_seller' , ['seller' => $seller]);
    }

    public function ActionNewOrderSeller(){

        $orderKey = $this->request->get('order_key');
        $select_key = $this->request->get('select_key');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        if(!is_numeric($select_key)){
            $select_key = 0;
        }

        $type_product = 'lens';
        $prescriptions = prescription::where('id', $request->product_id)->orderBy('created_at', 'desc')->first();
        if($prescriptions) $type_product = $prescriptions->type_product;

        $request->lathe_id = $select_key;
        $request->save();

        return response()->json([
            'type_product' => $type_product,
            'staus' => 'success',
        ]);
    }

    public function NewOrderGetService(){

        $orderKey = $this->request->get('order_key');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);



        $labrator = User::join('labrator_services', 'labrator_services.user_id' , '=' , 'users.id')->where('users.role', 'labrator')
            ->where('users.status', 'active')
            ->select('users.*', 'labrator_services.id as services_id', 'labrator_services.title', 'labrator_services.price')
            ->orderBy('users.name', 'ASC')
            ->paginate(18000);


        return view('site/order/ajax_service' , ['labrator' => $labrator]);
    }

    public function ActionNewOrderService(){

        $orderKey = $this->request->get('order_key');
        $select_key = $this->request->get('select_key');
        $json = $this->request->get('json');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey || !$json){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $data = json_decode($json);

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);



        $order_count = 0;

        $Prescription = Prescription::where('id' , $request->prescription_id)->first();
        if(!$Prescription) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        $order_count = (int) $Prescription->rcount;
        $order_count += (int) $Prescription->lcount;

        $labrator = 0;
        $sale_price = 0;
        $full_price = 0;


        $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%')->get();
        if($order_service){
            foreach ($order_service as $service) {
                $service->delete();
            }
        }

        if($data){
            foreach ($data as $key => $v){

                $service = labratorService::where('id', $v)->first();
                if($service){
                    $this->update_optien($request->id, 'order_service_' . $key , $v);
                    $this->update_optien($request->id, 'order_service_' . $key . '_price' , $service->price);
                    $this->update_optien($request->id, 'order_service_' . $key . '_title' , $service->title);
                    $this->update_optien($request->id, 'order_service_' . $key . '_labrator' , $service->user_id);
                    $sale_price += $service->sale_price;
                    $full_price += $service->price;
                    $labrator = $service->user_id;
                }
            }
        }

        $this->update_optien($request->id, 'order_service_amount_labrator' , ( $sale_price * $order_count ) );

        $amount_shop = $full_price - $sale_price;
        $amount_shop = ($amount_shop > 0) ? $amount_shop : 0;
        //$order_service_amount_shop = ( $amount_shop * $order_count );
        $order_service_amount_shop = ( $amount_shop );

        //$this->update_optien($request->id, 'order_service_amount_shop' , ( $amount_shop * $order_count ) );
        $this->update_optien($request->id, 'order_service_amount_shop' , ( $amount_shop ) );
        $this->update_optien($request->id, 'order_count' , $order_count );


        $request->labrator_id = $labrator;
        //$request->paid_amount_labrator = ( $sale_price * $order_count );
        $request->paid_amount_labrator = ( $sale_price );
        $request->paid_amount_shop = $order_service_amount_shop + $request->paid_amount_shop;
        $request->save();


        return response()->json([
            'staus' => 'success',
        ]);
    }


    public function ActionNewOrderLathe(){
        $data = $this->request->get('json');
        $orderKey = $this->request->get('order_key');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);


        $UserLogin = Auth::user();

        if(!$data){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $data = json_decode($data);



        if(!$data->lathe){
            return response()->json([
                'staus' => 'success',
            ]);
        }

        if(!$data->rpd || !$data->rheight || !$data->lpd || !$data->lheight){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }


        $request->lathe = json_encode(['rpd' => $data->rpd, 'rheight' => $data->rheight, 'lpd' => $data->lpd, 'lheight' => $data->lheight]);
        if($request->save()){

            return response()->json([
                'staus' => 'success',
            ]);

        }

        return response()->json([
            'staus' => 'error',
            'error' => 'مشکلی در ذخیره سازی پیش آمده است.'
        ]);

    }


    public function OldVNewOrderDate(){
        $orderKey = $this->request->get('order_key');

        /*if (!$this->request->ajax()) {
            return redirect('order/new');
        }*/

        if(!$orderKey){
            return 'لطفا اطلاعات را وارد کنید.';
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return 'اطلاعات ارسال شده اشتباه است.';


        $UserLogin = Auth::user();

        $timeList = ['getboxday' => [] , 'sendboxday' => [] , 'getboxtime' => [] , 'sendboxtime' => [] , 'passage' => '' , 'city' => '' , 'getcalender' => [] , 'sendcalender' => []];

        if($UserLogin->city != 'تهران'){
            $timeList['city'] = 'on';
        }elseif($UserLogin->passage == 'on'){
            $timeList['passage'] = 'on';
        }else{


            $user_area = $UserLogin->area;

            if($user_area){

                $without_shaving = 0;
                $with_shaving = 0;
                $difference = 0;

                $dev = DeliverAreas::where('area' , $user_area)->first();
                if($dev){
                    $delivery_normal = DeliveryNormal::where('id' , $dev->delivery_id)->first();
                    $without_shaving = $delivery_normal->without_shaving;
                    $with_shaving = $delivery_normal->with_shaving;
                    $difference = $delivery_normal->difference;
                    $timeList['sendboxday']['setting'] = $delivery_normal;
                }

                if($request->lathe_id > 0){
                    $hour = tr_num(jdate("H")) + $with_shaving;

                    $timeList['sendboxday']['receipt']['date'] = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '>=' , date('Y-m-d'))->orderBy('receipt_date', 'ASC')->get();

                    $dateReceipt = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , date('Y-m-d'))->count();
                    if($dateReceipt){
                        $timeList['sendboxday']['receipt']['time'] = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , date('Y-m-d'))->where('receipt_time' , '>=' , $hour)->orderBy('receipt_time', 'ASC')->get();
                    }else{
                        $receipt_date  = $timeList['sendboxday']['receipt']['date'][0]['receipt_date'];

                        $timeList['sendboxday']['receipt']['time'] = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , $receipt_date)->orderBy('receipt_time', 'ASC')->get();
                    }


                    $timeList['sendboxday']['get']['date'] = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('receipt_date' , '>=' , date('Y-m-d'))->orderBy('receipt_date', 'ASC')->get();

                    $dateReceipt = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('receipt_date' , date('Y-m-d'))->count();
                    if($dateReceipt){
                        $timeList['sendboxday']['receipt']['time'] = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , date('Y-m-d'))->where('receipt_time' , '>=' , $hour)->orderBy('receipt_time', 'ASC')->get();
                    }else{
                        $receipt_date  = $timeList['sendboxday']['receipt']['date'][0]['receipt_date'];

                        $timeList['sendboxday']['receipt']['time'] = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , $receipt_date)->orderBy('receipt_time', 'ASC')->get();
                    }


                    $timeList['sendboxday']['get'] = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->get();

                }else{

                    $timeList['sendboxday']['get'] = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->get();
                }

            }

        }


        return view('site/order/ajax_date', ['timeList' => $timeList]);

    }

    public function NewOrderDate(){
        $orderKey = $this->request->get('order_key');

        /*if (!$this->request->ajax()) {
            return redirect('order/new');
        }*/

        if(!$orderKey){
            return 'لطفا اطلاعات را وارد کنید.';
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return 'اطلاعات ارسال شده اشتباه است.';

        $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%%price%')->first();



        $UserLogin = Auth::user();

        $timeList = ['getboxday' => [] , 'sendboxday' => [] , 'getboxtime' => [] , 'sendboxtime' => [] , 'passage' => '' , 'city' => '' , 'getcalender' => [] , 'sendcalender' => []];

        if($UserLogin->city != 'تهران'){
            $timeList['city'] = 'on';
        }elseif($UserLogin->passage == 'on'){
            $timeList['passage'] = 'on';
        }else{

            $user_area = $UserLogin->area;

            if($user_area){

                //$timeList['sendboxday'] = true;

                $without_shaving = 0;
                $with_shaving = 0;
                $difference = 0;

                $dev = DeliverAreas::where('area' , $user_area)->first();
                if($dev){
                    $delivery_normal = DeliveryNormal::where('id' , $dev->delivery_id)->first();
                    $without_shaving = $delivery_normal->without_shaving;
                    $with_shaving = $delivery_normal->with_shaving;
                    $difference = $delivery_normal->difference;
                }

                $difference_delivery = 0;
                $difference_receive = 0;
                $capacity = 0;

                $dev = DeliveryCalenderArea::where('area' , $user_area)->first();
                if($dev){
                    $delivery_calender = DeliveryCalender::where('id' , $dev->delivery_id)->first();
                    $difference_delivery = $delivery_calender->difference_delivery;
                    $difference_receive = $delivery_calender->difference_receive;
                    $capacity = $delivery_calender->capacity;
                }

                if($request->lathe || $order_service){
                    $hour = tr_num(jdate("H"));

                    /* normal delivery */
                    $date = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '>=' , date('Y-m-d'))->orderBy('receipt_date', 'ASC')->get();

                    $datereceipt = [];
                    if($date){
                        foreach ($date as $k => $item) {

                            if(!in_array($item['receipt_date'], $datereceipt)){

                                if(date('Y-m-d', strtotime($item['receipt_date'])) == date('Y-m-d')){

                                    $countD = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , $item['receipt_date'])->where('receipt_time' , '>=' , $hour)->orderBy('receipt_time', 'ASC')->count();
                                    if($countD > 0){
                                        $datereceipt[] = $item['receipt_date'];
                                    }

                                }else{
                                    $datereceipt[] = $item['receipt_date'];
                                }

                            }

                        }
                    }

                    $timeList['sendboxday']['receipt']['date'] = $datereceipt;

                    $time = [];
                    $dateReceipt = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , date('Y-m-d'))->count();
                    if($dateReceipt){
                        $time = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , date('Y-m-d'))->where('receipt_time' , '>=' , $hour)->orderBy('receipt_time', 'ASC')->get();
                    }

                    if(count($time) <= 0){
                        $receipt_date = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '>', date('Y-m-d'))->first();
                        if($receipt_date){

                            $receipt_date = $receipt_date->receipt_date;

                            $time = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , $receipt_date)->orderBy('receipt_time', 'ASC')->get();


                        }
                    }

                    $timeList['sendboxday']['receipt']['time'] = $time;



                    $hour = tr_num(jdate("H")) + $with_shaving;

                    $date = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '>=' , date('Y-m-d'))->orderBy('with_date', 'ASC')->get();


                    $datereceipt = [];
                    if($date){
                        foreach ($date as $k => $item) {

                            if(!in_array($item['with_date'], $datereceipt)){

                                if(date('Y-m-d', strtotime($item['with_date'])) == date('Y-m-d')){

                                    $countD = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '=' , $item['with_date'])->where('with_time' , '>=' , $hour)->orderBy('with_time', 'ASC')->count();
                                    if($countD > 0){
                                        $datereceipt[] = $item['with_date'];
                                    }

                                }else{
                                    $datereceipt[] = $item['with_date'];
                                }

                            }
                        }
                    }

                    $timeList['sendboxday']['get']['date'] = $datereceipt;


                    $time = [];
                    $dateWith = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , date('Y-m-d'))->count();
                    if($dateWith){
                        $time = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '=' , date('Y-m-d'))->where('with_time' , '>=' , $hour)->orderBy('with_time', 'ASC')->get();
                    }

                    if(count($time) <= 0){
                        $receipt_date = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '>', date('Y-m-d'))->first();
                        if($receipt_date){

                            $receipt_date = $receipt_date->with_date;

                            $time = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '=' , $receipt_date)->orderBy('with_time', 'ASC')->get();


                        }
                    }

                    $timeList['sendboxday']['get']['time'] = $time;
                    $timeList['sendboxday']['lathe'] = true;



                    /* calender delivery */

                    $dateTime = Carbon::now()->addDay(2);
                    $dateTime = $dateTime->year . '-' . $dateTime->month . '-' . $dateTime->day;

                    $hour = tr_num(jdate("H")) + $difference_receive;
                    $date_receipt = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'receipt')->where('date' , '>=' , date('Y-m-d'))->where('date' , '<' , $dateTime)->where('occupation' , '<=' , $capacity)->where('start' , '>=' , $hour)->orderBy('date', 'ASC')->get();

                    $select_time = [];
                    $calender = [];
                    $hour_one = $hour;

                    if($date_receipt){
                        foreach ($date_receipt as $item) {

                            $select_time['start'] = $item['start'];
                            $select_time['end'] = $item['end'];

                            $select_date = [
                                'date' => $item['date'],
                                'time' => $select_time
                            ];

                            $select_date_get = [
                                'date' => '',
                                'time' => []
                            ];



                            $hour = $item['end'] + $difference_delivery;

                            $date_delivery = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , '>=' , $item['date'])->where('occupation' , '<=' , $capacity)->where('start' , '>=' , $hour)->orderBy('date', 'ASC')->first();

                            if(!$date_delivery){

                                $dateTime = Carbon::parse($item['date'])->addDay(1);
                                $dateTime = $dateTime->year . '-' . $dateTime->month . '-' . $dateTime->day;

                                $date_delivery = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , '>=' , $dateTime)->where('occupation' , '<=' , $capacity)->orderBy('date', 'ASC')->first();

                            }

                            $select_time = [];
                            if($date_delivery){

                                $select_time['start'] = $date_delivery->start;
                                $select_time['end'] = $date_delivery->end;

                                $select_date_get = [
                                    'date' => $date_delivery->date,
                                    'time' => $select_time
                                ];
                            }

                            if($select_date['date'] && $select_date_get['date']){
                                $calender[] = [
                                    'receipt' => $select_date,
                                    'get' => $select_date_get
                                ];
                            }

                        }
                    }


                    $timeList['sendcalender']['date_time'] = $calender;
                    $timeList['sendcalender']['lathe'] = true;


                }
                else{

                    $hour = tr_num(jdate("H")) + $with_shaving;
                    $date = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '>=' , date('Y-m-d'))->orderBy('without_date', 'ASC')->get();

                    $datereceipt = [];
                    if($date){
                        foreach ($date as $k => $item) {
                            if(!in_array($item['without_date'], $datereceipt)){

                                if(date('Y-m-d', strtotime($item['without_date'])) == date('Y-m-d')){

                                    $countD = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '=' , $item['without_date'])->where('without_time' , '>=' , $hour)->orderBy('without_time', 'ASC')->count();
                                    if($countD > 0){
                                        $datereceipt[] = $item['without_date'];
                                    }

                                }else{
                                    $datereceipt[] = $item['without_date'];
                                }

                            }
                        }
                    }

                    $timeList['sendboxday']['get']['date'] = $datereceipt;

                    $time = [];
                    $datewithout = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , date('Y-m-d'))->count();
                    if($datewithout){
                        $time = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '=' , date('Y-m-d'))->where('without_time' , '>=' , $hour)->orderBy('without_time', 'ASC')->get();
                    }

                    if(count($time) <= 0){
                        $receipt_date = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '>', date('Y-m-d'))->first();
                        if($receipt_date){

                            $receipt_date = $receipt_date->without_date;

                            $time = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '=' , $receipt_date)->orderBy('without_time', 'ASC')->get();


                        }
                    }

                    $timeList['sendboxday']['get']['time'] = $time;
                    $timeList['sendboxday']['lathe'] = false;





                    /* calender delivery */

                    /*$hour = tr_num(jdate("H")) + $difference_delivery;
                    $date = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , '>=' , date('Y-m-d'))->where('occupation' , '<=' , $capacity)->where('start' , '>=' , $hour)->orderBy('date', 'ASC')->first();

                    $select_date = "";
                    $select_time = [];
                    if($date){
                        $select_date = $date->date;
                        $select_time['start'] = $date->start;
                        $select_time['end'] = $date->end;
                    }


                    $timeList['sendcalender']['get']['date'] = $select_date;*/


                    $dateTime = Carbon::now()->addDay(2);
                    $dateTime = $dateTime->year . '-' . $dateTime->month . '-' . $dateTime->day;

                    $hour = tr_num(jdate("H")) + $difference_delivery;
                    $date_receipt = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , '>=' , date('Y-m-d'))->where('date' , '<' , $dateTime)->where('occupation' , '<=' , $capacity)->orderBy('date', 'ASC')->get();
                    //$date_receipt = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , '>=' , date('Y-m-d'))->where('date' , '<' , $dateTime)->where('occupation' , '<=' , $capacity)->where('start' , '>=' , $hour)->orderBy('date', 'ASC')->get();

                    $select_time = [];
                    $calender = [];

                    if($date_receipt){
                        foreach ($date_receipt as $item) {

                            if(date('Y-m-d', strtotime($item['date'])) == date('Y-m-d')){

                                if($item['start'] >= $hour){

                                    $select_time['start'] = $item['start'];
                                    $select_time['end'] = $item['end'];

                                    $select_date = [
                                        'date' => $item['date'],
                                        'time' => $select_time
                                    ];
                                    if($select_date['date']){
                                        $calender[] = [
                                            'get' => $select_date,
                                        ];
                                    }

                                }

                            }else{

                                $select_time['start'] = $item['start'];
                                $select_time['end'] = $item['end'];

                                $select_date = [
                                    'date' => $item['date'],
                                    'time' => $select_time
                                ];
                                if($select_date['date']){
                                    $calender[] = [
                                        'get' => $select_date,
                                    ];
                                }
                            }

                        }
                    }


                    $timeList['sendcalender']['date_time'] = $calender;
                    $timeList['sendcalender']['lathe'] = false;


                }

            }

        }


        return view('site/order/ajax_date', ['timeList' => $timeList]);

    }

    public function delivery_normal_receipt_time(){
        $receipt_date = $this->request->get('date');

        $orderKey = $this->request->get('order_key');

        $UserLogin = Auth::user();

        if(!$orderKey){
            return 'لطفا اطلاعات را وارد کنید.';
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return 'اطلاعات ارسال شده اشتباه است.';

        $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%%price%')->first();

        $option ='';
        $user_area = $UserLogin->area;

        if($user_area){

            $without_shaving = 0;
            $with_shaving = 0;
            $difference = 0;

            $dev = DeliverAreas::where('area' , $user_area)->first();
            if($dev){
                $delivery_normal = DeliveryNormal::where('id' , $dev->delivery_id)->first();
                $without_shaving = $delivery_normal->without_shaving;
                $with_shaving = $delivery_normal->with_shaving;
                $difference = $delivery_normal->difference;
            }


            if(!$receipt_date){
                $receipt_date = date('Y-m-d');
            }

            $time = [];
            if($request->lathe || $order_service){

                $hour = tr_num(jdate("H"));


                if(date('Y-m-d', strtotime($receipt_date)) == date('Y-m-d')) {
                    $datereceipt = DeliverAreas::where('area', $user_area)->where('type', 'receipt')->where('receipt_date', $receipt_date)->count();
                    if ($datereceipt) {
                        $time = DeliverAreas::where('area', $user_area)->where('type', 'receipt')->where('receipt_date', '=', $receipt_date)->where('receipt_time', '>', $hour)->orderBy('receipt_time', 'ASC')->get();
                    }
                }else{

                    $time = DeliverAreas::where('area' , $user_area)->where('type' , 'receipt')->where('receipt_date' , '=' , $receipt_date)->orderBy('receipt_time', 'ASC')->get();

                }

            }


            $option .= '<option value="">ساعت را انتخاب کنید</option>';
            if($time){
                foreach ($time as $item) {
                    $option .= '<option value="'.$item['receipt_time'].'">'.$item['receipt_time'] . ':00 - ' . $item['receipt_difference_time'] . ':00</option>';
                }
            }


        }

        return $option;

    }

    public function delivery_normal_get_date(){
        $receipt_date = $this->request->get('receipt_date');
        $receipt_time = $this->request->get('receipt_time');

        $orderKey = $this->request->get('order_key');

        $UserLogin = Auth::user();

        if(!$orderKey){
            return 'لطفا اطلاعات را وارد کنید.';
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return 'اطلاعات ارسال شده اشتباه است.';

        $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%%price%')->first();


        $option ='';
        $user_area = $UserLogin->area;

        if($user_area){

            $without_shaving = 0;
            $with_shaving = 0;
            $difference = 0;

            $dev = DeliverAreas::where('area' , $user_area)->first();
            if($dev){
                $delivery_normal = DeliveryNormal::where('id' , $dev->delivery_id)->first();
                $without_shaving = $delivery_normal->without_shaving;
                $with_shaving = $delivery_normal->with_shaving;
                $difference = $delivery_normal->difference;
            }

            if(!$receipt_date){
                $receipt_date = date('Y-m-d');
            }

            if($request->lathe || $order_service){

                $hour = tr_num(jdate("H")) + $with_shaving;
                if($receipt_time){
                    $hour = tr_num($receipt_time) + $with_shaving;
                }

                $date = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '>=' , $receipt_date)->orderBy('with_date', 'ASC')->get();

                $datereceipt = [];
                if($date){
                    foreach ($date as $k => $item) {

                        if(!in_array($item['with_date'], $datereceipt)){

                            if(date('Y-m-d', strtotime($item['with_date'])) == date('Y-m-d')){

                                $countD = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '=' , $item['with_date'])->where('with_time' , '>=' , $hour)->orderBy('with_time', 'ASC')->count();
                                if($countD > 0){
                                    $datereceipt[] = $item['with_date'];
                                }

                            }else{
                                $datereceipt[] = $item['with_date'];
                            }

                        }
                    }
                }

                $date = $datereceipt;

            }else{

                $hour = tr_num(jdate("H")) + $with_shaving;
                if($receipt_time){
                    $hour = tr_num($receipt_time) + $with_shaving;
                }

                $date = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '>=' , $receipt_date)->orderBy('without_date', 'ASC')->get();

                $datereceipt = [];
                if($date){
                    foreach ($date as $k => $item) {
                        if(!in_array($item['without_date'], $datereceipt)){

                            if(date('Y-m-d', strtotime($item['without_date'])) == date('Y-m-d')){

                                $countD = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '=' , $item['without_date'])->where('without_time' , '>=' , $hour)->orderBy('without_time', 'ASC')->count();
                                if($countD > 0){
                                    $datereceipt[] = $item['without_date'];
                                }

                            }else{
                                $datereceipt[] = $item['without_date'];
                            }

                        }
                    }
                }

                $date = $datereceipt;

            }

            $option .= '<option value="">تاریخ را انتخاب کنید</option>';
            if($date){
                foreach ($date as $item) {
                    $option .= '<option value="'.$item.'">'.tr_num(jdate('Y/m/d', strtotime($item))).'</option>';
                }
            }


        }

        return $option;

    }

    public function delivery_normal_get_time(){
        $receipt_date = $this->request->get('receipt_date');
        $receipt_time = $this->request->get('receipt_time');
        $get_date = $this->request->get('get_date');

        $orderKey = $this->request->get('order_key');

        $UserLogin = Auth::user();

        if(!$orderKey && !$receipt_time && !$receipt_date){
            return 'لطفا اطلاعات را وارد کنید.';
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return 'اطلاعات ارسال شده اشتباه است.';

        $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%%price%')->first();



        $option ='';
        $user_area = $UserLogin->area;

        if($user_area){

            $without_shaving = 0;
            $with_shaving = 0;
            $difference = 0;

            $dev = DeliverAreas::where('area' , $user_area)->first();
            if($dev){
                $delivery_normal = DeliveryNormal::where('id' , $dev->delivery_id)->first();
                $without_shaving = $delivery_normal->without_shaving;
                $with_shaving = $delivery_normal->with_shaving;
                $difference = $delivery_normal->difference;
            }

            if(!$get_date){
                $get_date = date('Y-m-d');
            }

            $time = [];
            if($request->lathe || $order_service){


                $hour = tr_num(jdate("H")) + $with_shaving;
                if($receipt_time){

                    if(date('Y-m-d', strtotime($get_date)) == date('Y-m-d', strtotime($receipt_date))) {
                        $datereceipt = DeliverAreas::where('area', $user_area)->where('type', 'receipt')->where('receipt_date', $receipt_date)->where('receipt_time', $receipt_time)->first();
                        if ($datereceipt) {
                            $hour = tr_num($datereceipt->receipt_difference_time) + $with_shaving;
                        }
                    }

                }

                if(date('Y-m-d', strtotime($get_date)) == date('Y-m-d', strtotime($receipt_date))) {

                    $dateWith = DeliverAreas::where('area', $user_area)->where('type', 'with')->where('with_date', $get_date)->count();
                    if ($dateWith) {
                        $time = DeliverAreas::where('area', $user_area)->where('type', 'with')->where('with_date', '=', $get_date)->where('with_time', '>=', $hour)->orderBy('with_time', 'ASC')->get();
                    }

                }else{

                    $time = DeliverAreas::where('area' , $user_area)->where('type' , 'with')->where('with_date' , '=' , $get_date)->orderBy('with_time', 'ASC')->get();

                }

            }else{

                $hour = tr_num(jdate("H")) + $with_shaving;
                if($receipt_time){
                    $hour = tr_num($receipt_time) + $with_shaving;
                }


                if(date('Y-m-d', strtotime($get_date)) == date('Y-m-d')) {
                    $datewithout = DeliverAreas::where('area', $user_area)->where('type', 'without')->where('without_date', $get_date)->count();
                    if ($datewithout) {
                        $time = DeliverAreas::where('area', $user_area)->where('type', 'without')->where('without_date', '=', $get_date)->where('without_time', '>=', $hour)->orderBy('without_time', 'ASC')->get();
                    }
                }else{

                    $time = DeliverAreas::where('area' , $user_area)->where('type' , 'without')->where('without_date' , '=' , $get_date)->orderBy('without_time', 'ASC')->get();

                }


            }

            $option .= '<option value="">ساعت را انتخاب کنید</option>';
            if($time){
                foreach ($time as $item) {
                    $time  = ($item['with_time']) ? $item['with_time'] : $item['without_time'];
                    $difference  = ($item['with_difference_time']) ? $item['with_difference_time'] : $item['without_difference_time'];
                    $price = ($item['with_price']) ? $item['with_price'] : $item['without_price'];
                    $price_lb = "";
                    if($price){
                        $str_price = str_replace('+', '' , $price);
                        $str_price = str_replace('-', '' , $str_price);

                        $price_lb = ($price > 0) ? ' ( ' . $str_price . '% افزایش قیمت )' : ' ( ' . $str_price . '% کاهش قیمت )';
                    }

                    $option .= '<option value="'.$time.'">'.$time . ':00 - ' . $difference . ':00 ' . $price_lb . '</option>';
                }
            }


        }

        return $option;

    }

    public function ActionNewOrderSetDate(){
        $orderKey = $this->request->get('order_key');
        $data = $this->request->get('json');
        $type_shipping = $this->request->get('type_shipping');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $UserLogin = Auth::user();
        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%%price%')->first();

        $user_area = $UserLogin->area;

        if($UserLogin->city != 'تهران'){
            $request->type_shipping = 'theCity';
        }elseif($UserLogin->passage == 'on'){
            $request->type_shipping = 'inPerson';
        }else{
            switch ($type_shipping){
                case 'theCalendar':

                    if(!$data){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'لطفا اطلاعات را وارد کنید.'
                        ]);
                    }
                    $data = json_decode($data);


                    $difference_delivery = 0;
                    $difference_receive = 0;
                    $capacity = 0;

                    $dev = DeliveryCalenderArea::where('area' , $user_area)->first();
                    if($dev){
                        $delivery_calender = DeliveryCalender::where('id' , $dev->delivery_id)->first();
                        $difference_delivery = $delivery_calender->difference_delivery;
                        $difference_receive = $delivery_calender->difference_receive;
                        $capacity = $delivery_calender->capacity;
                    }

                    if($request->lathe || $order_service){
                        if((!isset($data->calender_get_time) || !isset($data->calender_get_date)) && (empty($data->calender_get_time) || empty($data->calender_get_date))){
                            return response()->json([
                                'staus' => 'error',
                                'error' => 'تاریخ و زمان دریافت فریم را مشخص کنید.'
                            ]);
                        }
                    }


                    if((!isset($data->calender_send_time) || !isset($data->calender_send_date)) && (empty($data->calender_send_time) || empty($data->calender_send_date))){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'تاریخ و زمان تحویل سفارش را مشخص کنید.'
                        ]);
                    }




                    if($request->lathe || $order_service){

                        $hour = tr_num(jdate("H")) + $difference_receive;
                        $date = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'receipt')->where('date' , $data->calender_get_date)->where('occupation' , '<=' , $capacity)->where('start' , $data->calender_get_time)->orderBy('date', 'ASC')->first();


                        $select_date = "";
                        $select_time_start = 0;
                        $select_time_end = 0;
                        $hour_one = $hour;

                        if($date){
                            $select_date = $date->date;
                            $hour_one = $date->end;
                            $select_time_start = $date->start;
                            $select_time_end = $date->end;
                        }

                        if(!$select_date || !$select_time_start){
                            return response()->json([
                                'staus' => 'error',
                                'error' => 'تاریخ و زمان دریافت فریم مشخص نیست.'
                            ]);
                        }


                        $request->get_box_date = $select_date;
                        $request->get_box_time = $select_time_start;

                        OrderController::update_optien($request->id, 'order_get_date' , $select_date);
                        OrderController::update_optien($request->id, 'order_get_start_time' , $select_time_start);
                        OrderController::update_optien($request->id, 'order_get_end_time' , $select_time_end);


                        $hour = $hour_one + $difference_delivery;
                        $date = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , $data->calender_send_date)->where('occupation' , '<=' , $capacity)->where('start' , $data->calender_send_time)->orderBy('date', 'ASC')->first();


                        $select_date = "";
                        $select_time_start = 0;
                        $select_time_end = 0;
                        if($date){
                            $select_date = $date->date;
                            $select_time_start = $date->start;
                            $select_time_end = $date->end;
                        }

                        if(!$select_date || !$select_time_start){
                            return response()->json([
                                'staus' => 'error',
                                'error' => 'تاریخ و زمان تحویل سفارش مشخص نیست.'
                            ]);
                        }

                        $request->send_box_date = $select_date;
                        $request->send_box_time = $select_time_start;

                        OrderController::update_optien($request->id, 'order_send_date' , $select_date);
                        OrderController::update_optien($request->id, 'order_send_start_time' , $select_time_start);
                        OrderController::update_optien($request->id, 'order_send_end_time' , $select_time_end);
                        OrderController::update_optien($request->id, 'order_price' , 0);


                    }else{

                        $hour = tr_num(jdate("H")) + $difference_delivery;
                        $date = DeliveryCalenderArea::where('area' , $user_area)->where('type' , 'delivery')->where('date' , '>=' , date('Y-m-d'))->where('occupation' , '<=' , $capacity)->where('start' , '>=' , $hour)->orderBy('date', 'ASC')->first();

                        $select_date = "";
                        $select_time_start = 0;
                        $select_time_end = 0;
                        if($date){
                            $select_date = $date->date;
                            $select_time_start = $date->start;
                            $select_time_end = $date->end;
                        }

                        if(!$select_date || !$select_time_start){
                            return response()->json([
                                'staus' => 'error',
                                'error' => 'تاریخ و زمان تحویل سفارش مشخص نیست.'
                            ]);
                        }

                        $request->send_box_date = $select_date;
                        $request->send_box_time = $select_time_start;

                        OrderController::update_optien($request->id, 'order_send_date' , $select_date);
                        OrderController::update_optien($request->id, 'order_send_start_time' , $select_time_start);
                        OrderController::update_optien($request->id, 'order_send_end_time' , $select_time_end);
                        OrderController::update_optien($request->id, 'order_price' , 0);

                    }


                    break;
                case 'inPerson':
                    $request->type_shipping = 'inPerson';
                    break;
                case 'theNormal':

                    if(!$data){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'لطفا اطلاعات را وارد کنید.'
                        ]);
                    }
                    $data = json_decode($data);

                    if($request->lathe || $order_service){
                        if((!isset($data->get_time) || !isset($data->get_date)) && (empty($data->get_time) || empty($data->get_date))){
                            return response()->json([
                                'staus' => 'error',
                                'error' => 'تاریخ و زمان دریافت فریم را مشخص کنید.'
                            ]);
                        }
                    }


                    if((!isset($data->send_time) || !isset($data->send_date)) && (empty($data->send_time) || empty($data->send_date))){
                        return response()->json([
                            'staus' => 'error',
                            'error' => 'تاریخ و زمان تحویل سفارش را مشخص کنید.'
                        ]);
                    }


                    $request->type_shipping = 'theNormal';


                    $difference = 0;
                    $dev = DeliverAreas::where('area' , $user_area)->first();
                    if($dev){
                        $delivery_normal = DeliveryNormal::where('id' , $dev->delivery_id)->first();
                        $difference = $delivery_normal->difference;
                    }




                    if($request->lathe || $order_service){

                        $request->get_box_date = $data->get_date;
                        $request->get_box_time = $data->get_time;

                        $difference_time = $data->get_time + $difference;

                        OrderController::update_optien($request->id, 'order_get_date' , $data->get_date);
                        OrderController::update_optien($request->id, 'order_get_start_time' , $data->get_time);
                        OrderController::update_optien($request->id, 'order_get_end_time' , $difference_time);

                    }

                    $request->send_box_date = $data->send_date;
                    $request->send_box_time = $data->send_time;

                    $id = 0;
                    $price = 0;
                    $without_price = 0;

                    if($request->lathe || $order_service) {

                        $dateW = DeliverAreas::where('area', $user_area)->where('type', 'with')->where('with_date', $data->send_date)->where('with_time', $data->send_time)->first();
                        if ($dateW) {
                            $id = $dateW->id;
                            $price = $dateW->with_price;
                            $without_price = $dateW->with_price;
                        }

                    }else{

                        $dateW = DeliverAreas::where('area', $user_area)->where('type', 'without')->where('without_date', $data->send_date)->where('without_time', $data->send_time)->first();
                        if ($dateW) {
                            $id = $dateW->id;
                            $price = $dateW->without_price;
                            $without_price = $dateW->without_price;
                        }

                    }

                    $difference_time = $data->send_time + $difference;

                    OrderController::update_optien($request->id, 'order_send_date' , $data->send_date);
                    OrderController::update_optien($request->id, 'order_send_start_time' , $data->send_time);
                    OrderController::update_optien($request->id, 'order_send_end_time' , $difference_time);
                    OrderController::update_optien($request->id, 'order_price' , ($price) ? $price : 0);


                    if($without_price){

                        if($request->lathe || $order_service) {
                            $amount_labrator = ($request->paid_amount_labrator * 10) / 100;
                            $amount_labrator_p = ($amount_labrator > 0) ? $amount_labrator : 0;
                            $amount_labrator = $request->paid_amount_labrator + $amount_labrator_p;

                            $request->paid_amount_labrator = $amount_labrator;
                        }



                        $paid_amount_shop  = $this->get_optien($request->id, 'order_service_amount_shop');
                        $amount_shop = ($paid_amount_shop * 10 ) / 100;
                        $amount_shop_p = ($amount_shop > 0) ? $amount_shop : 0;
                        $amount_shop = $request->paid_amount_shop + $amount_shop_p;

                        $request->paid_amount_shop = $amount_shop;

                        OrderController::update_optien($request->id, 'order_send_forse_labrator' , ($amount_labrator_p) ? $amount_labrator_p : 0);
                        //OrderController::update_optien($request->id, 'order_send_forse_shop' , ($amount_shop_p) ? $amount_shop_p : 0);
                        OrderController::update_optien($request->id, 'order_send_forse_shop' , 0);

                    }

                    break;
            }
        }



        if($request->save()){

            return response()->json([
                'staus' => 'success',
            ]);

        }

        return response()->json([
            'staus' => 'error',
            'error' => 'مشکلی در ذخیره سازی پیش آمده است.'
        ]);

    }


    public function ActionNewOrderSubmitFull(){
        $orderKey = $this->request->get('order_key');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);


        if(!$request->prescription_id){

            return response()->json([
                'staus' => 'error',
                'error' => 'نسخه وارد نشده است.'
            ]);

        }

        if(!$request->product_id){

            return response()->json([
                'staus' => 'error',
                'error' => 'محصول وارد نشده است.'
            ]);

        }

        if($request->status != 'not_completed'){

            return response()->json([
                'staus' => 'error',
                'error' => 'وضعیت سفارش مورد تایید نیست.'
            ]);

        }



        $request->status = 'pending_pay';
        $request->save();


        Session::put('coupon_set', '');
        Session::put('coupon_amount', 0);
        Session::put('coupon_key', '');

        return response()->json([
            'staus' => 'success',
            'link' => url('order/invoice')
        ]);

    }



    public function NewOrderInvoice(){

        $UserLogin = Auth::user();
        $order = array();
        $full_labrator_price = array();

        $request = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
            ->join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'pending_pay')
            ->where('orders.user_id' , $UserLogin->id)
            ->orderBy('orders.created_at', 'desc')
            ->select(
                'prescriptions.*',
                'products.type as product_type',
                'products.sku as product_sku',
                'orders.created_at as created',
                'orders.lathe_id as lathe_id',
                'orders.price as price',
                'orders.order_key as key',
                'orders.id as order_id'
            )->get();

        $fullCart = array();
        if($request){
            foreach ($request as $v){
                $total = $v['price'];
                $labrator_price = 0;

                $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                if($order_service){
                    foreach ($order_service as $service) {
                        $labrator_price = $labrator_price + $service['val'];
                        $total = $total + $service['val'];
                    }
                }

                $order_service = Order_detail::where('order_id', $v['order_id'])->where('key' ,'order_price')->first();
                if($order_service){

                    if($order_service->val){
                        $price = $labrator_price * $order_service->val / 100;
                        $total = $total + $price;
                    }

                }

                $fullCart[$v['order_id']] = $total;
                $full_labrator_price[$v['order_id']] = $labrator_price;
            }
        }

        $totalInvoice = 0;
        if($fullCart){
            foreach ($fullCart as $v){
                $totalInvoice = $totalInvoice + $v;
            }
        }


        $last_order_key = '';
        $order_key = Order::where('orders.status' , 'pending_pay')->where('orders.user_id' , $UserLogin->id)->orderBy('orders.created_at', 'desc')->select('order_key as key')->first();
        if($order_key){
            $last_order_key = $order_key->key;
        }

        return view('site/order/new_invoice', ['request' => $request, 'fullTotal' => $fullCart, 'full_labrator_price' => $full_labrator_price, 'last_order_key' => $last_order_key, 'totalInvoice' => $totalInvoice]);

    }




    public function OrderComplete(){
        $orderKey = $this->request->key;

        if(!$orderKey){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');

        if($request->status == 'pending_pay'){
            return redirect('order/invoice');
        }

        return '';

    }

    public function ActionNewOrderLock(){
        $orderKey = $this->request->get('order_key');
        $event = $this->request->get('event');

        if (!$this->request->ajax()) {
            return redirect('order/new');
        }

        if(!$orderKey){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return response()->json([
            'staus' => 'error',
            'error' => 'اطلاعات ارسال شده اشتباه است.'
        ]);

        $lock = OrderController::get_optien($request->id, $event.'_lock');

        if($lock == 'closed'){
            OrderController::update_optien($request->id, $event.'_lock', 'open');

            return response()->json([
                'staus' => 'success',
                'actien' => 'open',
                'message' => 'بخش مورد نظر برای سفارش بعدی آزاد شد',
            ]);
        }else{
            OrderController::update_optien($request->id, $event.'_lock', 'closed');

            return response()->json([
                'staus' => 'success',
                'actien' => 'closed',
                'message' => 'بخش مورد نظر برای سفارش بعدی قفل شد',
            ]);
        }

    }

    public function OrderDelete(){
        $orderKey = $this->request->key;

        if(!$orderKey){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }

        $request = Order::where('order_key' , $orderKey)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');

        $request->delete();

        Session::put('coupon_set', '');
        Session::put('coupon_amount', 0);
        Session::put('coupon_key', '');

        return back()->with('susess' , 'اطلاعات ارسال شده اشتباه است.');

    }


    public function OrderPayment(){
        $UserLogin = Auth::user();


        $request = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
            ->join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'pending_pay')
            ->where('orders.user_id' , $UserLogin->id)
            ->orderBy('orders.created_at', 'desc')
            ->select(
                'prescriptions.name as prescription_name',
                'prescriptions.type as prescription_type',
                'prescriptions.birth as prescription_birth',
                'products.type as product_type',
                'products.sku as product_sku',

                'orders.price as price',
                'orders.order_key as key',
                'orders.id as order_id'
            )->get();


        $totalInvoice = 0;
        if(count($request) > 0){
            foreach ($request as $v){

                $total = $v['price'];
                $labrator_price = 0;

                $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                if($order_service){
                    foreach ($order_service as $service) {
                        $labrator_price = $labrator_price + $service['val'];
                        $total = $total + $service['val'];
                    }
                }

                $order_service = Order_detail::where('order_id', $v['order_id'])->where('key' ,'order_price')->first();
                if($order_service){

                    if($order_service->val){
                        $price = $labrator_price * $order_service->val / 100;
                        $total = $total + $price;
                    }

                }


                $totalInvoice = $totalInvoice + $total;
                //$full_labrator_price[$v['order_id']] = $labrator_price;
            }
        }else{
            return redirect('/order/invoice');
        }

        $totalInvoice_old = $totalInvoice;
        if(Session::get('coupon_amount')){
            $totalInvoice = $totalInvoice - Session::get('coupon_amount');
        }


        $price = $UserLogin->credit - $totalInvoice;
        if($UserLogin->credit <= 0){
            $payment_price = $totalInvoice;
        }else{
            $payment_price = $totalInvoice - $UserLogin->credit;
            $payment_price = ($payment_price <= 0) ? 0 : $payment_price;
        }

        return view('site/order/payment', ['price' => $totalInvoice_old, 'UserLogin' => $UserLogin, 'payment_price' => $payment_price]);
    }

    public function ActionOrderPayment(){
        $UserLogin = Auth::user();

        $request = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
            ->join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'pending_pay')
            ->where('orders.user_id' , $UserLogin->id)
            ->select(
                'prescriptions.name as prescription_name',
                'prescriptions.type as prescription_type',
                'prescriptions.birth as prescription_birth',
                'prescriptions.rcount',
                'prescriptions.lcount',
                'products.type as product_type',
                'products.sku as product_sku',
                'orders.price as price',
                'orders.order_key as key',
                'orders.product_id as product_id',
                'orders.detail_id as product_detail_id',
                'orders.id as id'
            )->get();

        $products = [];
        $totalInvoice = 0;
        if(count($request) > 0){
            foreach ($request as $v){
                $total = $v['price'];
                $labrator_price = 0;

                if($v['product_detail_id']){
                    $detail_id = json_decode($v['product_detail_id']);
                    if($detail_id){
                        foreach ($detail_id as $detail){

                            $inventory = 0;
                            if($v['product_type'] == 1){

                                $lens = lensPrice::where('product_id', $v['product_id'])->where('id', $detail)->first();
                                if($lens){
                                    $inventory = $lens->inventory;
                                }

                            }else{

                                $optical = optical_glassPrice::where('product_id', $v['product_id'])->where('id', $detail)->first();
                                if($optical){
                                    $inventory = $optical->inventory;
                                }

                            }

                            if($inventory <= 0){
                                return redirect('/order/invoice')->with('error' , 'موجودی محصول ' . $v['product_sku'] . ' به پایان رسیده است.');
                            }

                        }
                    }
                }

                $products[] = [
                    'product_id' => $v['product_id'],
                    'product_detail_id' => $v['product_detail_id'],
                    'product_type' => ($v['product_type'] == 1) ? 'lenz' : 'optical_glass',
                ];

                $order_service = Order_detail::where('order_id', $v['id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                if($order_service){
                    foreach ($order_service as $service) {
                        $labrator_price = $labrator_price + $service['val'];
                        $total = $total + $service['val'];
                    }
                }


                $order_service = Order_detail::where('order_id', $v['id'])->where('key' ,'order_price')->first();
                if($order_service){

                    if($order_service->val){
                        $price = $labrator_price * $order_service->val / 100;
                        $total = $total + $price;
                    }

                }

                $totalInvoice = $totalInvoice + $total;
            }
        }else{
            return redirect('/order/invoice');
        }



        $totalInvoice_old = $totalInvoice;
        if(Session::get('coupon_amount')){
            $totalInvoice = $totalInvoice - Session::get('coupon_amount');
        }

        $price = $UserLogin->credit - $totalInvoice;
        if($UserLogin->credit <= 0){
            $payment_price = $totalInvoice;
        }else{
            $payment_price = $totalInvoice - $UserLogin->credit;
            $payment_price = ($payment_price <= 0) ? 0 : $payment_price;
        }



        if(count($request) > 0){
            foreach ($request as $v){

                $count = (int) $v['rcount'];
                $count += (int) $v['lcount'];

                if($v['product_detail_id']){
                    $detail_id = json_decode($v['product_detail_id']);
                    if($detail_id){
                        foreach ($detail_id as $detail){

                            $inventory = 0;
                            if($v['product_type'] == 1){

                                $lens = lensPrice::where('id', $detail)->first();
                                if($lens){
                                    $lens->inventory = $lens->inventory - $count;
                                    $lens->save();
                                }

                            }else{

                                $optical = optical_glassPrice::where('id', $detail)->first();
                                if($optical){
                                    $optical->inventory = $optical->inventory - $count;
                                    $optical->save();
                                }

                            }


                        }
                    }



                }

            }
        }


        if($payment_price > 0){

            // Validation Data
            /*$ValidData = $this->validate($this->request,[
                'price' => 'required|numeric|min:1000',
                'type' => 'required'
            ]);

            if($ValidData['price'] < $payment_price) return back()->with('error' ,  'مبلغ وارد شده کم تر از مبلغ قابل پرداخت می باشد.');*/


            $sku = str_random(20);
            if($request){
                foreach ($request as $order){
                    OrderController::update_optien($order['id'], 'thank_you_key', $sku);
                }
            }


            $newTransaction = new transaction;
            $newTransaction->user_id = $UserLogin->id;
            $newTransaction->price = $payment_price;
            $newTransaction->credit = $UserLogin->credit;
            $newTransaction->orginal_price = $totalInvoice_old;
            if(Session::get('coupon_amount')){
                $newTransaction->discount = Session::get('coupon_amount');
            }
            $newTransaction->key = $sku;
            $newTransaction->status = 'pending';
            $newTransaction->save();

            return redirect('order/payment/bank/' . $sku);
            exit();

        }



        $sku = str_random(20);
        if($request){
            foreach ($request as $order){

                $orderup = Order::where('id' , $order['id'])->first();
                $orderup->status = 'paid';
                $orderup->save();

                OrderController::update_optien($order['id'], 'thank_you_key', $sku);


                if($orderup->labrator_id){

                    $lb_user = User::where('id' , $orderup->labrator_id)->first();
                    if($lb_user){
                        SendSMS::sendNewOrder($lb_user->mobile , $orderup->id);

                    }

                }

                if($orderup->lathe_id){

                    $lb_user = User::where('id' , $orderup->lathe_id)->first();
                    if($lb_user){
                        SendSMS::sendNewOrder($lb_user->mobile , $orderup->id);

                    }

                }

            }
        }

        $newTransaction = new transaction;
        $newTransaction->user_id = $UserLogin->id;
        $newTransaction->price = $totalInvoice;
        $newTransaction->orginal_price = $totalInvoice_old;

        if(Session::get('coupon_amount')){
            $newTransaction->discount = Session::get('coupon_amount');
        }

        $newTransaction->key = $sku;
        $newTransaction->payment_method = 'credit';
        $newTransaction->tracking_code = '0';
        $newTransaction->status = 'paid';
        $newTransaction->save();


        if($UserLogin->mobile){
            SendSMS::sendNewOrder($UserLogin->mobile , 'BOP-' . $newTransaction->id);
        }

        $damin_user = User::where('role' , 'admin')->get();
        if($damin_user){
            foreach ($damin_user as $v_admin){
                SendSMS::sendNewOrder($v_admin['mobile'] , 'BOP-' . $newTransaction->id);
            }
        }



        $credit = $UserLogin->credit - $totalInvoice;
        $credit = ($credit <= 0) ? 0 : $credit;

        $UserLogin->credit = $credit;
        $UserLogin->save();

        return redirect('order/thank-you/' . $sku);

    }

    public function OrderThankYou(){
        $orderKey = $this->request->key;
        $UserLogin = Auth::user();

        if(!$orderKey){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }


        $request = transaction::where('key' , $orderKey)->where('user_id' , $UserLogin->id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');


        $order_detail_list = [];
        $order_details = Order_detail::where('key' , 'thank_you_key')->where('val' , $request->key)->get();
        if($order_details){
            foreach ($order_details as $order_detail){
                $order_detail_list[] = $order_detail['order_id'];
            }

            $order = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                ->join('products', 'products.id' , '=', 'orders.product_id' )
                ->whereIN('orders.id' , $order_detail_list)
                ->where('orders.status' , 'paid')
                ->where('orders.user_id' , $UserLogin->id)
                ->select(
                    'prescriptions.*',
                    'products.type as product_type',
                    'products.sku as product_sku',
                    'products.seller_id as seller',
                    'orders.created_at as created',
                    'orders.price as price',
                    'orders.lathe_id as lathe_id',
                    'orders.order_key as key',
                    'orders.id as order_id'
                )->get();

            $fullCart = array();
            $labrator = array();
            if($order){
                foreach ($order as $v){
                    $total = $v['price'];
                    $labrator_price = 0;

                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                    if($order_service){
                        foreach ($order_service as $service) {
                            $labrator_price = $labrator_price + $service['val'];
                            $total = $total + $service['val'];
                        }
                    }

                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key' ,'order_price')->first();
                    if($order_service){

                        if($order_service->val){
                            $price = $labrator_price * $order_service->val / 100;
                            $total = $total + $price;
                        }

                    }

                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key' ,'order_service_0_labrator')->get();
                    if($order_service){
                        foreach ($order_service as $service) {
                            $labrator[$v['order_id']] = $service['val'];
                        }
                    }

                    $fullCart[$v['order_id']] = $total;
                    $full_labrator_price[$v['order_id']] = $labrator_price;
                }
            }

        }


        return view('site/order/thank_you', ['UserLogin' => $UserLogin, 'request' => $request, 'order' => $order, 'fullTotal' => $fullCart, 'full_labrator_price' => $full_labrator_price, 'labrator' => $labrator]);
    }

    public function OrderCancel(){
        $orderKey = $this->request->key;
        $UserLogin = Auth::user();

        if(!$orderKey){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }


        $request = transaction::where('key' , $orderKey)->where('posting_status' , '!=' ,'cancel')->where('user_id' , $UserLogin->id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');

        if($request->posting_status == 'pending'){

            $request->posting_status = 'cancel';
            $request->status = 'cancel';
            $request->save();

            if($request->price > 0){

                $UserLogin->credit = $request->price + $UserLogin->credit;
                $UserLogin->save();

                $sku = str_random(20);

                $newTransaction = new transaction;
                $newTransaction->user_id = $UserLogin->id;
                $newTransaction->price = $request->price;
                $newTransaction->type = 'charge';
                $newTransaction->key = $sku;
                $newTransaction->payment_method = 'online';
                $newTransaction->tracking_code = '0';
                $newTransaction->status = 'paid';
                $newTransaction->posting_status = '';
                $newTransaction->description = 'واریز اعتبار به دلیل لغو سفارش : ' . 'BOP-' . $request->id;
                $newTransaction->save();

            }

            return redirect('dashboard')->with('success' , 'سفارش با موفقیت لغو شد.');
        }else{
            return back()->with('error' , 'امکان لغو سفارش وجود ندارد.');
        }
    }


    public function paymentBank(){

        $key = $this->request->key;

        $transaction = transaction::where('key' , $key)->where('status' , 'pending')->first();
        if(!$transaction) return redirect('/');


        $price 			= $transaction->price * 10; 		// Price Rial
        $ResNum 		= $transaction->id; 			// Invoice Number
        $MerchantCode 	= "50026119";
        $RedirectURL 	= url('order/payment/verify-bank/' . $key);

        return "<form id='samanpeyment' action='https://sep.shaparak.ir/payment.aspx' method='post'>
            <input type='hidden' name='Amount' value='{$price}' />
            <input type='hidden' name='ResNum' value='{$ResNum}'>
            <input type='hidden' name='RedirectURL' value='{$RedirectURL}'/>
            <input type='hidden' name='MID' value='{$MerchantCode}'/>
            </form><script>document.forms['samanpeyment'].submit()</script>";
    }

    public function paymentVerifyBank(){

        $key = $this->request->key;
        $State = $this->request->get('State');
        $RefNum = $this->request->get('RefNum');

        $transaction = transaction::where('key' , $key)->where('status' , 'pending')->first();
        if(!$transaction) return redirect('/');

        $MerchantCode = "50026119";

        if(isset($State) && $State == "OK") {

            $soapclient = new soapclient('https://verify.sep.ir/Payments/ReferencePayment.asmx?WSDL');
            $res 		= $soapclient->VerifyTransaction($RefNum, $MerchantCode);

            if( $res <= 0 )
            {
                return redirect('order/payment')->with('error' ,  'تراکنش ناموفق ، لطفا پس از 15 دقیقه صفحه سابقه مالی را رفرش کنید و اگر واریزی در حساب شما ثبت نشده بود بار دیگر تراکنش اینترنتی خود را تکرار کنید' . $res);
            } else {




                $order_details = Order_detail::where('key' , 'thank_you_key')->where('val' , $transaction->key)->get();
                if($order_details){
                    foreach ($order_details as $order){

                        $orderup = Order::where('id' , $order['order_id'])->first();
                        if($orderup){
                            $orderup->status = 'paid';
                            $orderup->save();

                            if($orderup->labrator_id){

                                $lb_user = User::where('id' , $orderup->labrator_id)->first();
                                if($lb_user){
                                    SendSMS::sendNewOrder($lb_user->mobile , $orderup->id);

                                }

                            }

                            if($orderup->lathe_id){

                                $lb_user = User::where('id' , $orderup->lathe_id)->first();
                                if($lb_user){
                                    SendSMS::sendNewOrder($lb_user->mobile , $orderup->id);

                                }

                            }

                        }

                    }
                }


                $transaction->tracking_code = $RefNum;
                $transaction->status = 'paid';
                $transaction->save();

                if($UserLogin->mobile){
                    SendSMS::sendNewOrder($UserLogin->mobile , 'BOP-' . $transaction->id);
                }

                $damin_user = User::where('role' , 'admin')->get();
                if($damin_user){
                    foreach ($damin_user as $v_admin){
                        SendSMS::sendNewOrder($v_admin['mobile'] , 'BOP-' . $transaction->id);
                    }
                }


                $UserLogin = Auth::user();
                if($transaction->price < $transaction->orginal_price){

                    $credit = $UserLogin->credit - $transaction->credit;
                    $credit = ($credit <= 0) ? 0 : $credit;

                    $UserLogin->credit = $credit;
                    $UserLogin->save();

                }


                return redirect('order/thank-you/' . $key);
            }
        } else {
            return redirect('order/payment')->with('error' ,  'تراکنش لغو شد.');
        }
    }


    public function getPrescription(){
        $key = $this->request->get('key');
        $UserLogin = Auth::user();

        $request = Prescription::where('id' , $key)->where('user_id' , $UserLogin->id)->first();
        if(!$request) return back()->with('error' , 'لطفا اطلاعات را به درستی وارد کنید.');

        if (!$this->request->ajax()) return redirect('order/new');

        $prescriptions = Prescription::where('user_id', $UserLogin->id)->orderBy('created_at', 'desc')->get();

        $count = $this->LensProductNumber();

        $sph_lens = $this->SearchSph();
        $cyl_lens = $this->SearchCyl();
        $axis_lens = $this->SearchAxis();

        $sph_optic = $this->SearchSphOptic();
        $cyl_optic = $this->SearchCylOptic();
        $axis_optic = $this->SearchAxisOptic();
        $add_optic = $this->SearchAddOptic();

        $pd = $this->SearchPd();


        return view('site/order/ajax_prescription' , ['request' => $request, 'prescriptions' => $prescriptions, 'count' => $count,
            'sph' => $sph_lens, 'cyl' => $cyl_lens, 'axis' => $axis_lens,
            'sph_optic' => $sph_optic, 'cyl_optic' => $cyl_optic, 'axis_optic' => $axis_optic, 'add_optic' => $add_optic, 'pd' => $pd
        ]);

    }


    public function ActionDiscount(){

        if(!Auth::check()){
            return redirect(Session::get('lang') . "/login");
        }

        $discount_cod = $this->request->get('discount_cod');

        $ValidData = $this->validate($this->request,[
            'discount_cod' => 'required',
        ]);
        $user = Auth::user();

        $coupon = coupon::where('coupon_key', $discount_cod)->where('start_date', '<=' ,date('Y-m-d'))->where('expiry_date', '>=' ,date('Y-m-d'))->first();

        if($coupon){

            $discount_price = 0;
            $full_price = 0;

            $cartsData = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                ->join('products', 'products.id' , '=', 'orders.product_id' )
                ->where('orders.status' , 'pending_pay')
                ->where('orders.user_id' , $user->id)
                ->orderBy('orders.created_at', 'desc')
                ->select(
                    'prescriptions.*',
                    'products.type as product_type',
                    'products.sku as product_sku',
                    'orders.created_at as created',
                    'orders.price as price',
                    'orders.order_key as key',
                    'orders.id as order_id'
                )->get();

            if($cartsData){
                foreach ($cartsData as $key => $cd){

                    $statusDis = false;
                    if($coupon->product_categories == 'lens' && $cd['product_type'] != '1'){
                        $statusDis = true;
                    }

                    if($coupon->product_categories == 'optical-glass' && $cd['product_type'] != '2'){
                        $statusDis = true;
                    }

                    if($coupon->product_categories == 'optical-glass' && $cd['product_type'] != '2'){
                        $statusDis = true;
                    }

                    if($coupon->product_ids){

                        $product_ids = json_decode($coupon->product_ids);
                        if($product_ids && !in_array($cd['product_sku'], $product_ids)){
                            $statusDis = true;
                        }

                    }

                    if($coupon->exclude_product_ids){

                        $product_ids = json_decode($coupon->exclude_product_ids);
                        if($product_ids && in_array($cd['product_sku'], $product_ids)){
                            $statusDis = true;
                        }

                    }

                    $total = $cd['price'];

                    $order_service = Order_detail::where('order_id', $cd['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                    if($order_service){
                        foreach ($order_service as $service) {
                            $total = $total + $service['val'];
                        }
                    }

                    $order_service = Order_detail::where('order_id', $cd['order_id'])->where('key' ,'order_price')->first();
                    if($order_service){

                        if($order_service->val){
                            $price = $total * $order_service->val / 100;
                            $total = $total + $price;
                        }

                    }
                    $full_price += $total;

                    if($coupon->discount_type == 'percent'){
                        $discount_price += $total * $coupon->coupon_amount / 100;
                    }

                }
            }

            $userCount = couponUsed::where('coupon_id', $coupon->id)->count();
            if($coupon->disposable == 1 && $userCount > 0){
                $statusDis = true;
            }

            if($coupon->minimum_amount > 0 && $full_price < $coupon->minimum_amount){
                $statusDis = true;
            }

            if($coupon->maximum_amount > 0 && $full_price > $coupon->maximum_amount){
                $statusDis = true;
            }

            if($statusDis == false){

                if($coupon->discount_type != 'percent'){
                    $discount_price = $coupon->coupon_amount;
                }

                Session::put('coupon_set', $coupon->id);
                Session::put('coupon_amount', $discount_price);
                Session::put('coupon_key', $discount_cod);

                return response()->json([
                    'status' => 'success',
                    'discount' => $discount_price,
                    'message' => $coupon->description,
                    'msg' => 'کد تخفیف وارد شد.'
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'msg' => 'کد وارد شده معتبر نمی باشد.'
        ]);
    }

}

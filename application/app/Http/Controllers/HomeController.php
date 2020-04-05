<?php

namespace App\Http\Controllers;

use App\Brand;
use App\city;
use App\country;
use App\post;
use App\Product;
use App\province;
use App\SendSMS;
use App\slider;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends ProductSmartController
{

    protected $request;

    public function __construct(Request $request){

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $slider = slider::orderBy('created_at', 'desc')->get();

        $brands = ['lens' => [] , 'optical' => []];
        $brandsRequest = Brand::where('logo' , '!=', '')->get();
        if($brandsRequest){
            foreach ($brandsRequest as $brand){

                $lensPro = Product::where('brand_id', $brand['id'])->orderBy('created_at', 'desc')->first();
                if($lensPro){
                    if($lensPro->type == 1) $brands['lens'][] = $brand;
                    if($lensPro->type == 2) $brands['optical'][] = $brand;
                }

            }
        }

        $lens = [];
        $proList = [];
        $lensPro = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
            ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
            ->where('products.type', 1)
            ->where('products.status', 'active')
            ->where('products.price', '>' , 0)
            ->where('products.status', 'active')
            ->orderBy('products.created_at', 'desc')
            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','products.original_price','products.price')
            ->limit(20)->get();

        if($lensPro){
            foreach ($lensPro as $pro){
                if(!in_array($pro['id'] , $proList)){
                    $proList[] = $pro['id'];
                    $lens[] = $pro;
                }
            }
        }

        $lensOffer = [];
        $proList = [];
        $lensPro = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
            ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
            ->where('products.sale_price', '>' , 0)
            ->where('products.type', 1)
            ->where('products.status', 'active')
            ->orderBy('products.created_at', 'desc')
            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','products.original_price','products.price')
            ->limit(20)->get();

        if($lensPro){
            foreach ($lensPro as $pro){
                if(!in_array($pro['id'] , $proList)){
                    $proList[] = $pro['id'];
                    $lensOffer[] = $pro;
                }
            }
        }


        $optical = [];
        $proList = [];
        $opticalPro = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
            ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
            ->where('products.type', 2)
            ->where('products.status', 'active')
            ->where('products.price', '>' , 0)
            ->orderBy('products.created_at', 'desc')
            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','products.original_price','products.price')
            ->limit(20)->get();

        if($opticalPro){
            foreach ($opticalPro as $pro){
                if(!in_array($pro['id'] , $proList)){
                    $proList[] = $pro['id'];
                    $optical[] = $pro;
                }
            }
        }


        $opticalOffer = [];
        $proList = [];
        $opticalPro = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
            ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
            ->where('products.sale_price', '>' , 0)
            ->where('products.status', 'active')
            ->where('products.type', 2)
            ->orderBy('products.created_at', 'desc')
            ->select('products.id','products.sku','products.image','products.brand_id','brands.name','products.original_price','products.price')
            ->limit(20)->get();

        if($opticalPro){
            foreach ($opticalPro as $pro){
                if(!in_array($pro['id'] , $proList)){
                    $proList[] = $pro['id'];
                    $opticalOffer[] = $pro;
                }
            }
        }


        $page = post::where('location', 'home')->limit(6)->get();

        return view('site/index' , [ 'slider' => $slider,  'lens' => $lens, 'optical' => $optical,  'lensOffer' => $lensOffer, 'opticalOffer' => $opticalOffer, 'brands' => $brands, 'page' => $page ]);
    }

    public function product(){
        $proid = $this->request->id;

        $request = Product::join('brands', 'brands.id' , '=' , 'products.brand_id')->where('products.id' , $proid)->select('products.*', 'brands.name')->first();
        if(!$request) return App::abort(404);

        $requestFull = [];
        if($request->type == 1){

            $requestFull = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                ->where('products.id', $proid)
                ->where('products.type', 1)
                ->where('products.status', 'active')
                ->orderBy('products.created_at', 'desc')
                ->select('lens_details.*','brands.name','lens_prices.id as price_id','products.original_price','products.price')
                ->first();

        }else if($request->type == 2){

            $requestFull = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                ->join('brands', 'brands.id' , '=' , 'products.brand_id')
                ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                ->where('products.id', $proid)
                ->where('products.type', 2)
                ->where('products.status', 'active')
                ->orderBy('products.created_at', 'desc')
                ->select('optical_glass_details.*','brands.name','optical_glass_prices.id as price_id','products.original_price','products.price')
                ->first();

        }

        $country = country::where('title', $request->country)->first();


        return view('site/product' , [ 'request' => $request , 'requestFull' => $requestFull, 'country' => $country ]);
    }

    public function confermAccount(){

        if(!Auth::check()){
            return redirect("/login")->with('error' , 'جهت مشاهده این بخش وارد حساب کاربری خود شوید.');
        }

        if(Auth::user()->status != "not_verified"){
            return redirect("/dashboard")->with('error' , 'دسترسی به این بخش برای شما مجاز نمی باشد.');
        }

        $user = Auth::user();

        $expiredTime = Carbon::parse($user->conferm_time)->addMinute(1);

        return view('auth.conferm', ['expiredTime' => $expiredTime]);
    }

    public function actionConfermAccount(){

        if(!Auth::check()){
            return redirect("/login")->with('error' , 'جهت مشاهده این بخش وارد حساب کاربری خود شوید.');
        }

        if(Auth::user()->status != "not_verified"){
            return redirect("/dashboard")->with('error' , 'دسترسی به این بخش برای شما مجاز نمی باشد.');
        }

        // Validation Data
        $code = $this->request->get('code');
        if(!$code) return back()->with('error' ,  'لطفا کد تایید را وارد کنید.');
        $conferm = join('', $code);

        $user = Auth::user();

        $request = User::where('id', $user->id)->first();
        if(!$request || $request->conferm != $conferm) return back()->with('error' ,  'کد تایید وارد شده صحیح نمی باشد.');

        $request->status = 'not_active';
        $request->save();

        return redirect('/dashboard');
    }

    public function ResandconfermAccount(){

        if(!Auth::check()){
            return redirect("/login")->with('error' , 'جهت مشاهده این بخش وارد حساب کاربری خود شوید.');
        }

        if(Auth::user()->status != "not_verified"){
            return redirect("/dashboard")->with('error' , 'دسترسی به این بخش برای شما مجاز نمی باشد.');
        }

        $user = Auth::user();

        $expiredTime = Carbon::parse($user->conferm_time)->addMinute(1);
        $newtime = date('Y-m-d H:i:s');

        if(strtotime($expiredTime) > strtotime($newtime)){
            return back()->with('error' , 'در حال حاضر امکان ارسال مجدد نمی باشد لطفا دقایقی دیگر امتحان کنید.');
        }

        $user->conferm_time = date('Y-m-d H:i:s');
        $user->save();

        SendSMS::sendConferm($user->mobile, $user->conferm);

        return back()->with('success' , ' کد تایید مجددا برای شما ارسال شد.');
    }

    /**
     *
     */
    public function brand(){
        $type  = $this->request->st;
        $key  = $this->request->key;
        $key = urldecode($key);

        $whereFillter =[];

        if ($this->request->get("filter_brand") && !empty($this->request->get("filter_brand")) ) {
            $whereFillter[] = array('brands.name', $this->request->get("filter_brand"));
        }

        if ($this->request->get("filter_country") && !empty($this->request->get("filter_country"))) {
            $whereFillter[] = array('products.country', $this->request->get("filter_country"));
        }

        if($type == 'lens') {

            if ($this->request->get("filter_astigmatism") && !empty($this->request->get("filter_astigmatism"))) {
                $whereFillter[] = array('lens_details.astigmatism', $this->request->get("filter_astigmatism"));
                }

            if ($this->request->get("filter_consumption_period") && !empty($this->request->get("filter_consumption_period"))) {
                $whereFillter[] = array('lens_details.consumption_period', $this->request->get("filter_consumption_period"));
            }

            if ( $this->request->get("filter_curvature") && !empty($this->request->get("filter_curvature"))) {
                $whereFillter[] = array('lens_details.curvature', $this->request->get("filter_curvature"));
            }

            if ($this->request->get("filter_diagonal") && !empty($this->request->get("filter_diagonal"))) {
                $whereFillter[] = array('lens_prices.diagonal', $this->request->get("filter_diagonal"));
            }

            if ($this->request->get("filter_number") && !empty($this->request->get("filter_number"))) {
                $whereFillter[] = array('lens_details.number', $this->request->get("filter_number"));
            }

            if ($this->request->get("filter_structure") && !empty($this->request->get("filter_structure"))) {
                $whereFillter[] = array('lens_details.structure', $this->request->get("filter_structure"));
            }


            if ($this->request->get("max_filter_abatement") && !empty($this->request->get("max_filter_abatement"))) {
                $whereFillter[] = array('lens_details.abatement', '<=', $this->request->get("max_filter_abatement"));
            }

            if ($this->request->get("min_filter_abatement") && !empty($this->request->get("min_filter_abatement"))) {
                $whereFillter[] = array('lens_details.abatement', '>=', $this->request->get("min_filter_abatement"));
            }

            if ($this->request->get("max_filter_oxygen_supply") && !empty($this->request->get("max_filter_oxygen_supply"))) {
                $whereFillter[] = array('lens_details.oxygen_supply', '<=', $this->request->get("max_filter_oxygen_supply"));
            }

            if ($this->request->get("min_filter_oxygen_supply") && !empty($this->request->get("min_filter_oxygen_supply"))) {
                $whereFillter[] = array('lens_details.oxygen_supply', '>=', $this->request->get("min_filter_oxygen_supply"));
            }

            if ($this->request->get("max_filter_thickness") && !empty($this->request->get("max_filter_thickness"))) {
                $whereFillter[] = array('lens_details.thickness', '<=', $this->request->get("max_filter_thickness"));
            }

            if ($this->request->get("min_filter_thickness") && !empty($this->request->get("min_filter_thickness"))) {
                $whereFillter[] = array('lens_details.thickness', '>=', $this->request->get("min_filter_thickness"));
            }

            if ($this->request->get("max_filter_price") && !empty($this->request->get("max_filter_price"))) {
                $whereFillter[] = array('lens_prices.price', '<=', $this->request->get("max_filter_price"));
            }

            if ($this->request->get("min_filter_price") && !empty($this->request->get("min_filter_price"))) {
                $whereFillter[] = array('lens_prices.price', '>=', $this->request->get("min_filter_price"));
            }
        }
        else{

            if ($this->request->get("filter_size") && !empty($this->request->get("filter_size"))) {
                $whereFillter[] = array('optical_glass_prices.size', $this->request->get("filter_size"));
            }

            if ($this->request->get("filter_type") && !empty($this->request->get("filter_type"))) {
                $whereFillter[] = array('optical_glass_details.type', $this->request->get("filter_type"));
            }

            if ($this->request->get("filter_curvature") && !empty($this->request->get("filter_curvature"))) {
                $whereFillter[] = array('optical_glass_details.curvature', $this->request->get("filter_curvature"));
            }

            if ($this->request->get("filter_refractive_index") && !empty($this->request->get("filter_refractive_index"))) {
                $whereFillter[] = array('optical_glass_details.refractive_index', $this->request->get("filter_refractive_index"));
            }

            if ($this->request->get("filter_anti_reflex_color") && !empty($this->request->get("filter_anti_reflex_color"))) {
                $whereFillter[] = array('optical_glass_details.anti_reflex_color', $this->request->get("filter_anti_reflex_color"));
            }

            if ($this->request->get("filter_block") && !empty($this->request->get("filter_block"))) {
                $whereFillter[] = array('optical_glass_details.block', $this->request->get("filter_block"));
            }

            if ($this->request->get("filter_bloc_troll") && !empty($this->request->get("filter_bloc_troll"))) {
                $whereFillter[] = array('optical_glass_details.bloc_troll', $this->request->get("filter_bloc_troll"));
            }

            if ($this->request->get("filter_photocrophy") && !empty($this->request->get("filter_photocrophy"))) {
                $whereFillter[] = array('optical_glass_details.photocrophy', $this->request->get("filter_photocrophy"));
            }

            if ($this->request->get("filter_photo_color") && !empty($this->request->get("filter_photo_color"))) {
                $whereFillter[] = array('optical_glass_details.photo_color', $this->request->get("filter_photo_color"));
            }

            if ($this->request->get("filter_polycarbonate") && !empty($this->request->get("filter_polycarbonate"))) {
                $whereFillter[] = array('optical_glass_details.polycarbonate', $this->request->get("filter_polycarbonate"));
            }

            if ($this->request->get("filter_polycarbonate") && !empty($this->request->get("filter_polycarbonate"))) {
                $whereFillter[] = array('optical_glass_details.polycarbonate', $this->request->get("filter_polycarbonate"));
            }

            if ($this->request->get("filter_poly_break") && !empty($this->request->get("filter_poly_break"))) {
                $whereFillter[] = array('optical_glass_details.poly_break', $this->request->get("filter_poly_break"));
            }

            if ($this->request->get("filter_color_white") && !empty($this->request->get("filter_color_white"))) {
                $whereFillter[] = array('optical_glass_details.color_white', $this->request->get("filter_color_white"));
            }

            if ($this->request->get("filter_colored_score") && !empty($this->request->get("filter_colored_score"))) {
                $whereFillter[] = array('optical_glass_details.colored_score', $this->request->get("filter_colored_score"));
            }

            if ($this->request->get("filter_watering") && !empty($this->request->get("filter_watering"))) {
                $whereFillter[] = array('optical_glass_details.watering', $this->request->get("filter_watering"));
            }

            if ($this->request->get("filter_structure") && !empty($this->request->get("filter_structure"))) {
                $whereFillter[] = array('optical_glass_details.structure', $this->request->get("filter_structure"));
            }

            if ($this->request->get("filter_yu_vie") && !empty($this->request->get("filter_yu_vie"))) {
                $whereFillter[] = array('optical_glass_details.yu_vie', $this->request->get("filter_yu_vie"));
            }

            if ($this->request->get("max_filter_price") && !empty($this->request->get("max_filter_price"))) {
                $whereFillter[] = array('optical_glass_prices.price', '<=', $this->request->get("max_filter_price"));
            }

            if ($this->request->get("min_filter_price") && !empty($this->request->get("min_filter_price"))) {
                $whereFillter[] = array('optical_glass_prices.price', '>=', $this->request->get("min_filter_price"));
            }

        }

        $product = [];

        $filters = [];
        $request ='';
        switch ($type){
            case 'lens':
                $request = Brand::join('products', 'brands.id' , '=' , 'products.brand_id')->where('brands.name' , $key)->where('products.type', 1)->select('brands.*')->orderBy('brands.id', 'desc')->first();

                $filters = array(
                    [
                        'type' => 'select',
                        'name' => 'country',
                        'title' => 'کشور سازنده',
                        'value' => country::orderBy('title', 'ASC')->get()
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
                );


                if($request){

                    $product = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                        ->join('lens_prices', 'lens_prices.product_id' , '=' , 'products.id')
                        ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->where('brand_id', $request->id)
                        ->where($whereFillter)
                        ->where('products.price', '>' , 0)
                        ->where('products.type', 1)
                        ->where('products.status', 'active')
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.type','products.image','products.original_price','products.price')
                        ->distinct()
                        ->get();

                }

                break;
            case 'optical':
                $request = Brand::join('products', 'brands.id' , '=' , 'products.brand_id')->where('brands.name' , $key)->where('products.type', 2)->select('brands.*')->orderBy('brands.id', 'desc')->first();

                $filters = array(
                    [
                        'type' => 'select',
                        'name' => 'country',
                        'title' => 'کشور سازنده',
                        'value' => country::orderBy('title', 'ASC')->get()
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
                    ]
                );


                if($request){

                    $product = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                        ->join('optical_glass_prices', 'optical_glass_prices.product_id' , '=' , 'products.id')
                        ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->where('brand_id', $request->id)
                        ->where($whereFillter)
                        ->where('products.price', '>' , 0)
                        ->where('products.type', 2)
                        ->where('products.status', 'active')
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.type','products.image','products.original_price','products.price')
                        ->distinct()
                        ->get();

                }

                break;
        }

        return view('site/brand' , [ 'product' => $product, 'brand' => $request, 'filters' => $filters ]);

    }

    public function products(){
        $type  = $this->request->st;

        $whereFillter =[];

        if ($this->request->get("filter_brand") && !empty($this->request->get("filter_brand")) ) {
            $whereFillter[] = array('brands.name', $this->request->get("filter_brand"));
        }

        if ($this->request->get("filter_country") && !empty($this->request->get("filter_country"))) {
            $whereFillter[] = array('products.country', $this->request->get("filter_country"));
        }

        if($type == 'lens') {

            if ($this->request->get("filter_astigmatism") && !empty($this->request->get("filter_astigmatism"))) {
                $whereFillter[] = array('lens_details.astigmatism', $this->request->get("filter_astigmatism"));
                }

            if ($this->request->get("filter_consumption_period") && !empty($this->request->get("filter_consumption_period"))) {
                $whereFillter[] = array('lens_details.consumption_period', $this->request->get("filter_consumption_period"));
            }

            if ( $this->request->get("filter_curvature") && !empty($this->request->get("filter_curvature"))) {
                $whereFillter[] = array('lens_details.curvature', $this->request->get("filter_curvature"));
            }

            if ($this->request->get("filter_diagonal") && !empty($this->request->get("filter_diagonal"))) {
                $whereFillter[] = array('lens_prices.diagonal', $this->request->get("filter_diagonal"));
            }

            if ($this->request->get("filter_number") && !empty($this->request->get("filter_number"))) {
                $whereFillter[] = array('lens_details.number', $this->request->get("filter_number"));
            }

            if ($this->request->get("filter_structure") && !empty($this->request->get("filter_structure"))) {
                $whereFillter[] = array('lens_details.structure', $this->request->get("filter_structure"));
            }


            if ($this->request->get("max_filter_abatement") && !empty($this->request->get("max_filter_abatement"))) {
                $whereFillter[] = array('lens_details.abatement', '<=', $this->request->get("max_filter_abatement"));
            }

            if ($this->request->get("min_filter_abatement") && !empty($this->request->get("min_filter_abatement"))) {
                $whereFillter[] = array('lens_details.abatement', '>=', $this->request->get("min_filter_abatement"));
            }

            if ($this->request->get("max_filter_oxygen_supply") && !empty($this->request->get("max_filter_oxygen_supply"))) {
                $whereFillter[] = array('lens_details.oxygen_supply', '<=', $this->request->get("max_filter_oxygen_supply"));
            }

            if ($this->request->get("min_filter_oxygen_supply") && !empty($this->request->get("min_filter_oxygen_supply"))) {
                $whereFillter[] = array('lens_details.oxygen_supply', '>=', $this->request->get("min_filter_oxygen_supply"));
            }

            if ($this->request->get("max_filter_thickness") && !empty($this->request->get("max_filter_thickness"))) {
                $whereFillter[] = array('lens_details.thickness', '<=', $this->request->get("max_filter_thickness"));
            }

            if ($this->request->get("min_filter_thickness") && !empty($this->request->get("min_filter_thickness"))) {
                $whereFillter[] = array('lens_details.thickness', '>=', $this->request->get("min_filter_thickness"));
            }

            if ($this->request->get("max_filter_price") && !empty($this->request->get("max_filter_price"))) {
                $whereFillter[] = array('lens_prices.price', '<=', $this->request->get("max_filter_price"));
            }

            if ($this->request->get("min_filter_price") && !empty($this->request->get("min_filter_price"))) {
                $whereFillter[] = array('lens_prices.price', '>=', $this->request->get("min_filter_price"));
            }
        }
        else{

            if ($this->request->get("filter_size") && !empty($this->request->get("filter_size"))) {
                $whereFillter[] = array('optical_glass_details.size', $this->request->get("filter_size"));
            }

            if ($this->request->get("filter_type") && !empty($this->request->get("filter_type"))) {
                $whereFillter[] = array('optical_glass_details.type', $this->request->get("filter_type"));
            }

            if ($this->request->get("filter_curvature") && !empty($this->request->get("filter_curvature"))) {
                $whereFillter[] = array('optical_glass_details.curvature', $this->request->get("filter_curvature"));
            }

            if ($this->request->get("filter_refractive_index") && !empty($this->request->get("filter_refractive_index"))) {
                $whereFillter[] = array('optical_glass_details.refractive_index', $this->request->get("filter_refractive_index"));
            }

            if ($this->request->get("filter_anti_reflex_color") && !empty($this->request->get("filter_anti_reflex_color"))) {
                $whereFillter[] = array('optical_glass_details.anti_reflex_color', $this->request->get("filter_anti_reflex_color"));
            }

            if ($this->request->get("filter_block") && !empty($this->request->get("filter_block"))) {
                $whereFillter[] = array('optical_glass_details.block', $this->request->get("filter_block"));
            }

            if ($this->request->get("filter_bloc_troll") && !empty($this->request->get("filter_bloc_troll"))) {
                $whereFillter[] = array('optical_glass_details.bloc_troll', $this->request->get("filter_bloc_troll"));
            }

            if ($this->request->get("filter_photocrophy") && !empty($this->request->get("filter_photocrophy"))) {
                $whereFillter[] = array('optical_glass_details.photocrophy', $this->request->get("filter_photocrophy"));
            }

            if ($this->request->get("filter_photo_color") && !empty($this->request->get("filter_photo_color"))) {
                $whereFillter[] = array('optical_glass_details.photo_color', $this->request->get("filter_photo_color"));
            }

            if ($this->request->get("filter_polycarbonate") && !empty($this->request->get("filter_polycarbonate"))) {
                $whereFillter[] = array('optical_glass_details.polycarbonate', $this->request->get("filter_polycarbonate"));
            }

            if ($this->request->get("filter_polycarbonate") && !empty($this->request->get("filter_polycarbonate"))) {
                $whereFillter[] = array('optical_glass_details.polycarbonate', $this->request->get("filter_polycarbonate"));
            }

            if ($this->request->get("filter_poly_break") && !empty($this->request->get("filter_poly_break"))) {
                $whereFillter[] = array('optical_glass_details.poly_break', $this->request->get("filter_poly_break"));
            }

            if ($this->request->get("filter_color_white") && !empty($this->request->get("filter_color_white"))) {
                $whereFillter[] = array('optical_glass_details.color_white', $this->request->get("filter_color_white"));
            }

            if ($this->request->get("filter_colored_score") && !empty($this->request->get("filter_colored_score"))) {
                $whereFillter[] = array('optical_glass_details.colored_score', $this->request->get("filter_colored_score"));
            }

            if ($this->request->get("filter_watering") && !empty($this->request->get("filter_watering"))) {
                $whereFillter[] = array('optical_glass_details.watering', $this->request->get("filter_watering"));
            }

            if ($this->request->get("filter_structure") && !empty($this->request->get("filter_structure"))) {
                $whereFillter[] = array('optical_glass_details.structure', $this->request->get("filter_structure"));
            }

            if ($this->request->get("filter_yu_vie") && !empty($this->request->get("filter_yu_vie"))) {
                $whereFillter[] = array('optical_glass_details.yu_vie', $this->request->get("filter_yu_vie"));
            }

            if ($this->request->get("max_filter_price") && !empty($this->request->get("max_filter_price"))) {
                $whereFillter[] = array('optical_glass_prices.price', '<=', $this->request->get("max_filter_price"));
            }

            if ($this->request->get("min_filter_price") && !empty($this->request->get("min_filter_price"))) {
                $whereFillter[] = array('optical_glass_prices.price', '>=', $this->request->get("min_filter_price"));
            }

        }

        $product = [];

        $filters = [];
        $request ='';
        switch ($type){
            case 'lens':
                $request = Brand::join('products', 'brands.id' , '=' , 'products.brand_id')->where('products.type', 1)->select('brands.*')->orderBy('brands.id', 'desc')->first();

                $filters = array(
                    [
                        'type' => 'select',
                        'name' => 'country',
                        'title' => 'کشور سازنده',
                        'value' => country::orderBy('title', 'ASC')->get()
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
                );


                if($request){

                    $product = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
                        ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->where('brand_id', $request->id)
                        ->where($whereFillter)
                        ->where('products.price', '>' , 0)
                        ->where('products.type', 1)
                        ->where('products.status', 'active')
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.type','products.image','products.original_price','products.price')
                        ->paginate(30);

                }

                break;
            case 'optical':
                $request = Brand::join('products', 'brands.id' , '=' , 'products.brand_id')->where('products.type', 2)->select('brands.*')->orderBy('brands.id', 'desc')->first();

                $filters = array(
                    [
                        'type' => 'select',
                        'name' => 'country',
                        'title' => 'کشور سازنده',
                        'value' => country::orderBy('title', 'ASC')->get()
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
                    ]
                );


                if($request){

                    $product = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
                        ->leftJoin('brands', 'brands.id' , '=' , 'products.brand_id')
                        ->where($whereFillter)
                        ->where('products.price', '>' , 0)
                        ->where('products.type', 2)
                        ->where('products.status', 'active')
                        ->orderBy('products.created_at', 'desc')
                        ->select('products.id','products.sku','products.type','products.image','products.original_price','products.price')
                        ->paginate(30);

                }

                break;
        }

        return view('site/brand' , [ 'product' => $product, 'brand' => $request, 'filters' => $filters ]);

    }

    public function AddToFav(){

        $product_id = $this->request->get('product_id');
        if(!$product_id && !is_numeric($product_id)){
            return false;
        }

        $request = Product::where('id' , $product_id)->first();
        if(!$request) return false;

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
                    $newFavorite->user_id = $UserLogin->id;
                    $newFavorite->status = 'active';
                    $newFavorite->save();
                }
                return true;
                break;
            case 'remove':
                $requestFavorite = Favorite::where('product_id' , $product_id)->where('user_id' , $UserLogin->id)->first();
                if($requestFavorite){
                    $requestFavorite->delete();
                    return true;
                }
                return false;
                break;
            default:
                return false;
                break;
        }

    }

    public function post(){

        //get user id
        $slug = $this->request->slug;

        //check user validate
        $request = post::where('slug' , $slug)->orderBy('id', 'desc')->first();
        if(!$request) return App::abort(404);

        return view('site/post', ['request' => $request]);
    }

    public function checkingAccount(){

        if(!Auth::check()){
            return redirect("/login");
        }

        if(Auth::user()->status != "not_active"){
            return redirect("/dashboard");
        }

        return view('site/checking_account');
    }

    public function userProfile(){

        if(!Auth::check()){
            return redirect("/login");
        }

        if(Auth::user()->status == "inactive"){
            Auth::logout();
            return redirect("/login")->with('error' , 'حساب کاربری شما غیر فعال می باشد.');
        }

        $province = province::orderBy('name', 'asc')->get();
        $user = Auth::user();

        return view('site/user/profile' , [ 'request' => $user , 'province' => $province ]);
    }

    public function actionUserProfile(){

        if(!Auth::check()){
            return redirect("/login");
        }

        if(Auth::user()->status == "inactive"){
            Auth::logout();
            return redirect("/login")->with('error' , 'حساب کاربری شما غیر فعال می باشد.');
        }

        $user = Auth::user();

        $id = $user->id;

        //check user validate
        $request = User::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('/')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
            'email' => 'required',
            'phone' => 'required|numeric',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'passage' => 'nullable',
        ]);

        if($this->request->get('city') == 'تهران'){
            $ValidData_area = $this->validate($this->request,[
                'area' => 'required',
            ]);
            $request->area = $ValidData_area['area'];
        }

        if($this->request->get('email') != $request->email){
            $ValidData_email = $this->validate($this->request,[
                'email' => 'required|string|email|max:255|unique:users',
            ]);
            $request->email = $ValidData_email['email'];
        }

        if($this->request->get('password')){
            $ValidData_password = $this->validate($this->request,[
                'old_password' => 'string|min:6',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $check = Hash::check($ValidData_password['old_password'], $request->password);

            if( !$check ) return redirect('/')->with('error' ,  'رمز عبور اشثباه است');

            $request->password = bcrypt($ValidData_password['password']);
        }

        $request->name = $ValidData['name'];
        $request->phone = $this->request->get('phone');
        $request->state = $this->request->get('state');
        $request->city = $this->request->get('city');
        $request->address = $this->request->get('address');
        $request->passage = $this->request->get('passage');
        $request->save();

        return redirect('user/profile')->with('success' ,  'اطلاعات حساب کاربری بروز رسانی شد.')->withInput();
    }

    public function cityList(){

        if (!$this->request->ajax()) {
            return redirect('/');
        }

        $province = $this->request->province;
        if(!$province){
            return response()->json([
                'status' => 'error',
                'error' => 'لطفا کد استان را وارد کنید.'
            ]);
        }

        $province_id = province::where('name' , $province)->orderBy('name', 'asc')->first();
        if(!$province_id) return response()->json([
            'status' => 'error',
            'error' => 'لطفا کد استان را وارد کنید.'
        ]);

        $request = city::where('province_id' , $province_id->id)->orderBy('name', 'asc')->select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                $request
            ]
        ]);

    }


}

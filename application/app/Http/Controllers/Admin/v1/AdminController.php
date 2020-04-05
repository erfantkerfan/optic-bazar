<?php

namespace App\Http\Controllers\Admin\v1;

use App\Brand;
use App\Http\Controllers\SettingController;
use App\labratorService;
use App\message;
use App\messageReply;
use App\Order;
use App\Order_detail;
use App\post;
use App\Prescription;
use App\Product;
use App\province;
use App\SendSMS;
use App\slider;
use App\transaction;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('admin');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }

    public function index(){

        $users = User::where('role' , 'user')->count();

        return redirect('cp-manager/dashboard');
    }

    public function dashboard(){
        return view('admin/index');
    }

    //User Manager Controller

    public function users(){
        $user = Auth::user();
        $where_array = array();

        //filter set to query
        $filter_name = trim($this->request->get('filter_name'));
        $filter_mobile = trim($this->request->get('filter_mobile'));
        $filter_email = trim($this->request->get('filter_email'));
        $filter_status = trim($this->request->get('filter_status'));
        $filter_role = trim($this->request->get('filter_role'));
        if($filter_name){
            $where_array[] = array('name', "LIKE" , "%" . $filter_name . "%");
        }
        if($filter_mobile){
            $where_array[] = array('mobile', "LIKE" , "%" . $filter_mobile . "%");
        }
        if($filter_email){
            $where_array[] = array('email', "LIKE" , "%" . $filter_email . "%");
        }
        if($filter_status){
            $where_array[] = array('status', $filter_status);
        }
        if($filter_role){
            $where_array[] = array('role', $filter_role);
        }


        //get data query
        //$users = User::where('id', '!=', $user->id)->where($where_array)->orderBy('email', 'desc')->paginate(35);
        $users = User::where($where_array)->orderBy('email', 'desc')->paginate(35);

        //view data
        return view('admin/users/index', ['request' => $users]);
    }

    public function userAdd(){

        $province = province::orderBy('name', 'asc')->get();
        return view('admin/users/add' , [ 'province' => $province ]);
    }
    public function ActionUserAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric|unique:users|regex:/(0)[0-9]{10}/',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $ValidData['name'],
            'email' => $ValidData['email'],
            'mobile' => $ValidData['mobile'],
            'role' => $ValidData['role'],
            'area' => $this->request->get('area'),
            'phone' => $this->request->get('phone'),
            'state' => $this->request->get('state'),
            'city' => $this->request->get('city'),
            'address' => $this->request->get('address'),
            'passage' => $this->request->get('passage'),
            'password' => bcrypt($ValidData['password']),
        ]);


        return redirect('cp-manager/users')->with('success' ,  'این کاربر به لیست کاربران شما اضافه شد.')->withInput();

    }

    public function userEdit(){

        //get user id
        $id = $this->request->user;

        //check user validate
        $request = User::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/users')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $province = province::orderBy('name', 'asc')->get();

        return view('admin/users/edit', ['request' => $request , 'province' => $province]);
    }
    public function ActionUserEdit(){

        //get user id
        $id = $this->request->user;

        //check user validate
        $request = User::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/users')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'status' => 'required|string',
            'credit' => 'required|numeric',
        ]);

        if($this->request->get('email') != $request->email){
            $ValidData_email = $this->validate($this->request,[
                'email' => 'required|string|email|max:255|unique:users',
            ]);
            $request->email = $ValidData_email['email'];
        }

        if($this->request->get('mobile') != $request->mobile){
            $ValidData_mobile = $this->validate($this->request,[
                'mobile' => 'required|numeric|unique:users|regex:/(0)[0-9]{10}/',
            ]);
            $request->mobile = $ValidData_mobile['mobile'];
        }

        if($this->request->get('password')){
            $ValidData_password = $this->validate($this->request,[
                'password' => 'string|min:6',
            ]);
            $request->password = bcrypt($ValidData_password['password']);
        }

        if($this->request->get('credit') > $request->credit){

            $new_credit = $this->request->get('credit') - $request->credit;


            $sku = str_random(20);
            $newTransaction = new transaction;
            $newTransaction->user_id = $id;
            $newTransaction->price = $new_credit;
            $newTransaction->type = 'charge';
            $newTransaction->key = $sku;
            $newTransaction->payment_method = 'admin';
            $newTransaction->tracking_code = '0';
            $newTransaction->status = 'paid';
            $newTransaction->posting_status = '';
            $newTransaction->description = 'افزایش اعتبار توسط ادمین';
            $newTransaction->save();


        }else if($this->request->get('credit') < $request->credit){

            $new_credit = $this->request->get('credit') - $request->credit;


            $sku = str_random(20);
            $newTransaction = new transaction;
            $newTransaction->user_id = $id;
            $newTransaction->price = $new_credit;
            $newTransaction->type = 'charge';
            $newTransaction->key = $sku;
            $newTransaction->payment_method = 'admin';
            $newTransaction->tracking_code = '0';
            $newTransaction->status = 'paid';
            $newTransaction->posting_status = '';
            $newTransaction->description = '** کاهش اعتبار توسط ادمین';
            $newTransaction->save();

        }

        $request->name = $ValidData['name'];
        $request->role = $ValidData['role'];
        $request->status = $ValidData['status'];
        $request->area = $this->request->get('area');
        $request->phone = $this->request->get('phone');
        $request->state = $this->request->get('state');
        $request->city = $this->request->get('city');
        $request->address = $this->request->get('address');
        $request->passage = $this->request->get('passage');
        $request->credit = $this->request->get('credit');
        $request->save();


        return redirect('cp-manager/users')->with('success' ,  'اطلاعات این کاربر بروز رسانی شد.')->withInput();

    }

    public function userDelete(){

        //get user id
        $id = $this->request->user;

        //check user validate
        $request = User::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/users')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $request->delete();

        return back()->with('success' ,  'این کاربر با موفقیت حذف شد.')->withInput();
    }


    //Brands Manager Controller

    public function brands(){
        $where_array = array();

        //filter set to query
        $filter_name = trim($this->request->get('filter_name'));
        if($filter_name){
            $where_array[] = array('name', "LIKE" , "%" . $filter_name . "%");
        }


        //get data query
        $request = Brand::where($where_array)->orderBy('name', 'desc')->paginate(35);

        //view data
        return view('admin/brands/index', ['request' => $request]);
    }

    public function brandAdd(){
        return view('admin/brands/add');
    }
    public function ActionBrandAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
            'logo' => 'required',
            'profit' => 'required',
            'description' => 'string',
        ]);


        $image_logo = $this->request->get('logo');
        if($image_logo){
            $slim = str_replace(chr(92), '', $image_logo);
            $slim = json_decode($slim);
            $file = $slim->output->image;
            $filename = str_random(15) . '.jpg';
            Image::make($file)->save($this->fileFinalPath('/'). $filename);
            $image_logo = url('uploads/'.$filename);
        }


        Brand::create([
            'name' => $ValidData['name'],
            'logo' => $image_logo,
            'description' => $ValidData['description'],
            'profit' => $ValidData['profit'],
        ]);

        return redirect('cp-manager/brands')->with('success' ,  'این برند به لیست برندهای شما اضافه شد.')->withInput();

    }

    public function brandEdit(){

        //get user id
        $id = $this->request->brand;

        //check user validate
        $request = Brand::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/brands')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        return view('admin/brands/edit', ['request' => $request]);
    }
    public function ActionBrandEdit(){

        //get user id
        $id = $this->request->brand;

        //check user validate
        $request = Brand::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/brands')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
            'description' => 'string',
            'profit' => 'required',
        ]);

        if($this->request->get('logo') != $request->logo){
            $ValidData_logo = $this->validate($this->request,[
                'logo' => 'required',
            ]);

            $image_logo = $this->request->get('logo');
            if($image_logo){
                $slim = str_replace(chr(92), '', $image_logo);
                $slim = json_decode($slim);
                $file = $slim->output->image;
                $filename = str_random(15) . '.jpg';
                Image::make($file)->save($this->fileFinalPath('/'). $filename);
                $image_logo = url('uploads/'.$filename);
            }

            $request->logo = $image_logo;
        }

        $request->name = $ValidData['name'];
        $request->profit = $ValidData['profit'];
        $request->description = $ValidData['description'];
        $request->save();


        return redirect('cp-manager/brands')->with('success' ,  'اطلاعات این برند بروز رسانی شد.')->withInput();

    }

    public function brandDelete(){

        //get user id
        $id = $this->request->brand;

        //check user validate
        $request = Brand::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/brands')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $request->delete();

        return back()->with('success' ,  'این برند با موفقیت حذف شد.')->withInput();
    }

    public function sliders(){
        //get data query
        $request = slider::orderBy('name', 'desc')->paginate(35);

        //view data
        return view('admin/slider/index', ['request' => $request]);
    }

    public function sliderAdd(){
        return view('admin/slider/add');
    }
    public function ActionSliderAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
        ]);

        $image_url = '';
        $video_url = $this->request->get('video');
        $image_image = $this->request->get('image');
        if($image_image){
            $slim = str_replace(chr(92), '', $image_image);
            $slim = json_decode($slim);
            $file = $slim->output->image;
            $filename = str_random(15) . '.jpg';

            Image::make($file)->save($this->fileFinalPath('/sliders/'). $filename);
            $image_url = url('uploads/sliders/'.$filename);
        }

        slider::create([
            'name' => $ValidData['name'],
            'image' => $image_url,
            'video' => $video_url,
            'description' => $this->request->get('description'),
            'link' => $this->request->get('link'),
        ]);

        return redirect('cp-manager/sliders')->with('success' ,  'این اسلاید به لیست اسلایدر های شما اضافه شد.')->withInput();

    }

    public function sliderEdit(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = slider::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/sliders')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        return view('admin/slider/edit', ['request' => $request]);
    }
    public function ActionSliderEdit(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = slider::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/sliders')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'name' => 'required|string|max:255',
            'image' => 'required',
        ]);

        $video_url = $this->request->get('video');
        if($this->request->get('logo') != $request->logo){
            $ValidData_logo = $this->validate($this->request,[
                'logo' => 'required',
            ]);

            $image_logo = $this->request->get('logo');
            if($image_logo){
                $slim = str_replace(chr(92), '', $image_logo);
                $slim = json_decode($slim);
                $file = $slim->output->image;
                $filename = str_random(15) . '.jpg';
                Image::make($file)->save($this->fileFinalPath('/'). $filename);
                $image_logo = url('uploads/'.$filename);
            }

            $request->image = $image_logo;
        }

        $request->video = $video_url;
        $request->name = $ValidData['name'];
        $request->description = $this->request->get('description');
        $request->link = $this->request->get('link');
        $request->save();


        return redirect('cp-manager/sliders')->with('success' ,  'اطلاعات این اسلایذ بروز رسانی شد.')->withInput();

    }

    public function sliderDelete(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = slider::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/sliders')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $request->delete();

        return back()->with('success' ,  'این اسلایذ با موفقیت حذف شد.')->withInput();
    }


    public function orders(){
        $where_array = array();
        $where_to_array = array();
        $userLogin = Auth::user();

        //filter set to query

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('transactions.created_at', '>=', $date);
        }

        $filter_end_date = $this->request->get('filter_end_date');
        if($filter_end_date){

            $date = explode('/', $filter_end_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('transactions.created_at', '<=', $date);
        }

        $filter_code = $this->request->get('filter_code');
        if($filter_code){

            $code = explode('-', $filter_code);
            if(isset($code[1]) && !empty($code[1])){
                $where_array[] = array('transactions.id', $code[1]);
            }else{
                $where_array[] = array('transactions.id', 0);
            }
        }

        $filter_amel = $this->request->get('filter_amel');
        if($filter_amel){
            $filter_amel = ($filter_amel == 'bazar') ? 0 : $filter_amel;
            $where_array[] = array('orders.lathe_id', $filter_amel);
            $where_to_array[] = array('orders.lathe_id', $filter_amel);
        }

        $filter_bonakdars = $this->request->get('filter_bonakdars');
        if($filter_bonakdars){
            $filter_bonakdars = ($filter_bonakdars == 'bazar') ? 0 : $filter_bonakdars;
            $where_array[] = array('products.seller_id', $filter_bonakdars);
            $where_to_array[] = array('products.seller_id', $filter_bonakdars);
        }

        $filter_name = $this->request->get('filter_name');
        if($filter_name){
            $where_array[] = array('users.name', 'LIKE', '%'.$filter_name.'%');
        }

        $filter_product = $this->request->get('filter_product');
        if($filter_product){
            $where_array[] = array('products.sku', 'LIKE', '%'.$filter_product.'%');
            $where_to_array[] = array('products.sku', 'LIKE', '%'.$filter_product.'%');
        }

        $filter_brand = $this->request->get('filter_brand');
        if($filter_brand){
            $brand = Brand::where('name' , 'LIKE', '%'.$filter_brand.'%')->first();
            if($brand){
                $where_to_array[] = array('products.brand_id', $brand->id);
                $where_array[] = array('products.brand_id', $brand->id);
            }else{
                $where_array[] = array('products.brand_id', 0);
                $where_to_array[] = array('products.brand_id', 0);
            }
        }

        $filter_status = $this->request->get('filter_status');
        if($filter_status){
            $filter_status = ($filter_status == '100') ? 0 : $filter_status;
            $where_to_array[] = array('orders.status_operator', $filter_status);
            $where_array[] = array('orders.status_operator', $filter_status);
        }


        $order_id = [];
        $filter_labrators = $this->request->get('filter_labrators');
        if($filter_labrators){

            $order_service = Order_detail::where('val', $filter_labrators)->where('key', 'LIKE' ,'%order_service%%labrator%')->distinct('order_id')->get();
            if($order_service){
                foreach ($order_service as $service) {
                    $order_id[] = $service['order_id'];
                }
            }else{
                $order_id[] = 0;
            }

        }


        if($order_id){

            //get data query
            $request = transaction::join('users', 'transactions.user_id' , '=' , 'users.id')
                ->join('order_details', 'order_details.val' , '=', 'transactions.key' )
                ->join('orders', 'orders.id' , '=', 'order_details.order_id' )
                ->join('products', 'products.id' , '=', 'orders.product_id' )
                ->whereIn('orders.id' , $order_id)
                ->where('orders.status' , 'paid')
                ->where('transactions.type' , 'order')
                ->where('transactions.status' , '!=' ,  'cancel')
                ->where($where_array)
                ->select(
                    'transactions.*',
                    'users.name as user_name',
                    'users.mobile as user_mobile',
                    'users.email as user_email'
                )
                ->orderBy('transactions.created_at', 'desc')
                ->distinct('transactions.id')
                ->paginate(20);

        }else{

            //get data query
            $request = transaction::join('users', 'transactions.user_id' , '=' , 'users.id')
                ->join('order_details', 'order_details.val' , '=', 'transactions.key' )
                ->join('orders', 'orders.id' , '=', 'order_details.order_id' )
                ->join('products', 'products.id' , '=', 'orders.product_id' )
                ->where('orders.status' , 'paid')
                ->where('transactions.type' , 'order')
                ->where('transactions.status' , '!=' ,  'cancel')
                ->where($where_array)
                ->select(
                    'transactions.*',
                    'users.name as user_name',
                    'users.mobile as user_mobile',
                    'users.email as user_email'
                )
                ->orderBy('transactions.created_at', 'desc')
                ->distinct('transactions.id')
                ->paginate(20);

        }

        $fullCart = array();
        $orderData = array();
        $labrator = [];
        $full_labrator_price = [];


        if($request){
            foreach ($request as $trans){

                $order_detail_list = [];
                $order_details = Order_detail::where('key' , 'thank_you_key')->where('val' , $trans->key)->get();
                if($order_details){
                    foreach ($order_details as $order_detail){
                        $order_detail_list[] = $order_detail['order_id'];
                    }

                    $order = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                        ->join('products', 'products.id' , '=', 'orders.product_id' )
                        ->whereIN('orders.id' , $order_detail_list)
                        ->where('orders.status' , 'paid')
                        ->where($where_to_array)
                        ->select(
                            'prescriptions.*',
                            'products.type as product_type',
                            'products.sku as product_sku',
                            'products.seller_id as seller_id',
                            'orders.created_at as created',
                            'orders.price as price',
                            'orders.lathe_id as lathe_id',
                            'orders.lathe as lathe',
                            'orders.paid_amount_shop',
                            'orders.paid_amount_labrator',
                            'orders.paid_amount_bonakdar',
                            'orders.send_box_time',
                            'orders.get_box_time',
                            'orders.type_shipping',
                            'orders.order_key as key',
                            'orders.status_operator as status_operator',
                            'orders.id as order_id'
                        )->get();

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
                            $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%labrator%')->first();
                            if($order_service){
                                $labrator[$v['order_id']] = $order_service->val;
                            }

                            $fullCart[$trans->id][$v['order_id']] = $total;
                            $full_labrator_price[$trans->id][$v['order_id']] = $labrator_price;
                            $orderData[$trans->id][] = $v;
                        }
                    }

                }

            }
        }

        $labrators = User::where('role', 'labrator')->where('status', 'active')->get();
        $amels = User::where('role', 'amel')->where('status', 'active')->get();
        $bonakdars = User::where('role', 'bonakdar')->where('status', 'active')->get();

        //view data
        return view('admin/orders/index', ['request' => $request, 'orderData' => $orderData, 'fullTotal' => $fullCart, 'full_labrator_price' => $full_labrator_price, 'labrator' => $labrator
            , 'labrators' => $labrators, 'amels' => $amels, 'bonakdars' => $bonakdars]);
    }
    public function orderShow(){

        //get id
        $key = $this->request->key;

        //check user validate
        $request = transaction::where('key' , $key)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');



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
                ->where('orders.user_id' , $request->user_id)
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
            if($order){
                foreach ($order as $v){
                    $total = $v['price'];

                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                    if($order_service){
                        foreach ($order_service as $service) {
                            $total = $total + $service['val'];
                        }
                    }

                    $fullCart[$v['order_id']] = $total;
                }
            }

        }



        $User = User::where('id' , $request->user_id)->orderBy('id', 'desc')->first();

        //view data
        return view('admin/orders/edit', ['request' => $request, 'User' => $User, 'order' => $order, 'fullTotal' => $fullCart]);
    }

    public function orderPrint(){
        $where_array = array();
        $userLogin = Auth::user();
        $key = $this->request->key;

        //get data query
        $order_detail_list = [];
        $fullCart = [];
        $UserLogin = [];
        $labrator = [];
        $amel = [];
        $bonakdar = [];
        $labrators = User::where('role', 'labrator')->where('status', 'active')->get();
        $amels = User::where('role', 'amel')->where('status', 'active')->get();
        $bonakdars = User::where('role', 'bonakdar')->where('status', 'active')->get();

        $order_details = Order_detail::where('key' , 'thank_you_key')->where('val' , $key)->get();
        if($order_details){
            foreach ($order_details as $order_detail){
                $order_detail_list[] = $order_detail['order_id'];
            }

            $order = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                ->join('products', 'products.id' , '=', 'orders.product_id' )
                ->whereIN('orders.id' , $order_detail_list)
                ->where('orders.status' , 'paid')
                ->select(
                    'prescriptions.*',
                    'products.type as product_type',
                    'products.sku as product_sku',
                    'products.brand_id as brand_id',
                    'products.seller_id as seller',
                    'orders.created_at as created',
                    'orders.price as price',
                    'orders.lathe_id as lathe_id',
                    'orders.order_key as key',
                    'orders.id as order_id',
                    'orders.lathe as lathe',
                    'orders.send_box_time',
                    'orders.get_box_time',
                    'orders.get_box_date',
                    'orders.send_box_date',
                    'orders.type_shipping',
                    'orders.user_id'
                )->get();

            if($order){
                foreach ($order as $v){
                    $total = $v['price'];

                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                    if($order_service){
                        foreach ($order_service as $service) {
                            $total = $total + $service['val'];
                        }
                    }
                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%labrator%')->first();
                    if($order_service){

                        if($labrators){
                            foreach ($labrators as $item){
                                if($order_service->val == $item->id){
                                    $labrator[$v['order_id']] = $item->name;
                                }
                            }
                        }
                    }

                    $fullCart[$v['order_id']] = $total;
                }

                $UserLogin = User::where('id', $order[0]['user_id'])->first();

            }

        }

        $trans = transaction::where('key', $key)->first();

        if($order){
            //view data
            return view('admin/orders/print', ['UserLogin' => $UserLogin, 'orderData' => $order, 'fullTotal' => $fullCart, 'labrator' => $labrator, 'trans' => $trans]);
        }else{
            return back();
        }
    }

    public function statusUpdate(){
        $where_array = array();
        $userLogin = Auth::user();

        $id  = $this->request->get('id');
        $value  = $this->request->get('value');

        //get data query
        $request = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'paid')
            ->where('orders.id' , $id)
            ->where($where_array)
            ->select(
                'orders.*'
            )
            ->orderBy('orders.created_at', 'desc')
            ->distinct('orders.id')
            ->first();


        if($request){

            $orderKey = Order_detail::where('key', 'thank_you_key')->where('order_id', $request->id)->first();
            if($orderKey){
                $trans = transaction::where('key', $orderKey->val)->first();
                if($trans){
                    if($value == '0' || $value == '2'){

                        if($request->checkout_amel === 2){
                            $amount = $request->paid_amount_labrator;
                            $amel = $request->labrator_id;

                            if($amel) {
                                $sku = str_random(20);
                                $newTransaction = new transaction;
                                $newTransaction->user_id = $amel;
                                $newTransaction->price = -$amount;
                                $newTransaction->type = 'charge';
                                $newTransaction->key = $sku;
                                $newTransaction->payment_method = 'admin';
                                $newTransaction->tracking_code = '0';
                                $newTransaction->status = 'paid';
                                $newTransaction->posting_status = '';
                                $newTransaction->description = '** کاهش اعتبار عامل به دلیل بازگشت وضعیت سفارش ' . $id;
                                $newTransaction->save();

                                $request->checkout_amel = 0;

                                $user_st = User::where('id', $amel)->first();
                                if($user_st){
                                    $user_st->credit = $amount - $user_st->credit;
                                    $user_st->save();
                                }

                            }

                        }

                        if($request->checkout_bonakdar === 2){

                            $amount = $request->paid_amount_bonakdar;
                            $bonakdar = 0;
                            $product = Product::where('id', $request->product_id)->first();
                            if($product){
                                $bonakdar = $product->seller_id;
                            }

                            if($bonakdar){

                                $sku = str_random(20);
                                $newTransaction = new transaction;
                                $newTransaction->user_id = $bonakdar;
                                $newTransaction->price = -$amount;
                                $newTransaction->type = 'charge';
                                $newTransaction->key = $sku;
                                $newTransaction->payment_method = 'admin';
                                $newTransaction->tracking_code = '0';
                                $newTransaction->status = 'paid';
                                $newTransaction->posting_status = '';
                                $newTransaction->description = '** کاهش اعتبار بنکدار به دلیل بازگشت وضعیت سفارش ' . $id;
                                $newTransaction->save();

                                $request->checkout_bonakdar = 0;

                                $user_st = User::where('id', $bonakdar)->first();
                                if($user_st){
                                    $user_st->credit = $amount - $user_st->credit;
                                    $user_st->save();
                                }

                            }

                        }

                        $request->checkout_labrator = 0;

                        $trans->posting_status = 'pending';
                    }
                    else{


                        if(( (int) $value ) >= 1) {

                            if ($request->checkout_amel === 0) {
                                $amount = $request->paid_amount_labrator;
                                $amel = $request->labrator_id;

                                if($amel){

                                    $sku = str_random(20);
                                    $newTransaction = new transaction;
                                    $newTransaction->user_id = $amel;
                                    $newTransaction->price = $amount;
                                    $newTransaction->type = 'charge';
                                    $newTransaction->key = $sku;
                                    $newTransaction->payment_method = 'admin';
                                    $newTransaction->tracking_code = '0';
                                    $newTransaction->status = 'paid';
                                    $newTransaction->posting_status = '';
                                    $newTransaction->description = 'افزایش اعتبار عامل برای صورت حساب سفارش ' . $id;
                                    $newTransaction->save();

                                    $request->checkout_amel = 2;


                                    $user_st = User::where('id', $amel)->first();
                                    if($user_st){
                                        $user_st->credit = $amount + $user_st->credit;
                                        $user_st->save();
                                    }

                                }

                            }

                        }

                        if(( (int) $value ) >= 6) {
                            if ($request->checkout_bonakdar === 0) {

                                $amount = $request->paid_amount_bonakdar;
                                $bonakdar = 0;
                                $product = Product::where('id', $request->product_id)->first();
                                if ($product) {
                                    $bonakdar = $product->seller_id;
                                }

                                if($bonakdar) {
                                    $sku = str_random(20);
                                    $newTransaction = new transaction;
                                    $newTransaction->user_id = $bonakdar;
                                    $newTransaction->price = $amount;
                                    $newTransaction->type = 'charge';
                                    $newTransaction->key = $sku;
                                    $newTransaction->payment_method = 'admin';
                                    $newTransaction->tracking_code = '0';
                                    $newTransaction->status = 'paid';
                                    $newTransaction->posting_status = '';
                                    $newTransaction->description = 'افزایش اعتبار بنکدار برای صورت حساب سفارش ' . $id;
                                    $newTransaction->save();

                                    $request->checkout_bonakdar = 2;


                                    $user_st = User::where('id', $bonakdar)->first();
                                    if($user_st){
                                        $user_st->credit = $amount + $user_st->credit;
                                        $user_st->save();
                                    }

                                }

                            }
                        }

                        $trans->posting_status = 'dont_cancel';
                    }
                    $trans->save();
                }
            }

            $request->status_operator = $value;
            $request->save();

            $user = User::where('id', $request->user_id)->first();
            if($user){
                if($user->mobile){
                    $mobile = $user->mobile;
                    $status = '';
                    switch ($value){
                        case '0':
                            $status = 'بررسی';
                            break;
                        case '2':
                            $status = 'انبار';
                            break;
                        case '1':
                            $status = 'ارسال-‌برای-‌لابراتو';
                            break;
                        case '4':
                            $status = 'تحویل-‌به-‌پیک';
                            break;
                        case '5':
                            $status = 'اماده-ارسال';
                            break;
                        case '6':
                            $status = 'بسته‌-شده';
                            break;
                    }

                    SendSMS::sendOrder($mobile , $request->id, $status);
                }
            }
            //SendSMS::sendOrder();

            return 'success';
        }

        return 'error';
    }

    public function orderAmelUpdate(){
        $where_array = array();
        $userLogin = Auth::user();

        $id  = $this->request->get('id');
        $value  = $this->request->get('value');

        //get data query
        $request = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'paid')
            ->where('orders.id' , $id)
            ->where($where_array)
            ->select(
                'orders.*'
            )
            ->orderBy('orders.created_at', 'desc')
            ->distinct('orders.id')
            ->first();


        if($request){
            $request->lathe_id = $value;
            $request->save();

            return User::where('id', $value)->where('role', 'amel')->first();
        }

        return 'error';
    }

    public function orderLabratorUpdate(){
        $where_array = array();
        $userLogin = Auth::user();

        $id  = $this->request->get('id');
        $value  = $this->request->get('value');

        //get data query
        $request = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'paid')
            ->where('orders.id' , $id)
            ->where($where_array)
            ->select(
                'orders.*'
            )
            ->orderBy('orders.created_at', 'desc')
            ->distinct('orders.id')
            ->first();


        if($request){

            $order_service = Order_detail::where('order_id', $request->id)->where('key', 'LIKE' ,'%order_service%%labrator%')->get();
            if($order_service){
                foreach ($order_service as $s){
                    $s->val = $value;
                    $s->save();
                }

                $request->labrator_id = $value;
                $request->save();


                return User::where('id', $value)->where('role', 'labrator')->first();
            }
        }

        return 'error';
    }

    public function orderEditPrescriptions(){
        $where_array = array();
        $userLogin = Auth::user();

        $id  = $this->request->id;
        $data = $this->request->get('json');

        if(!$data){
            return response()->json([
                'staus' => 'error',
                'error' => 'لطفا اطلاعات را وارد کنید.'
            ]);
        }

        $data = json_decode($data);

        if($data->rcount <= 0 && $data->lcount <= 0){
            return response()->json([
                'staus' => 'error',
                'error' => 'تعداد نباید کمتر از یک باشد.'
            ]);
        }

        //get data query
        $request = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('orders.status' , 'paid')
            ->where('orders.id' , $id)
            ->where($where_array)
            ->select(
                'orders.*'
            )
            ->orderBy('orders.created_at', 'desc')
            ->distinct('orders.id')
            ->first();


        if($request){

            $editSearchValidate = Prescription::where('id', $request->prescription_id)->first();
            if(!$editSearchValidate) return response()->json([
                'staus' => 'error',
                'error' => 'ویرایش امکان پذیر نیست.'
            ]);

            $newPrescription = $editSearchValidate;

            $newPrescription->rcount = $data->rcount;
            $newPrescription->lcount = $data->lcount;
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

            $newPrescription->prisma_rprisma1 = isset($data->rprisma1) ? $data->rprisma1 : "";
            $newPrescription->prisma_lprisma1 = isset($data->lprisma1) ? $data->lprisma1 : "";
            $newPrescription->prisma_rdegrees1 = isset($data->rdegrees1) ? $data->rdegrees1 : "";
            $newPrescription->prisma_ldegrees1 = isset($data->ldegrees1) ? $data->ldegrees1 : "";
            $newPrescription->prisma_rprisma2 = isset($data->rprisma2) ? $data->rprisma2 : "";
            $newPrescription->prisma_lprisma2 = isset($data->lprisma2) ? $data->lprisma2 : "";
            $newPrescription->prisma_rdegrees2 = isset($data->rdegrees2) ? $data->rdegrees2 : "";
            $newPrescription->prisma_ldegrees2 = isset($data->ldegrees2) ? $data->ldegrees2 : "";


            if($newPrescription->save()){

                return response()->json([
                    'staus' => 'success'
                ]);

            }

        }

        return response()->json([
            'staus' => 'error',
            'error' => 'خطا در ذخیره سازی'
        ]);
    }


    public function posts(){
        //get data query
        $request = post::orderBy('title', 'desc')->paginate(35);

        //view data
        return view('admin/posts/index', ['request' => $request]);
    }

    public function postAdd(){
        return view('admin/posts/add');
    }
    public function ActionPostAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);


        $slug = $this->request->get('slug');
        if(!$slug) $slug = $ValidData['title'];

        $slug = $this->slugify($slug);

        $slug_check = post::where('slug' , $slug)->orderBy('id', 'desc')->first();
        if($slug_check) $slug = $slug . '-' . str_random(4);

        $image = '';
        $image_image = $this->request->get('image');
        if($image_image){
            $slim = str_replace(chr(92), '', $image_image);
            $slim = json_decode($slim);
            $file = $slim->output->image;
            $filename = str_random(15) . '.jpg';

            Image::make($file)->save($this->fileFinalPath('/'). $filename);
            $image = url('uploads/'.$filename);
        }

        post::create([
            'title' => $ValidData['title'],
            'content' => $ValidData['content'],
            'slug' => $slug,
            'location' => $this->request->get('location'),
            'image' => $image,
        ]);

        return redirect('cp-manager/posts')->with('success' ,  'این برگه به لیست برگه های شما اضافه شد.')->withInput();

    }

    public function postEdit(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = post::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/posts')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        return view('admin/posts/edit', ['request' => $request]);
    }
    public function ActionPostEdit(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = post::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/posts')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');


        // Validation Data
        $ValidData = $this->validate($this->request,[
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);


        $slug = $this->request->get('slug');
        if(!$slug) $slug = $ValidData['title'];

        $slug = $this->slugify($slug);

        if($slug != $request->slug){
            $slug_check = post::where('slug' , $slug)->orderBy('id', 'desc')->first();
            if($slug_check) $slug = $slug . '-' . str_random(4);


            $request->slug = $slug;
        }


        if($this->request->get('image') != $request->image){

            $image = '';
            $image_image = $this->request->get('image');
            if($image_image){
                $slim = str_replace(chr(92), '', $image_image);
                $slim = json_decode($slim);
                $file = $slim->output->image;
                $filename = str_random(15) . '.jpg';

                Image::make($file)->save($this->fileFinalPath('/'). $filename);
                $image = url('uploads/'.$filename);
            }

            $request->image = $image;
        }

        $request->title = $ValidData['title'];
        $request->content = $ValidData['content'];
        $request->location = $this->request->get('location');
        $request->save();


        return redirect('cp-manager/posts')->with('success' ,  'اطلاعات این برگه بروز رسانی شد.')->withInput();

    }

    public function postDelete(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = post::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/posts')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $request->delete();

        return back()->with('success' ,  'این برگه با موفقیت حذف شد.')->withInput();
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function setting(){
        return view('admin/setting/edit');
    }
    public function ActionSetting(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'facebook' => 'required',
            'instagram' => 'required',
            'linkedin' => 'required',
            'title_about' => 'required',
            'text_about' => 'required',
            'title_baner_order' => 'required',
            'text_baner_order' => 'required',
        ]);


        SettingController::update_package_optien('title_about', $ValidData['title_about']);
        SettingController::update_package_optien('text_about', $ValidData['text_about']);
        SettingController::update_package_optien('title_baner_order', $ValidData['title_baner_order']);
        SettingController::update_package_optien('text_baner_order', $ValidData['text_baner_order']);
        SettingController::update_package_optien('email', $ValidData['email']);
        SettingController::update_package_optien('phone', $ValidData['phone']);
        SettingController::update_package_optien('facebook', $ValidData['facebook']);
        SettingController::update_package_optien('instagram', $ValidData['instagram']);
        SettingController::update_package_optien('linkedin', $ValidData['linkedin']);



        return redirect('cp-manager/setting')->with('success' ,  'اطلاعات تنظیمات بروز رسانی شد.')->withInput();

    }


    public function messages(){
        //get data query
        $request = message::orderBy('created_at', 'desc')->paginate(35);

        //view data
        return view('admin/messages/index', ['request' => $request]);
    }

    public function messageAdd(){
        $user = Auth::user();
        $userList = User::where('id', '!=', $user->id)->orderBy('name', 'desc')->get();

        return view('admin/messages/add', ['userList' => $userList]);
    }
    public function ActionMessageAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'user_id' => 'required',
            'content' => 'required',
        ]);

        $user = Auth::user();

        message::create([
            'send_id' => $user->id,
            'user_id' => $ValidData['user_id'],
            'message' => $ValidData['content'],
            'status' => 'pending',
        ]);

        return redirect('cp-manager/messages')->with('success' ,  'پیام با موفقیت ارسال شد.')->withInput();

    }

    public function messageReplies(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = message::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/messages')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $messageReply = messageReply::where('message_id' , $id)->orderBy('created_at', 'ASC')->get();

        $userSend = \App\user::where('id', $request->send_id)->first();
        if($userSend) $userSend = $userSend->name;

        $userGet = \App\user::where('id', $request->user_id)->first();
        if($userGet) $userGet = $userGet->name;

        $request->status = 'seen';
        $request->save();

        return view('admin/messages/replies', ['request' => $request, 'messageReply' => $messageReply, 'userSend' => $userSend, 'userGet' => $userGet]);
    }
    public function ActionMessageReplies(){

        //get user id
        $id = $this->request->id;
        $user = Auth::user();

        //check user validate
        $request = message::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/messages')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');


        // Validation Data
        $ValidData = $this->validate($this->request,[
            'content' => 'required',
        ]);

        $newMessageReply = new messageReply;
        $newMessageReply->message_id = $id;
        $newMessageReply->user_id = $user->id;
        $newMessageReply->message = $ValidData['content'];
        $newMessageReply->status = 'send';
        $newMessageReply->save();

        $request->status = 'no_reply';
        $request->save();


        return redirect('cp-manager/message/replies/' . $id)->with('success' ,  'پاسخ شما ارسال شد')->withInput();

    }

    public function messageDelete(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = message::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/messages')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $messageReply = messageReply::where('message_id' , $id)->orderBy('created_at', 'ASC')->get();

        if($messageReply) {
            foreach ($messageReply as $reply){
                $reply->delete();
            }
        }

        $request->delete();

        return back()->with('success' ,  'این پیام با موفقیت حذف شد.')->withInput();
    }

    public function services(){
        $user = Auth::user();
        $userList = User::where('id', '!=', $user->id)->where('role', 'labrator')->orderBy('name', 'desc')->paginate(35);

        return view('admin/services/index', ['request' => $userList]);
    }

    public function editService(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = User::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/services')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');


        $labratorService = labratorService::where('user_id' , $id)->orderBy('title', 'desc')->get();


        return view('admin/services/edit', ['request' => $request, 'labrator_service' => $labratorService]);
    }

    public function ActionEditService(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = User::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/services')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');




        $labratorServiceFull = labratorService::where('user_id' , $id)->orderBy('id', 'desc')->get();

        $price = $this->request->get('price');
        $sale_price = $this->request->get('sale_price');
        $title = $this->request->get('title');
        if(!is_array($title)) $title = [];

        if($title){

            foreach ($title as $key => $validDatum) {
                if($price[$key]){

                    $labratorService = labratorService::where('user_id' , $id)->where('title' , trim($validDatum))->orderBy('id', 'desc')->first();
                    if(!$labratorService){
                        $newService = new labratorService;
                        $newService->user_id = $id;
                        $newService->title = trim($validDatum);
                        $newService->price = $price[$key];
                        $newService->sale_price = $sale_price[$key];
                        $newService->save();
                    }else{

                        $coun = labratorService::where('user_id' , $id)->where('title' , trim($validDatum))->orderBy('id', 'desc')->get();
                        if(count($coun) > 1){
                            foreach ($coun as $item){

                                if($labratorService->id != $item['id']){
                                    $item->delete();
                                }

                            }
                        }

                        $labratorService->price = $price[$key];
                        $labratorService->sale_price = $sale_price[$key];
                        $labratorService->save();
                    }

                }
            }

        }


        if($labratorServiceFull){
            foreach ($labratorServiceFull as $Service){

                if( !in_array($Service['title'], $title) ){
                    $Service->delete();
                }

            }
        }



        return redirect('cp-manager/services')->with('success' ,  'خدمات با موفقیت ارسال شد.')->withInput();
    }

}

<?php

namespace App\Http\Controllers\Admin\v1;

use App\Order;
use App\Order_detail;
use App\transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillContoller extends Controller
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('admin');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }


    public function charge(){
        $where_array = array();

        //filter set to query

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('transactions.created_at', '>=', $date);
        }

        $filter_user = $this->request->get('filter_user');
        if($filter_user){

            $where_array[] = array('users.name', 'LIKE', "%".$filter_user."%");
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

                $where_array[] = array('transactions.transactions.id', $code[1]);

            }else{
                $where_array[] = array('transactions.transactions.id', $filter_code);
            }
        }


        //get data query

        $transaction = transaction::join('users', 'users.id' , '=', 'transactions.user_id' )
            ->orderBy('transactions.created_at', 'desc')
            ->select('transactions.*', 'users.name as user_name', 'users.id as user_key')
            ->where('transactions.type' , 'charge')
            ->where('transactions.status' , 'paid')
            ->where($where_array)
            ->paginate(20);


        //view data
        return view('admin/bill/charge', ['request' => $transaction]);

    }

    public function bonakdar(){
        $where_array = array();

        //filter set to query


        $filter_user = $this->request->get('filter_user');
        if($filter_user){

            $user = User::where('users.name', $filter_user)->first();
            if($user){
                $where_array[] = array('products.seller_id', $user->id);
            }else{
                $where_array[] = array('products.seller_id', '');
            }

        }

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '>=', $date);
        }

        $filter_end_date = $this->request->get('filter_end_date');
        if($filter_end_date){

            $date = explode('/', $filter_end_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '<=', $date);
        }

        $filter_checkout = $this->request->get('filter_checkout');
        if($filter_checkout){
            $where_array[] = array('orders.checkout_amel', $filter_checkout);
        }

        $filter_code = $this->request->get('filter_code');
        if($filter_code){

            $code = explode('-', $filter_code);
            if(isset($code[1]) && !empty($code[1])){

                $where_array[] = array('transactions.id', $code[1]);

                if(isset($code[2]) && !empty($code[2])) {
                    $where_array[] = array('orders.id', $code[2]);
                }

            }
        }


        //get data query

        $fullCart = [];
        $order = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->join('order_details', 'order_details.order_id', '=' , 'orders.id')
            ->join('transactions', 'transactions.key', '=' , 'order_details.val')
            ->join('users', 'users.id' , '=', 'transactions.user_id' )
            ->where('order_details.key' , 'thank_you_key')
            ->where('orders.status' , 'paid')
            ->where('transactions.status' , 'paid')
            ->where('orders.status_operator' , '>=' , 6)
            ->where('products.seller_id' , '!=' , 0)
            ->where($where_array)
            ->select(
                'products.type as product_type',
                'products.sku as product_sku',
                'products.seller_id as seller',
                'orders.created_at as created',
                'orders.paid_amount_bonakdar as price',
                'orders.lathe_id as lathe_id',
                'orders.order_key as key',
                'orders.id as order_id',
                'transactions.id as transactions_id',
                'transactions.created_at as transactions_created_at',
                'transactions.payment_method as transactions_payment_method',
                'transactions.description as description',
                'users.name as user_name'
            )->orderBy('orders.created_at', 'desc')->paginate(20);

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


        //view data
        return view('admin/bill/bonakdar', ['request' => $order, 'fullTotal' => $fullCart]);

    }

    public function bonakdarEdit(){

        $order_id = $this->request->order_id;


        //get data query

        $fullCart = 0;
        $order = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->join('order_details', 'order_details.order_id', '=' , 'orders.id')
            ->join('transactions', 'transactions.key', '=' , 'order_details.val')
            ->join('users', 'users.id' , '=', 'transactions.user_id' )
            ->where('order_details.key' , 'thank_you_key')
            ->where('orders.status' , 'paid')
            ->where('transactions.status' , 'paid')
            ->where('orders.status_operator' , '>=' , 6)
            ->where('orders.id' , $order_id)
            ->where('products.seller_id' , '!=' , 0)
            ->select(
                'products.type as product_type',
                'products.sku as product_sku',
                'products.seller_id as seller',
                'orders.created_at as created',
                'orders.price as price',
                'orders.lathe_id as lathe_id',
                'orders.order_key as key',
                'orders.id as order_id',
                'transactions.id as transactions_id',
                'transactions.created_at as transactions_created_at',
                'transactions.payment_method as transactions_payment_method',
                'transactions.description as description',
                'users.name as user_name'
            )->first();

        if($order){

            $total = $order->price;

            $order_service = Order_detail::where('order_id', $order_id)->where('key', 'LIKE' ,'%order_service%%price%')->get();
            if($order_service){
                foreach ($order_service as $service) {
                    $total = $total + $service['val'];
                }
            }

            $fullCart = $total;

            //view data
            return view('admin/bill/bonakdar_edit', ['request' => $order, 'fullTotal' => $fullCart]);

        }

        return back();

    }

    public function amel(){
        $where_array = array();

        //filter set to query


        $filter_user = $this->request->get('filter_user');
        if($filter_user){

            $user = User::where('users.name', $filter_user)->first();
            if($user){
                $where_array[] = array('orders.lathe_id', $user->id);
            }else{
                $where_array[] = array('orders.lathe_id', '');
            }

        }

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '>=', $date);
        }

        $filter_end_date = $this->request->get('filter_end_date');
        if($filter_end_date){

            $date = explode('/', $filter_end_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '<=', $date);
        }

        $filter_checkout = $this->request->get('filter_checkout');
        if($filter_checkout){
            $where_array[] = array('orders.checkout_amel', $filter_checkout);
        }

        $filter_code = $this->request->get('filter_code');
        if($filter_code){

            $code = explode('-', $filter_code);
            if(isset($code[1]) && !empty($code[1])){

                $where_array[] = array('transactions.id', $code[1]);

                if(isset($code[2]) && !empty($code[2])) {
                    $where_array[] = array('orders.id', $code[2]);
                }

            }
        }


        //get data query

        $fullCart = [];
        $order = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->join('order_details', 'order_details.order_id', '=' , 'orders.id')
            ->join('transactions', 'transactions.key', '=' , 'order_details.val')
            ->join('users', 'users.id' , '=', 'transactions.user_id' )
            ->where('order_details.key' , 'thank_you_key')
            ->where('orders.status' , 'paid')
            ->where('transactions.status' , 'paid')
            ->where('orders.status_operator' , '>=' , 2)
            ->where('orders.lathe_id' , '!=' , 0)
            ->where($where_array)
            ->select(
                'products.type as product_type',
                'products.sku as product_sku',
                'products.seller_id as seller',
                'orders.created_at as created',
                'orders.paid_amount_amel as price',
                'orders.lathe_id as lathe_id',
                'orders.order_key as key',
                'orders.id as order_id',
                'transactions.id as transactions_id',
                'transactions.created_at as transactions_created_at',
                'transactions.payment_method as transactions_payment_method',
                'transactions.description as description',
                'users.name as user_name'
            )->orderBy('orders.created_at', 'desc')->paginate(20);

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


        //view data
        return view('admin/bill/amel', ['request' => $order, 'fullTotal' => $fullCart]);

    }

    public function labrator(){
        $where_array = array();

        //filter set to query


        $user = [];
        $filter_user = $this->request->get('filter_user');
        if($filter_user){

            $user = User::where('users.name', $filter_user)->first();

        }

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '>=', $date);
        }

        $filter_end_date = $this->request->get('filter_end_date');
        if($filter_end_date){

            $date = explode('/', $filter_end_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '<=', $date);
        }

        $filter_checkout = $this->request->get('filter_checkout');
        if($filter_checkout){
            $where_array[] = array('orders.checkout_amel', $filter_checkout);
        }

        $filter_code = $this->request->get('filter_code');
        if($filter_code){

            $code = explode('-', $filter_code);
            if(isset($code[1]) && !empty($code[1])){

                $where_array[] = array('transactions.id', $code[1]);

                if(isset($code[2]) && !empty($code[2])) {
                    $where_array[] = array('orders.id', $code[2]);
                }

            }
        }


        //get data query
        $order_id = [];
        $order_labrator = [];
        $order_service = Order_detail::where('key', 'LIKE' ,'%order_service%%labrator%')->distinct('order_id')->get();
        if($order_service){
            foreach ($order_service as $service) {
                if($user){
                    if($user->id == $service['val']){
                        $order_id[] = $service['order_id'];
                    }
                }else{
                    $order_id[] = $service['order_id'];
                }
                $order_labrator[$service['order_id']] = $service['val'];
            }
        }

        $fullCart = [];
        $order = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->join('order_details', 'order_details.order_id', '=' , 'orders.id')
            ->join('transactions', 'transactions.key', '=' , 'order_details.val')
            ->where('order_details.key' , 'thank_you_key')
            ->whereIn('orders.id' , $order_id)
            ->where('orders.status' , 'paid')
            ->where('orders.status_operator' , '>=' , 3)
            ->where($where_array)
            ->select(
                'products.type as product_type',
                'products.sku as product_sku',
                'products.seller_id as seller',
                'orders.created_at as created',
                'orders.paid_amount_labrator as price',
                'orders.lathe_id as lathe_id',
                'orders.order_key as key',
                'orders.id as order_id',
                'transactions.id as transactions_id',
                'transactions.created_at as transactions_created_at',
                'transactions.payment_method as transactions_payment_method',
                'transactions.description as description'
            )->orderBy('orders.created_at', 'desc')->paginate(20);

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


        //view data
        return view('admin/bill/labrator', ['request' => $order, 'fullTotal' => $fullCart, 'order_labrator' => $order_labrator]);

    }

    public function shop(){
        $where_array = array();

        $fullCart = [];
        $order = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->join('order_details', 'order_details.order_id', '=' , 'orders.id')
            ->join('transactions', 'transactions.key', '=' , 'order_details.val')
            ->where('order_details.key' , 'thank_you_key')
            ->where('orders.status' , 'paid')
            ->where('orders.status_operator' , '>=' , 3)
            ->where($where_array)
            ->select(
                'products.type as product_type',
                'products.sku as product_sku',
                'products.seller_id as seller',
                'orders.created_at as created',
                'orders.paid_amount_shop as price',
                'orders.lathe_id as lathe_id',
                'orders.order_key as key',
                'orders.id as order_id',
                'transactions.id as transactions_id',
                'transactions.created_at as transactions_created_at',
                'transactions.payment_method as transactions_payment_method',
                'transactions.description as description'
            )->orderBy('orders.created_at', 'desc')->paginate(20);

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


        //view data
        return view('admin/bill/shop', ['request' => $order, 'fullTotal' => $fullCart]);

    }


}

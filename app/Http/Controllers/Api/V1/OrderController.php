<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserShip;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    const IMAGE_HOST = 'http://127.0.0.1:8000';

    /**
     * 获取产品列表信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartProduct(Request $request)
    {
        $user_id = $request->userInfo['id'];
        $data = CartService::getUserCheckedList($user_id);

        return success_json($data);
    }

    /**
     * 提交订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        //DB::enableQueryLog();
        $validator = Validator::make($request->all(), [
            'shipId' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $shipId = $request->input('shipId');
        /*检查收货地址是否有效*/
        $ship = UserShip::find($shipId);
        if (!$ship)
        {
            // 收货地址信息不存在
            return error_json(10501);
        }

        $user_id = $request->userInfo['id'];
        $cart = CartService::getUserCheckedList($user_id);
        if ($cart['list']->isEmpty())
        {
            // 请选择商品下单
            return error_json(10600);
        }

        $order_id = generate_sn(1);
        $total_fee = 0.00;

        // 订单数据
        $item_data = [];
        foreach ($cart['list'] as $c)
        {
            if (!$c->product)
            {
                // 商品不存在
                return error_json(10300);
            }
            if ($c->product['status'] == Product::STATUS_IN_STOCK)
            {
                // 商品已下架
                return error_json(10301);
            }
            if ($c->quantity > $c->product['stock'])
            {
                // 商品库存不足
                return error_json(10303);
            }

            $_total_fee = $c->product['price'] * $c->quantity;
            $_payment = $_total_fee;
            $total_fee += $_total_fee;

            $item_data[] = [
                'id' => generate_sn(1),
                'oid' => $order_id,
                'user_id' => $user_id,
                'product_id' => $c->product_id,
                'title' => $c->product['title'],
                'main_img' => $c->product['main_img'],
                'quantity' => $c->quantity,
                'payment' => $_payment,
                'total_fee' => $_total_fee,
            ];
        }

        $payment = $total_fee;

        // 主订单数据
        $order_data = [
            'id' => $order_id,
            'user_id' => $user_id,
            'status' => Order::STATUS_WAIT_BUYER_PAY,
            'pay_type' => Order::PAY_ONLINE,
            'payment' => $payment,
            'total_fee' => $total_fee,
            'name' => $ship->name,
            'mobile' => $ship->mobile,
            'tel' => $ship->tel,
            'zip' => $ship->zip,
            'province' => $ship->province,
            'city' => $ship->city,
            'district' => $ship->district,
            'address' => $ship->address,
        ];

        try {
            DB::beginTransaction();

            // 添加主订单数据
            Order::create($order_data);

            foreach ($item_data as $v)
            {
                // 添加子订单数据
                OrderItem::create($v);

                $_product_id = $v['product_id'];
                // 更新商品库存
                $product = Product::find($_product_id);
                if ($v['quantity'] > $product->stock)
                {
                    // 商品库存不足
                    return error_json(10303);
                }
                $product->stock -= $v['quantity'];
                $product->save();

                // 删除购物车已选商品
                Cart::where(['user_id'=>$user_id, 'product_id'=>$_product_id])->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // 创建订单失败
            return error_json(10601);
        }

        $data = [
            'orderNo' => $order_id,
            'payment' => $payment,
        ];
        //dd(DB::getQueryLog());
        return success_json($data);
    }

    /**
     * 获取支付信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderNo' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $orderNo = $request->input('orderNo');

        $order = Order::find($orderNo);
        if (!$order)
        {
            // 获取订单信息失败
            return error_json(10602);
        }
        if ($order->status != Order::STATUS_WAIT_BUYER_PAY)
        {
            // 订单已支付
            return error_json(10603);
        }

        try {
            $data = [
                'orderNo' => $order->id,
                'qrUrl' => 'http://127.0.0.1:8000/storage/attachment/20200521/qr-1492329044075.png'
            ];
        } catch (\Exception $e) {
            // 支付单生成失败
            return error_json(10604);
        }

        return success_json($data);
    }

    /**
     * 获取订单状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function queryPayStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderNo' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $orderNo = $request->input('orderNo');

        $data = true;
        return success_json($data);
    }
}

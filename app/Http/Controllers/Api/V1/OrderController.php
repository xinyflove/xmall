<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\UserShip;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function create(Request $request)
    {
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
            return error_json(10501);
        }

        $user_id = $request->userInfo['id'];
        $cart = CartService::getUserCheckedList($user_id);
        if (!$cart)
        {
            return error_json(10600);
        }

        $item_data = [];

        foreach ($cart as $c)
        {
            if ($c->quantity > $c->product['stock'])
            {
                return error_json(10601); 
            }
        }

        $order_data = [

        ];

        dd(generate_sn(1));
        dd($cart);
    }
}

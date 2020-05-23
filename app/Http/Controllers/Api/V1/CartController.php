<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    /**
     * 添加购物车
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
            'count' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $productId = $request->input('productId');
        $count = $request->input('count');
        $user_id = $request->userInfo['id'];

        /*验证商品*/
        $product = Product::find($productId);
        if (!$product)
        {
            return error_json(10300);
        }
        if ($product->status == 0)
        {
            return error_json(10301);
        }
        if ($product->stock <= 0)
        {
            return error_json(10302);
        }
        if ($product->stock < $count)
        {
            return error_json(10303);
        }

        $cart = Cart::where(['user_id'=>$user_id, 'product_id'=>$productId])->first();

        try {
            if ($cart)
            {
                $cart->quantity += $count;
                $cart->save();
            }
            else
            {
                $data = [
                    'user_id' => $user_id,
                    'product_id' => $productId,
                    'quantity' => $count,
                    'checked' => 1,
                    'cart_price' => $product->price,
                ];

                Cart::create($data);
            }
        } catch (\Exception $e) {
            return error_json(10400, $e->getMessage());
        }

        return success_json();
    }

    /**
     * 购物车数量
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productCount(Request $request)
    {
        $user_id = $request->userInfo['id'];
        $where = [
            'user_id'=>$user_id,
            'checked'=>Cart::CHECKED,
        ];
        $count = Cart::where($where)->count();

        return success_json($count);
    }
}

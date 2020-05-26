<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    protected $_image_host = 'http://127.0.0.1:8000';

    /**
     * 购物车
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $user_id = $request->userInfo['id'];
        $data = CartService::getUserCartList($user_id);

        return success_json($data);
    }

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
            return error_json(10400);
        }

        return success_json();
    }

    /**
     * 选择购物车商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $productId = $request->input('productId');
        $user_id = $request->userInfo['id'];
        
        $where = [
            'user_id' => $user_id,
            'product_id' => $productId,
        ];
        $cart = Cart::where($where)->first();
        
        if (!$cart)
        {
            return error_json(10401);
        }

        try {
            $cart->checked = Cart::CHECKED;
            $cart->save();
        } catch (\Exception $e) {
            return error_json(10401);
        }

        $data = CartService::getUserCartList($user_id);

        return success_json($data);
    }

    /**
     * 取消选择购物车商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unSelect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $productId = $request->input('productId');
        $user_id = $request->userInfo['id'];

        $where = [
            'user_id' => $user_id,
            'product_id' => $productId,
        ];
        $cart = Cart::where($where)->first();

        if (!$cart)
        {
            return error_json(10402);
        }

        try {
            $cart->checked = Cart::UNCHECKED;
            $cart->save();
        } catch (\Exception $e) {
            return error_json(10402);
        }

        $data = CartService::getUserCartList($user_id);

        return success_json($data);
    }

    /**
     * 选中全部商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectAll(Request $request)
    {
        $user_id = $request->userInfo['id'];

        try {
            Cart::where('user_id', $user_id)->update(['checked'=>Cart::CHECKED]);
        } catch (\Exception $e) {
            return error_json(10403);
        }

        $data = CartService::getUserCartList($user_id);

        return success_json($data);
    }

    /**
     * 取消选中全部商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unSelectAll(Request $request)
    {
        $user_id = $request->userInfo['id'];

        try {
            Cart::where('user_id', $user_id)->update(['checked'=>Cart::UNCHECKED]);
        } catch (\Exception $e) {
            return error_json(10404);
        }

        $data = CartService::getUserCartList($user_id);

        return success_json($data);
    }

    /**
     * 更新购物车商品数量
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
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
        if (!$cart)
        {
            return error_json(10405);
        }

        try {
            $cart->quantity = $count;
            $cart->save();
        } catch (\Exception $e) {
            return error_json(10405);
        }

        $data = CartService::getUserCartList($user_id);

        return success_json($data);
    }

    /**
     * 删除指定商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productIds' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $productIds = $request->input('productIds');
        $user_id = $request->userInfo['id'];
        $productIdArr = explode(',', $productIds);

        try {
            Cart::where(['user_id'=>$user_id])->whereIn('product_id', $productIdArr)->delete();
        } catch (\Exception $e) {
            return error_json(10406);
        }

        $data = CartService::getUserCartList($user_id);

        return success_json($data);
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

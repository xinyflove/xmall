<?php

namespace App\Services;

use App\Models\Cart;

/**
 * 购物车服务类
 * Class CartService
 * @package App\Services
 */
class CartService
{
    const IMAGE_HOST = 'http://127.0.0.1:8000';

    /**
     * 获取门户用户购物车列表
     * @param $user_id
     * @return array
     */
    public static function getUserCartList($user_id)
    {
        $allChecked = true;
        $cartTotalPrice = 0;
        $list = Cart::with(['product'])->where('user_id', $user_id)->get();
        
        if ($list->isEmpty()) $allChecked = false;

        foreach ($list as $item)
        {
            $item->product_total = $item->product->price * $item->quantity;
            if ($item->checked == 0)
            {
                $allChecked = false;
            }
            else
            {
                $cartTotalPrice += $item->product_total;
            }

            $item->product->image_host = self::IMAGE_HOST;
        }
        
        $data = [
            'list' => $list,
            'allChecked' => $allChecked,
            'cartTotalPrice' => $cartTotalPrice,
        ];
        
        return $data;
    }

    /**
     * 获取用户购物车已选商品列表
     * @param $user_id
     * @return array
     */
    public static function getUserCheckedList($user_id)
    {
        $productTotalPrice = 0;
        $list = Cart::where(['user_id'=>$user_id, 'checked'=>Cart::CHECKED])->get();

        foreach ($list as $item)
        {
            $item->product_total = $item->product->price * $item->quantity;
            $productTotalPrice += $item->product_total;
        }

        $data = [
            'list' => $list,
            'image_host' => self::IMAGE_HOST,
            'productTotalPrice' => $productTotalPrice,
        ];

        return $data;
    }
}
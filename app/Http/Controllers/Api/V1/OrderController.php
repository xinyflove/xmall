<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}

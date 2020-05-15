<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function productCount(Request $request)
    {
        $data = 2;
        return success_json($data);
    }
}

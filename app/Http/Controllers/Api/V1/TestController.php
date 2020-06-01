<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = 'success';
        return success_json($data);
    }
}

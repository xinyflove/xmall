<?php

namespace App\Http\Controllers\AdminApi\V1;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        echo 'admin api v1 test index';
    }
}
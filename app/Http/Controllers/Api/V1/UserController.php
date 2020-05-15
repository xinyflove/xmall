<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = 'user index';
        return success_json($data);
    }

    /**
     * 用户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $params = request(['username', 'password']);

        if (!Auth::attempt($params)) {
            return error_json(10102);
        }

        $userInfo = Auth::user();

        $data = [
            'id' => $userInfo->id,
            'token' => $userInfo->token,
        ];

        return success_json($data);
    }

    /**
     * 检查用户名
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkValid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:username',
            'str' => 'required|min:3|max:20'
        ], [
            'str.min' => error_msg(10115),
            'str.max' => error_msg(10115),
        ]);
        if ($validator->fails()) {
            return error_json(10001, $validator->messages()->first());
        };

        $username = $request->input('str');
        $user = User::where('username', $username)->first(['id']);
        if ($user)
        {
            return error_json(10110);
        }

        return success_json();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|min:3',
            'password' => 'required|min:6|max:18',
            'passwordConfirm' => 'required|min:6|max:18',
            'mobile' => 'required|email',
            'email' => 'required|email',
            'question' => 'required',
            'answer' => 'required'
        ]);
        dd($request->all());
        if ($validator->fails()) {
            return error_json(10001);
        };
    }

    /**
     * 用户登录信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function loginInfo(Request $request)
    {
        $data = [
            'name' => $request->userInfo['name']
        ];

        return success_json($data);
    }
}

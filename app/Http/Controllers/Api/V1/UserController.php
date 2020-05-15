<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'username' => 'required|min:3',
            'password' => 'required|min:6|max:18',
            'passwordConfirm' => 'required|min:6|max:18',
            'mobile' => ['required', new Mobile],
            'email' => 'required|email',
            'question' => 'required',
            'answer' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $username = $request->input('username');
        $userExist = User::withoutGlobalScope('avaiable')->where('username', $username)->first();
        if ($userExist)
        {
            return error_json(10110);
        }

        $password = $request->input('password');
        $passwordConfirm = $request->input('passwordConfirm');
        if ($password != $passwordConfirm)
        {
            return error_json(10116);
        }

        $password = bcrypt($password);
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $question = $request->input('question');
        $answer = $request->input('answer');
        $token = Str::random(60);
        $name = '';

        try {
            User::create(compact('username', 'password', 'mobile', 'email', 'question', 'answer', 'token', 'name'));
        } catch (\Exception $e) {
            return error_json(10112);
        }

        return success_json();
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

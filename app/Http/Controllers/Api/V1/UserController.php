<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

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

        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where(['username'=>$username])->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return error_json(10102);
        }

        $data = [
            'id' => $user->id,
            'token' => $user->token,
        ];

        return success_json($data);
    }

    /**
     * 检查用户名
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function checkValid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:username',
            'str' => 'required|min:3|max:20'
        ], [
            'str.min' => error_msg(10205),
            'str.max' => error_msg(10205),
        ]);
        if ($validator->fails()) {
            return error_json(10001, $validator->messages()->first());
        };

        $username = $request->input('str');
        $user = User::where('username', $username)->first(['id']);
        if ($user)
        {
            return error_json(10200);
        }

        return success_json();
    }

    /**
     * 用户注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
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
            return error_json(10200);
        }

        $password = $request->input('password');
        $passwordConfirm = $request->input('passwordConfirm');
        if ($password != $passwordConfirm)
        {
            return error_json(10206);
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
            return error_json(10202);
        }

        return success_json();
    }

    /**
     * 获取用户密码提示问题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function forgetGtQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $username = $request->input('username');
        $user = User::where('username', $username)->select(['question'])->first();
        if (!$user)
        {
            return error_json(10201);
        }

        return success_json($user);
    }

    /**
     * 检查密码提示问题答案
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function forgetCheckAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $username = $request->input('username');
        $question = $request->input('question');
        $answer = $request->input('answer');

        $where = [
            'username' => $username,
            'question' => $question,
            'answer' => $answer,
        ];
        $user = User::where($where)->select(['token'])->first();
        
        if (!$user)
        {
            return error_json(10207);
        }

        return success_json($user);
    }

    /**
     * 重置密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function forgetResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6|max:18',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $username = $request->input('username');
        $password = $request->input('password');
        $token = $request->input('token');

        try {
            $where = [
                'username' => $username,
                'token' => $token
            ];
            $password = bcrypt($password);
            User::where($where)->update(['password'=>$password]);
        }  catch (\Exception $e) {
            return error_json(10202);
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
            'username' => $request->userInfo['username']
        ];

        return success_json($data);
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    public function getInfo(Request $request)
    {
        $data = [
            'name' => $request->userInfo['name'],
            'username' => $request->userInfo['username'],
            'mobile' => $request->userInfo['mobile'],
            'email' => $request->userInfo['email'],
            'question' => $request->userInfo['question'],
            'answer' => $request->userInfo['answer'],
        ];

        return success_json($data);
    }

    /**
     * 更新个人信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', new Mobile],
            'email' => 'required|email',
            'question' => 'required',
            'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $user = User::find($request->userInfo['id']);
        if (!$user)
        {
            return error_json(10201);
        }
        
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $question = $request->input('question');
        $answer = $request->input('answer');

        try {
            $user->mobile = $mobile;
            $user->email = $email;
            $user->question = $question;
            $user->answer = $answer;
            $user->save();
        }  catch (\Exception $e) {
            return error_json(10209);
        }

        return success_json();
    }

    /**
     * 登录状态下更新密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|max:18',
            'passwordNew' => 'required|min:6|max:18',
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $user = User::find($request->userInfo['id']);
        if (!$user)
        {
            return error_json(10201);
        }

        $password = $request->input('password');
        $passwordNew = $request->input('passwordNew');

        if (!Hash::check($password, $user->password))// 验证原密码
        {
            return error_json(10210);
        }
        
        try {
            $user->password = bcrypt($passwordNew);
            $user->save();
        }  catch (\Exception $e) {
            return error_json(10211);
        }

        return success_json();
    }
}

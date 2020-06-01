<?php

namespace App\Http\Controllers\AdminApi\V1;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    /**
     * 管理员登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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

        $user = AdminUser::where(['username'=>$username])->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return error_json(10102);
        }

        $data = [
            'id' => $user->id,
            'token' => $user->token,
        ];

        return success_json($data);
    }
}

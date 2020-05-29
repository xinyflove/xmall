<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\UserShip;
use App\Rules\Mobile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserShipController extends Controller
{
    /**
     * 获取地址列表信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filter['pageSize'] = intval($request->input('pageSize', 20));
        $filter['pageNum'] = intval($request->input('pageNum', 1));
        $user_id = $request->userInfo['id'];

        $list = UserShip::where('user_id', $user_id)->get();

        $data = [
            'list'=>$list,
        ];

        return success_json($data);
    }

    /**
     * 新建收件人收货信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'mobile' => ['required', new Mobile],
            'province' => 'required|max:20',
            'city' => 'required|max:20',
            'district' => 'nullable|max:20',
            'address' => 'required|max:50',
            'zip' => 'nullable|max:10',
            'def' => 'nullable|in:0,1',
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $name = $request->input('name');
        $mobile = $request->input('mobile');
        $province = $request->input('province');
        $city = $request->input('city');
        $district = $request->input('district', '');
        $address = $request->input('address');
        $zip = $request->input('zip', '');
        $def = $request->input('def', 0);
        $tel = $request->input('tel', '');
        $user_id = $request->userInfo['id'];

        // 判断是否存在收货地址，不存在则第一个收货地址为默认
        $has = UserShip::where('user_id', $user_id)->first();
        $def = !!$has ? $def : UserShip::SHIP_DEF;

        try {
            DB::beginTransaction();
            if ($def)
            {
                UserShip::where('user_id', $user_id)->update(['def'=>UserShip::SHIP_NOR]);
            }

            UserShip::create(compact('name', 'mobile', 'province', 'city', 'district', 'address', 'zip', 'def', 'tel', 'user_id'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return error_json(10500);
        }

        return success_json();
    }

    /**
     * 获取要编辑的收货人收货信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipId' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $shipId = $request->input('shipId');
        $ship = UserShip::find($shipId);
        if (!$ship)
        {
            return error_json(10501);
        }

        return success_json($ship);
    }

    /**
     * 更新收件人收货信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|max:50',
            'mobile' => ['required', new Mobile],
            'province' => 'required|max:20',
            'city' => 'required|max:20',
            'district' => 'nullable|max:20',
            'address' => 'required|max:50',
            'zip' => 'nullable|max:10',
            'def' => 'nullable|in:0,1',
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $id = $request->input('id');
        $name = $request->input('name');
        $mobile = $request->input('mobile');
        $province = $request->input('province');
        $city = $request->input('city');
        $district = $request->input('district', '');
        $address = $request->input('address');
        $zip = $request->input('zip', '');
        $def = $request->input('def', 0);
        $tel = $request->input('tel', '');
        $user_id = $request->userInfo['id'];

        $ship = UserShip::find($id);
        if (!$ship)
        {
            return error_json(10501);
        }

        try {
            DB::beginTransaction();


            $ship->name = $name;
            $ship->mobile = $mobile;
            $ship->province = $province;
            $ship->city = $city;
            $ship->district = $district;
            $ship->address = $address;
            !empty($zip) && $ship->zip = $zip;
            !empty($tel) && $ship->tel = $tel;

            if ($def)
            {
                UserShip::where('user_id', $user_id)->update(['def'=>UserShip::SHIP_NOR]);
                $ship->def = $def;
            }

            $ship->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return error_json(10500);
        }

        return success_json();
    }

    /**
     * 删除收件人收货信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipId' => 'required'
        ]);
        if ($validator->fails()) {
            return error_json(10001);
        };

        $shipId = $request->input('shipId');
        $ship = UserShip::find($shipId);
        if (!$ship)
        {
            return error_json(10501);
        }

        try {
            if ($ship->def)
            {
                $user_id = $request->userInfo['id'];
                $oneShip = UserShip::where('user_id', $user_id)->first();
                if ($oneShip)
                {
                    $oneShip->def = UserShip::SHIP_DEF;
                    $oneShip->save();
                }
            }

            $ship->delete();
        } catch (\Exception $e) {
            return error_json(10502);
        }

        return success_json();
    }
}

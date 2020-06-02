<?php

namespace App\Http\Controllers\AdminApi\V1;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string',
            'file' => 'required|file'
        ]);
        if ($validator->fails()) {

            return error_json(10001);
        };

        $type = $request->input('type', 'attachment');
        $file = $request->file('file');
        $user_id = $request->userInfo['id'];

        try {
            $fileService = new FileService();
            $data = $fileService->upload($file, $type, $user_id);
        } catch (\Exception $e) {
            return error_json($e->getCode());
        }

        return success_json($data);
    }
}

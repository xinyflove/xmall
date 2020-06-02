<?php

namespace App\Services;


use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileService
{
    protected $_upload_max_filesize = '10M';// 上传文件大小限制
    protected $_invalidExtension = [// 上传文件类型限制
        'php','exe','link','so','a','dll','c','dll','cpp','py','js','html','pyc'
    ];
    protected $_allowExtension = [];// 允许文件类型
    
    public function __construct($option=[])
    {
        if (!empty($option['invalidExtension']))
        {
            $this->_invalidExtension = $option['invalidExtension'];
        }
        if (!empty($option['uploadMaxFileSize']))
        {
            $this->_upload_max_filesize = $option['uploadMaxFileSize'];
        }
    }

    public function upload($file, $type='attachment', $user_id=0, $is_store=false)
    {
        ini_set('upload_max_filesize', $this->_upload_max_filesize);

        $extension = $file->getClientOriginalExtension();// 获取上传文件扩展
        if(in_array(strtolower($extension), $this->_invalidExtension))
        {
            $code = 10006;
            throw new \Exception(error_msg($code), $code);
        }

        if (!$file->isValid()) {
            $code = 10007;
            throw new \Exception(error_msg($code), $code);
        }

        $data = [];
        try {
            $title = sha1(time(). rand(0, 99)) . '.' . $extension;
            $path = UploadHelper::storeFile($title, $file, $type);
            $url = config('app.url') . '/' . $path;
            $name = $file->getClientOriginalName(); // 文件原名
            unset($file);

            if ($is_store)
            {
                $upload_time = time();
                $file = File::create(compact('user_id', 'name', 'path', 'url', 'upload_time'));
                $data['id'] = $file->id;
            }

            $data['name'] = $name;
            $data['url'] = $url;

        } catch (\Exception $e) {
            $code = 10007;
            throw new \Exception(error_msg($code), $code);
        }

        return $data;
    }
    
    
}
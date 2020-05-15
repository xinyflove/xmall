<?php
/**
 * 文件上传帮助类
 */
namespace App\Helpers;

class UploadHelper
{
    /**
     * 存储文件
     * @param $file
     * @param string $type
     * @return string
     */
    public static function storeFile($file, $type='attachment')
    {
        $savePath = $type . '/' . date('Ymd');
        $path = $file->storePublicly($savePath);
        
        return 'storage/' . $path;
    }

    /**
     * 存储Base64文件
     * @param $title
     * @param $file
     * @param string $type
     * @return string
     */
    public static function storeBase64File($title, $file, $type='attachment')
    {

        $dir = date('ymd');
        $savePath = 'upload/' . $type . '/' . $dir . '/';
        $abcSavePath = public_path('upload') . '/' . $type . '/' . $dir . '/';
        self::mkMutiDir($abcSavePath);
        file_put_contents($abcSavePath . $title, $file);

        return $savePath . $title;
    }

    /**
     * 创建目录
     * @param $dir
     * @return bool
     */
    public static function mkMutiDir($dir){
        if(!is_dir($dir))
        {
            if(!self::mkMutiDir(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0755)){
                return false;
            }
        }
        
        return true;
    }

    /**
     * 删除文件
     * @param $path
     * @return bool
     */
    public static function deleteFile($path)
    {
        return @unlink(public_path($path));
    }
}
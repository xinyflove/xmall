<?php
namespace App\Helpers;

/**
 * 图片处理帮助类
 * @package App\Helpers
 * @author Peak Xin<xinyflove@sina.com>
 */
class ImageHelper {

    private $__storage = '';
    private $__fontFile = '';
    private $__textColor = '#212121';

    public function __construct()
    {
        $this->__storage = public_path('storage');// ./storage
        $this->__fontFile = "{$this->__storage}/font/simhei.ttf";
    }

    /**
     * 添加水印
     * @param $dst_im
     * @param $params
     * @return bool
     * @throws \Exception
     * @author Peak Xin<xinyflove@sina.com>
     */
    public function waterMark($dst_im, $params)
    {
        $ground_image = $this->createImage($dst_im);
        //启用混色模式
        imagealphablending($ground_image['im'], true);

        /*文字水印*/
        if (!empty($params['waterText'])) {
            $waterText = $params['waterText'];
            foreach ($waterText as $item) {
                empty($item['text']) && $item['text'] = '';
                empty($item['size']) && $item['size'] = '20';
                empty($item['x']) && $item['x'] = '0';
                empty($item['y']) && $item['y'] = '0';
                (empty($item['color']) || strlen($item['color']) != 7) && $item['color'] = $this->__textColor;
                $item['color_rgb'] = $this->hex2rgb($item['color'], $err);
                if (!$item['color_rgb']) {
                    throw new \Exception($err);
                }
                empty($item['angle']) && $item['angle'] = '0';
                empty($item['fontfile']) && $item['fontfile'] = $this->__fontFile;

                // 生成文字水印
                $text_color = imagecolorallocate($ground_image['im'], $item['color_rgb']['R'], $item['color_rgb']['G'], $item['color_rgb']['B']);// 字体颜色
                imagettftext($ground_image['im'],  $item['size'] , $item['angle'], $item['x'], $item['y'], $text_color, $item['fontfile'], $item['text']);
            }
        }

        /*图片水印*/
        if (!empty($params['waterImage'])) {
            $waterImage = $params['waterImage'];
            foreach ($waterImage as $item) {
                if (!file_exists($item['src_im'])) {
                    throw new \Exception("文件{$item['src_im']}不存在");
                }

                $water_im = $this->resizeImage($item['src_im'], $item['src_w'], $item['src_h']);
                imagecopy($ground_image['im'], $water_im, $item['dst_x'], $item['dst_y'], $item['src_x'], $item['src_y'], $item['src_w'], $item['src_h']);
                imagedestroy($water_im);
            }
        }

        imagesavealpha($ground_image['im'] , true);

        switch ($ground_image['mime']) {//取得背景图片的格式
            case 'image/gif':
                imagegif($ground_image['im'], $dst_im);
                break;
            case 'image/jpeg':
                imagejpeg($ground_image['im'], $dst_im);
                break;
            case 'image/png':
                imagepng($ground_image['im'], $dst_im);
                break;
            default:

                break;
        }

        imagedestroy($ground_image['im']);

        return true;
    }

    /**
     * 将16进制颜色转换为RGB
     * @param $hexColor
     * @param string $err
     * @return array|bool
     * @author Peak Xin<xinyflove@sina.com>
     */
    public function hex2rgb($hexColor, &$err='')
    {
        if (!empty($hexColor) && (strlen($hexColor) == 7)) {
            $R = hexdec(substr($hexColor, 1, 2));
            $G = hexdec(substr($hexColor, 3, 2));
            $B = hexdec(substr($hexColor, 5));

            return ['R'=>$R, 'G'=>$G, 'B'=>$B];
        }

        $err = '水印文字颜色格式不正确';
        return false;
    }

    /**
     * 创建一个新图象
     * @param $image
     * @param string $err
     * @return array|bool
     * @author Peak Xin<xinyflove@sina.com>
     */
    public function createImage($image, &$err='')
    {
        $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。";

        if (file_exists($image)) {
            $info = getimagesize($image);
            $w = $info[0]; //取得水印图片的宽
            $h = $info[1]; //取得水印图片的高
            $mime = $info['mime'];
            switch ($info[2]) {//取得水印图片的格式
                case 1:
                    $im = imagecreatefromgif($image);
                    break;
                case 2:
                    $im = imagecreatefromjpeg($image);
                    break;
                case 3:
                    $im = imagecreatefrompng($image);
                    break;
                default:
                    $err = $formatMsg;
            }

            return compact('im', 'w', 'h', 'mime');
        } else {
            $err = "文件{$image}不存在";

            return false;
        }
    }

    /**
     * 按照指定的尺寸压缩图片
     * @param string $source_path 原图路径
     * @param int|string $imgWidth 目标宽度
     * @param int|string $imgHeight 目标高度
     * @param string $target_path 保存路径
     * @return bool|false|resource|string
     * @author Peak Xin<xinyflove@sina.com>
     */
    public function resizeImage($source_path, $imgWidth, $imgHeight, $target_path='')
    {
        $source_info = getimagesize($source_path);
        $source_mime = $source_info['mime'];// 图片格式
        switch ($source_mime)
        {
            case 'image/gif':
                $source_image = imagecreatefromgif($source_path);
                break;

            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($source_path);
                break;

            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                break;

            case 'image/webp':
                $source_image = imagecreatefromwebp($source_path);
                break;

            default:
                return false;
                break;
        }

        // 创建一个彩色的底图
        $target_image = imagecreatetruecolor($imgWidth, $imgHeight);
        //分配颜色 + alpha，将颜色填充到新图上
        $alpha = imagecolorallocatealpha($target_image, 0, 0, 0, 127);
        imagefill($target_image, 0, 0, $alpha);
        // 重采样拷贝部分图像并调整大小
        imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $imgWidth, $imgHeight, $source_info[0], $source_info[1]);

        if (empty($target_path)) {
            return $target_image;
        }

        //保存图片到本地
        if (!imagejpeg($target_image, $target_path)) {
            $target_path = false;
        }
        imagedestroy($target_image);// 释放内存

        return $target_path;
    }

    /**
     * 按照比例裁剪图片
     * @param string $source_path 原图路径
     * @param int|string $target_width 需要裁剪的宽
     * @param int|string $target_height 需要裁剪的高
     * @param string $target_path 需要保存的路径
     * @return bool|string
     * @author Peak Xin<xinyflove@sina.com>
     */
    public function image_cropper($source_path, $target_width, $target_height, $target_path='')
    {
        $source_info     = getimagesize($source_path);
        $source_width     = $source_info[0];
        $source_height     = $source_info[1];
        $source_mime     = $source_info['mime'];
        $source_ratio     = $source_height / $source_width;
        $target_ratio     = $target_height / $target_width;

        if ($source_ratio > $target_ratio) // 源图过高
        {
            $cropped_width = $source_width;
            $cropped_height = $source_width * $target_ratio;
            $source_x = 0;
            $source_y = ($source_height - $cropped_height) / 2;

        }elseif ($source_ratio < $target_ratio){  // 源图过宽

            $cropped_width = $source_height / $target_ratio;
            $cropped_height = $source_height;
            $source_x = ($source_width - $cropped_width) / 2;
            $source_y = 0;
        }else{ // 源图适中

            $cropped_width = $source_width;
            $cropped_height = $source_height;
            $source_x = 0;
            $source_y = 0;
        }

        switch ($source_mime)
        {
            case 'image/gif':
                $source_image = imagecreatefromgif($source_path);
                break;

            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($source_path);
                break;

            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                break;

            default:
                return false;
                break;
        }

        $target_image = imagecreatetruecolor($target_width, $target_height);
        $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

        // 裁剪
        imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
        // 缩放
        imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);

        //保存图片到本地(两者选一)
        $dir = '../../'.$target_path. '/'. date("Ymd") . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }

        $fileName = $dir.date("YmdHis").uniqid().'.jpg';
        if(!imagejpeg($target_image,'./'.$fileName)){
            $fileName = '';
        }
        imagedestroy($target_image);
        return $fileName;
    }
}

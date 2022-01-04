<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    /*$name = '辛小峰';
    $name_x = (754 - (strlen($name)/3-1)*14);
    $waterText = [
        ['text'=>'PN12346789', 'size'=>'20', 'x'=>'280', 'y'=>'855', 'color'=>'#272121', 'angle'=>'0', 'fontfile'=>''],
        ['text'=>$name, 'size'=>'24', 'x'=>$name_x, 'y'=>'460', 'color'=>'#272121', 'angle'=>'0', 'fontfile'=>''],
        ['text'=>'1', 'size'=>'24', 'x'=>'790', 'y'=>'680', 'color'=>'#272121', 'angle'=>'0', 'fontfile'=>''],
        ['text'=>'2022年01月05日', 'size'=>'20', 'x'=>'1000', 'y'=>'855', 'color'=>'#272121', 'angle'=>'0', 'fontfile'=>''],
    ];
    $storage = public_path('storage');// ./storage
    $waterImage = [
        ['src_im' => "{$storage}/education/work_photo/1193.png", 'dst_x'=>'892', 'dst_y'=>'170', 'src_x'=>'0', 'src_y'=>'0', 'src_w'=>'160', 'src_h'=>'208'],
        ['src_im' => "{$storage}/education/16038558819135.png", 'dst_x'=>'1060', 'dst_y'=>'700', 'src_x'=>'0', 'src_y'=>'0', 'src_w'=>'200', 'src_h'=>'200'],
    ];

    $params = [
        'waterText' => $waterText,
        'waterImage' => $waterImage,
    ];

    try {
        // 证书模板
        $cert_template = "{$storage}/education/cert_template.png";
        if(!file_exists($cert_template)) {
            return $this->error(10001,'证书模板不存在！');
        }

        $cert_dir = '/education/certificate';// 证书相对目录
        $cert_real_dir = "{$storage}{$cert_dir}";// 图片绝对目录
        if (!file_exists($cert_real_dir)) {
            // 不存在则创建目录
            @mkdir($cert_real_dir, 0777, true);
        }
        $cert_filename = "1-2022-01-04.png";// 证书文件名
        $cert_filepath = "{$cert_dir}/{$cert_filename}";// 证书文件相对地址
        $cert_real_filepath = "{$cert_real_dir}/{$cert_filename}";// 证书文件绝对地址

        // 1.将证书模板复制到证书目录中
        copy($cert_template, $cert_real_filepath);

        (new \App\Helpers\ImageHelper())->waterMark($cert_real_filepath, $params);
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }*/

    return view('welcome');
});

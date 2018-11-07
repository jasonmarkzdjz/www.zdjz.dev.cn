<?php
namespace App\Bls\Pano;
use App\Bls\Common\Model\AreaModel;
use library\Service\ApiSpeech\AipSpeech;
use \Image;
use \Response;
/**
 * 公用函数
 */
class CommonBls
{
    /**
     * 创建不重复的ID
     * @author Jason7 2018-05-29
     * @return string
     */
    public function create_unique_id($length = 32)
    {
        mt_srand((double)microtime()*10000); // optional for php 4.2.0 and up.
        $charid = strtolower(md5(uniqid(rand(), true)));
        if($length == 16) {
            $charid = substr($charid,8,16);
        }
        return $charid;
    }

    /**
     * 文件上传
     * @author Jason7 2018-05-30
     * @param  file $file 文件
     * @param  string $savePath 存储路径
     * @param  string $partition 分区
     * @return array
     */
    public function upload_file($file,$savePath,$partition = 'qiniu')
    {
        @set_time_limit(0);
        $disk = \Storage::disk($partition);
        if ($file->isValid()) {
            $ext = $file->getClientOriginalExtension(); // 扩展名
            $realPath = $file->getRealPath(); //临时文件的绝对路径
            $filename = $savePath . uniqid() . '.' . $ext;
            // 大文件上传
            $bool = $disk->put($filename, fopen($realPath,'r+'));
            if ($bool) {
                $saveInfo['imgurl'] = $disk->url($filename);
                $saveInfo['filename'] = $filename;
                $saveInfo['cdnhost'] = parse_url($disk->url($filename))['host'];
                $saveInfo['status']   = 1;
            } else {
                $saveInfo['status']   = 0;
            }
            return $saveInfo;
        }
    }

    /**
     * 生成不重复随机数
     * @author Jason7 2018-06-13
     * @return string
     */
    public static function get_rand_number()
    {
       /* 选择一个随机的方案 */
       mt_srand((double) microtime() * 1000000);
       return  str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * 获取区域名称
     * @author Jason7 2018-06-13
     * @param  int $areaId
     * @return string
     */
    public function get_area_name($areaId = 0)
    {
        return AreaModel::where('areaId',$areaId)->value('areaName');
    }

    /**
     * 文字转语音
     * @author Jason7 2018-06-30
     * @param  string $text 合成的文本，使用UTF-8编码，请注意文本长度必须小于1024字节
     * @param  string $cuid 用户唯一标识，用来区分用户，填写机器 MAC 地址或 IMEI 码，长度为60以内
     * @param  string $spd 语速，取值0-9，默认为5中语速
     * @param  string $pit 音调，取值0-9，默认为5中语调
     * @param  string $vol 音量，取值0-15，默认为5中音量
     * @param  string $per 发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女
     * @return audio
     */
    public function text2speech($text = null, $spd = '5', $pit = '5', $vol = '5', $per = '1', $cuid = null)
    {
        header('Content-Type: audio/mpeg');
        // 你的 APPID AK SK
        $appId = '11472641';
        $apiKey = 'r6bYkNcfGbYZDnOlWqPgY7kW';
        $secretKey = 'VaDQ6ytY2GBLv88l8MjkfP4wqHFiYxz';

        $client = new AipSpeech($appId, $apiKey, $secretKey);
        $result = $client->synthesis($text, 'zh', 1, array(
            'per' => $per,
            'spd' => $spd,
            'pit' => $pit,
            'vol' => $vol,
            'cuid'=> $cuid
        ));

        // 保存mp3
        // if(!is_array($result)){
        //     file_put_contents('audio.mp3', $result);
        // }
        return $result;
    }

    public function get_goods_list($mid = 0, $keywords = null)
    {
        return array(['id' => 88, 'goods_name' => 'demo商品1', 'imgurl' => '1.png'],['id' => 89, 'goods_name' => 'demo商品2', 'imgurl' => '2.png']);
    }

    /**
     * TODO BUG
     * 多图合成一张整图
     * @author Jason7 2018-07-09
     * @return [type] [description]
     */
    public function slice2plane($picArr)
    {
        $target  = asset('ucenter/images/VREdit_point_bg.png'); //背景图片
        $target_img = imagecreatefrompng($target);
        $source = [];
        foreach ($picArr as $k => $v) {
            $source[$k]['source'] = imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }
        $num  = 1;
        $tmp  = 2;
        $tmpy = 0; //图片之间的间距
        for ($i = 0; $i < count($picArr); $i++) {
            $width = $source[$i]['size'][0];
            $height = $source[$i]['size'][1];

            imagecopy($target_img, $source[$i]['source'], $tmp, $tmpy, 0, 0, $width, $height);
             
            $tmp = $tmp + $source[$i]['size'][0];
            $tmp = $tmp + 5;
            if ($i == $num) {
                $tmpy = $tmpy + $source[$i]['size'][1];
                $tmpy = $tmpy + 5;
                $tmp  = 2;
                $num  = $num + 3;
            }
        }
        // return imagepng($target_img, 'pin.png');
        // $file = $request->file('file');
        // $path = Auth::user()->id . '/images/source/';
        // return $this->upload_file($file,$path);
    }
}
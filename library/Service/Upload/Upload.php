<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/7/4
 * Time: 14:51
 */
namespace library\Service\Upload;
use Illuminate\Support\Facades\Storage;
use library\Service\Response\JsonResponse;
use Auth;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload{

    /**
     *params isonly 是否唯一 true 删除源文件 isonly为true时 $prefix 不能为空
     *
     *
     * */
    public function Image(UploadedFile $file,$originpath = '') {
        $baseUrl = storage_path('image');
        $up_dir =  date('Y', time()) . '/' . date('m', time()) . '/' . date('d', time());
        if (!is_dir($baseUrl.'/'.$up_dir)) {
            mkdir($baseUrl.'/'.$up_dir, 0777, true);
        }
        $extendName = $file->getClientOriginalExtension();//扩展名
        $fileTypeArray = ['pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'];
        if (in_array($file->getClientMimeType(), $fileTypeArray)) {
            return JsonResponse::error(0, '不允许上传该图片类型');
        }
        if ($file->getClientSize() > 2097152) {
            return JsonResponse::error(0, '上传图片大于2M禁止上传');
        }
        $new_file =  time() . uniqid() . '.' . $extendName;
        $m = $file->move($baseUrl.'/'.$up_dir, $new_file);//本地上传成功
        if ($m) {
            //上传同步到七牛
            $disk = \Storage::disk('qiniu');
            $local = Storage::disk('image');
            if ($originpath) {
                $local->exists($originpath) && $local->delete($originpath);
                if ($local->exists($originpath)) { //如果文件已存在 删除源文件  重新进行上传
                    $disk->delete($originpath);
                }
            }
            $isupload = $disk->put($new_file, file_get_contents($baseUrl.'/'.$up_dir.'/'.$new_file));
            if ($isupload) {
                return JsonResponse::success(['cdnurl' => $disk->url($new_file), 'originurl' => $up_dir.'/'.$new_file]);
            }
            return JsonResponse::error(0, '上传失败');
        }
    }
    public function base64Upload($base64Image= ''){
        $content = str_replace("data:image/png;base64,", "", $base64Image);
        $content = base64_decode($content);
        $params = array('typelimit' => 'jpg|gif|bmp|png', 'isformtype' => 1, 'smallflag' => 0, 'rsptype' => 1, 'rspjsontype' => 1, 'sizelimit' => 2097152);
        $up_dir =  date('Y', time()) . '/' . date('m', time()) . '/' . date('d', time());
        if (!is_dir(storage_path('image') . '/' .$up_dir)) {
            mkdir(storage_path('image') . '/' .$up_dir, 0777, true);
        }
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Image, $result)){
            $type = $result[2];
            $filename = \helper::getRandomString(8).time().".{$type}";
            $local_file_url = storage_path('image') . '/' .$up_dir."/".$filename;
            if (file_put_contents($local_file_url, base64_decode(str_replace($result[1], '', $base64Image)))){
                $disk = \Storage::disk('qiniu');
                $isupload = $disk->put($filename, file_get_contents($local_file_url));
                if ($isupload) {
                    return JsonResponse::success(['cdnurl' => $disk->url($filename), 'originurl' => $up_dir."/".$filename]);
                }
                return JsonResponse::error(0, '上传失败');
            }
            return JsonResponse::error(0, '上传失败');
        }
    }
}
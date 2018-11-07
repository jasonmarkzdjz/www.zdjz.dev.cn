<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/22
 * Time: 16:54
 */
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Auth;
use library\Service\File\TMFile;
use library\Service\Response\JsonResponse;

class UploadController extends Controller {

    /*
     *是否删除源文件 isdelete = 1
     *
     * */
    public function upImage(Request $request){
        $file = $request->file('file');
        $type = !empty($request->get('type')) ? $request->get('type') : 'images';
        strtoupper(substr(PHP_OS,0,3))==='WIN' ?
            $up_dir = '/upload/images/home/'.date('Y',time()).'/'.date('m',time()):
            $up_dir = storage_path('upload/'.$type.'/'.date('Y',time()).'/'.date('m',time()));
        if($request->get('isdelete')){
            $up_dir =  $up_dir.'/'.$type.'/'.$request->get('authType');
        }
        if(!is_dir($up_dir)){
            mkdir($up_dir,0777,true);
        }

        $extendName=$file->getClientOriginalExtension();//扩展名
        $fileTypeArray = ['pjpeg','jpeg','jpg','gif','bmp','png'];
        if(in_array($file->getClientMimeType(),$fileTypeArray)){
            return $this->retError(0,'不允许上传该图片类型');
        }
        if($file->getClientSize() > 2097152){
            return $this->retError(0,'上传图片大于2M禁止上传');
        }

        if($request->get('isdelete')){
            $new_name   = $request->get('lower').'.'.$extendName;
            $new_file    = $up_dir.'/'.Auth::user()->mobile.'_'.$new_name;
        }else{
            $new_name = time().uniqid();
            $new_file = $up_dir.'/'.$new_name.'.'.$extendName;
        }

        $m = $file->move($up_dir,$new_file);
        if($m){
            //上传同步到七牛
            $disk = Storage::disk('qiniu');
            if($request->get('isdelete')){
                $disk->exists($new_file) && $disk->delete($new_file);
            }
            if ($disk->put($new_file,file_get_contents($m))){
                return $this->retJson(['imgurl'=>$disk->url($new_file),'filename'=>$new_file]);
            }
            return $this->retError(0,'上传失败');
        }
        return $this->retError(0,'上传失败');
    }
        //文件上传
        public function upfile(Request $request){
            $privitefile = $request->file('privitefile');
            $publicfile = $request->file('publicKey');
            $fileTypeArray = ['pem'];
            if(in_array($request->privatekey->getClientMimeType(),$fileTypeArray) || in_array($publicfile->getClientMimeType(),$fileTypeArray)){
                return JsonResponse::error(0,'非法的文件类型禁止上传');
            }
            $privateOriginName = $privitefile->getClientOriginalName();
            $originNameArray = explode('.',$privateOriginName);
            if(empty($originNameArray) || $originNameArray[0] != 'merchant_private_key'){
                return JsonResponse::error(0,'学通宝商户私钥名称错误');
            }
            $publicOriginName = $publicfile->getClientOriginalName();
            $originNameArray = explode('.',$publicOriginName);
            if(empty($originNameArray) || $originNameArray[0] != 'merchant_public_key'){
                return JsonResponse::error(0,'学通宝商户公钥名称错误');
            }
            $priviteextendName=$privitefile->getClientOriginalExtension();//商户私钥文件扩展名
            $priviteRealPath = $privitefile->getRealPath();   //临时文件的绝对路径
            $publicfileExtensionName = $publicfile->getClientOriginalExtension();//商户公钥文件扩展名
            $publicRealPath = $publicfile->getRealPath();   //临时文件的绝对路径
            $priviteNewFilename = Auth::user()->mobile.'_merchant_private_key'.'.'.$priviteextendName;
            $publiteNewFilename = Auth::user()->mobile.'_merchant_public_key'.'.'.$publicfileExtensionName;
            $xtbKey = Auth::user()->mobile.'_xtb_key.pem';
            if(Storage::exists($priviteNewFilename)  || Storage::exists($publiteNewFilename) || Storage::exists($xtbKey)){ //如果文件已存在 删除源文件  重新进行上传
                Storage::delete($priviteNewFilename);
                Storage::delete($publiteNewFilename);
                Storage::delete($xtbKey);
            }
            $file = TMFile::getInstance();
            $file->execute(storage_path().'/cert/',$xtbKey,$request->get('xtbKey'));
            $isPrivate= Storage::disk('cert')->put($priviteNewFilename, file_get_contents($priviteRealPath));
            $isPublic = Storage::disk('cert')->put($publiteNewFilename, file_get_contents($publicRealPath));
            if($isPrivate && $isPublic){
                return JsonResponse::success();
            }
    }
}
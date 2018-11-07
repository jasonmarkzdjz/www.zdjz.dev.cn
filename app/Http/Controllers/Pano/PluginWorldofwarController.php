<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\CommonBls;
use Auth;
class PluginWorldofwarController extends Controller
{
    /**
     * AJAX 上传开场动画图标
     * @author Jason7 2018-05-29
     * @param  Request $request
     * @return array
     */
    public function ajaxUpload(Request $request)
    {
        $CommonBls = new CommonBls();
        if ($request->isMethod('post')) {
            // 验证规则
            $rules = [
                'file' => 'file|image|max:100',
            ];

            $messages = [
                'file.file' => '请先上传文件。',
                'file.image'  => '图片类型不正确。',
                'file.between' => '上传文件大小限制最大为100kb',
            ];

            // 验证数据
            \Validator::make($request->all(),$rules,$messages)->validate();

            // 获取上传文件
            $file = $request->file('file');
            $path = Auth::user()->id . '/plugin/worldofwar/images/';
            return $CommonBls->upload_file($file,$path);
        }
    }
}

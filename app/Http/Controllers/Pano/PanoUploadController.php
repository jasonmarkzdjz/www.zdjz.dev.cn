<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\CommonBls;
use Auth;
class PanoUploadController extends Controller
{
    /**
     * AJAX 上传全景图片
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
                'file' => 'file|image|dimensions:ratio=2/1',
            ];

            $messages = [
                'file.file' => '请先上传文件。',
                'file.image'  => '图片类型不正确。',
                'file.dimensions' => '请上传2：1全景图。',
            ];

            // 验证数据
            \Validator::make($request->all(),$rules,$messages)->validate();

            // 获取上传文件
            $file = $request->file('file');
            $path = Auth::user()->id . '/images/source/';
            return $CommonBls->upload_file($file,$path);
        }
    }
}

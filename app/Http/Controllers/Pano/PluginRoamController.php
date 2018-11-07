<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\CommonBls;
use App\Bls\Pano\PluginRoamCustomBls;
use Auth;
class PluginRoamController extends Controller
{
    /**
     * AJAX 上传用户自定义全局标注点
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
                // dimensions:min_width=200,min_height=100
                'file' => 'file|image|between:1,60',
            ];

            $messages = [
                'file.file' => '请先上传文件。',
                'file.image'  => '图片类型不正确。',
                'file.between' => '上传文件大小限制最大为60kb',
            ];

            // 验证数据
            \Validator::make($request->all(),$rules,$messages)->validate();

            // 获取上传文件
            $file = $request->file('file');
            $path = Auth::user()->id . '/plugin/roam/images/';
            return $CommonBls->upload_file($file,$path);
        }
    }

    /**
     * 获取小图列表
     * @author Jason7 2018-07-10
     * @return array
     */
    public function imgList(Request $request)
    {
        $id = $request->input('id');
        $PluginRoamCustomBls = new PluginRoamCustomBls();
        $result = $PluginRoamCustomBls->getRoamCustomList($id);
        $imgsInfo = [];
        foreach ($result as $key => $value) {
            $imgsInfo = $value['imgsInfo'];
        }
        return $imgsInfo;
    }

    /**
     * 自定义样式添加
     * @author Jason7 2018-07-09
     * @return [type] [description]
     */
    public function addPost(Request $request)
    {
        $input = $request->all();
        // 验证规则
        $rules = [
            'points' => 'min:1|max:8',
            'points.*' => 'required',
        ];
        \Validator::make($request->all(),$rules)->validate();
        $PluginRoamCustomBls = new PluginRoamCustomBls();
        $data = [
            'name' => '暂无', 
            'coverImg' => $input['points'][0], 
            'imgsInfo' => $input['points'], 
            'sort' => 100
        ];
        if ($PluginRoamCustomBls->saveRoamCustom($data)) {
            return array('msg'=>'添加成功','status'=>1);
        }

    }

    /**
     * 自定义样式保存
     * @author Jason7 2018-07-09
     * @return [type] [description]
     */
    public function editPost(Request $request)
    {
        $input = $request->all();
        // 验证规则
        $rules = [
            'points' => 'min:1|max:8',
            'points.*' => 'required',
            'id' => 'required',
        ];
        \Validator::make($request->all(),$rules)->validate();

        $data = [
            'name' => '暂无', 
            'coverImg' => $input['points'][0], 
            'imgsInfo' => $input['points'], 
            'sort' => 100
        ];
        $id = $input['id'];

        $PluginRoamCustomBls = new PluginRoamCustomBls();
        if ($PluginRoamCustomBls->saveRoamCustom($data,$id)) {
            return array('msg'=>'修改成功','status'=>1);
        }
    }

    public function delete(Request $request)
    {
        $rules = [
            'id' => 'required',
        ];

        $Validator = \Validator::make($request->all(),$rules);

        if ($Validator->fails()) {
            return array('msg' => $Validator->errors()->first(), 'status' => 0);
        }

        $PluginRoamCustomBls = new PluginRoamCustomBls();
        if ($PluginRoamCustomBls->deleteRoamCustom($request->input('id'))) {
             return array('msg'=>'删除成功','status'=>1);
        }
    }
}

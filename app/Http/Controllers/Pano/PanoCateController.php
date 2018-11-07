<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\model\PanoCateModel;
use App\Bls\Pano\PanoCateBls;
use Auth;

class PanoCateController extends Controller
{
    /**
     * 提交保存
     * @author Jason7 2018-05-25
     * @param  Request $request 
     * @return array
     */
    public function savePost(Request $request)
    {
        $rules = [
            'cateTitle' => 'required'
        ];

        $messages = [
            'cateTitle.required' => '分类标题不能为空。'
        ];

        $Validator = \Validator::make($request->all(),$rules,$messages);

        if ($Validator->fails()) {
            return array('msg' => $Validator->errors()->first(), 'status' => 0);
        }

        $id = $request->input('id',0);

        if (empty($id)) { // 添加操作
            $PanoCateModel = new PanoCateModel();
            $PanoCateModel->merchantId = Auth::user()->id;
        } else { // 修改操作
            $PanoCateModel = PanoCateModel::where('id',$id)->where('merchantId',Auth::user()->id)->first();
        }
        
        $PanoCateModel->cateTitle = $request->input('cateTitle');
        if (false !== $PanoCateModel->save()) {
            return array('msg'=>'保存成功','status'=>1,'saveid'=>$PanoCateModel->id);
        }
    }

    /**
     * 刪除分組
     * @author Jason7 2018-05-25
     * @param  Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $PanoCateBls = new PanoCateBls;
        if ($PanoCateBls->deleteCate($request->input('id',0)) > 0) {
            return array('msg'=>'分组刪除成功','status'=>1);
        }
    }
}

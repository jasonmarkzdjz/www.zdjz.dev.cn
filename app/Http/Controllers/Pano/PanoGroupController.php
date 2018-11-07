<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\model\PanoGroupModel;
use App\Bls\Pano\PanoGroupBls;
use Auth;
class PanoGroupController extends Controller
{


    /**
     * Ajax获取全景项目下的所有分組
     * @author Jason7 2018-05-25
     * @return array
     */
    public function list(Request $request)
    {
        $PanoGroupBls = new PanoGroupBls();
        $list = $PanoGroupBls->getGroupList($request->input('panoId',0));
        return $list->toArray();
    }

    /**
     * 創建全景项目分組
     * @author Jason7 2018-05-25
     * @return array
     */
    public function addPost(Request $request)
    {
        $groupTitle = $request->input('groupTitle');
        $PanoGroupModel = new PanoGroupModel;
        $PanoGroupModel->groupTitle = $groupTitle;
        $PanoGroupModel->merchantId = Auth::user()->id;
        if (false !== $PanoGroupModel->save()) {
            return array('msg'=>'添加成功','status'=>1);
        }
    }

    /**
     * 提交全景项目分组修改
     * @author Jason7 2018-05-25
     * @param  Request $request 
     * @return array
     */
    public function editPost(Request $request)
    {
        $id = $request->input('id',0);
        $groupTitle = $request->input('groupTitle');

        $Group = PanoGroupModel::where('id',$id)->where('merchantId',Auth::user()->id)->first();
        $Group->groupTitle = $groupTitle;
        if (false !== $Group->save()) {
            return array('msg'=>'修改成功','status'=>1);
        }
    }

    /**
     * 刪除全景项目分組
     * @author Jason7 2018-05-25
     * @param  Request $request
     * @return array
     */
    public function delete(Request $request,$id = 0)
    {
        $PanoGroupBls = new PanoGroupBls();
        if ($PanoGroupBls->deleteGroup($id) > 0) {
            return array('msg'=>'分组刪除成功','status'=>1);
        }
    }
}

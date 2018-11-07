<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\model\PanoNavModel;
use App\Bls\Pano\PanoNavBls;
use Auth;
class PanoNavController extends Controller
{
    /**
     * 导航列表
     * @author Jason7 2018-05-25
     * @return json
     */
    public function list()
    {
        $PanoNavBls = new PanoNavBls;
        $list = $PanoNavBls->getNavList();
        return $list->toArray();
    }

    /**
     * 創建导航
     * @author Jason7 2018-05-25
     * @return json
     */
    public function addPost(Request $request)
    {
        $Nav = new PanoNavModel;
        $navTitle = $request->input('navTitle');
        $navType = $request->input('navType');
        $panoId = $request->input('panoId');

        if ($navType == '2') {
            $Nav->navContent = $request->input('navContent');
        } else if ($navType == '3') {
            $Nav->navList = $request->input('navList');
        }
        
        $Nav->navTitle = $navTitle;
        $Nav->merchantId = Auth::user()->id;
        $Nav->panoId = $panoId;
        if (false !== $Nav->save()) {
            return array('msg'=>'添加成功','status'=>1);
        }
    }

    /**
     * 编辑分组
     * @author Jason7 2018-05-25
     * @param  Request $request 
     * @return json
     */
    public function edit(Request $request,$id = 0)
    {
        $info = PanoGroupModel::where('id',$id)->where('merchantId',Auth::user()->id)->firstOrFail();
        return $info->toArray();
    }

    /**
     * 提交修改
     * @author Jason7 2018-05-25
     * @param  Request $request 
     * @return json
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
     * 刪除分組
     * @author Jason7 2018-05-25
     * @param  Request $request
     * @return json
     */
    public function delete(Request $request,$id = 0)
    {
        $bls = new PanoGroupBls;
        if ($bls->deleteGroup($id) > 0) {
            return array('msg'=>'分组刪除成功','status'=>1);
        }
    }
}

<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\Model\TaskSceneModel;
use Auth;
class TaskSceneController extends Controller
{
    /**
     * 生成全景步骤 4
     * @author Jason7 2018-07-04
     * @param  Request $request
     * @return stdclass
     */
    public function list(Request $request)
    {
        $TaskSceneModel = new TaskSceneModel();
        return $TaskSceneModel->where('merchantId', Auth::user()->id)->where('panoId', $request->input('panoId'))->get();
    }
    /**
     * 生成全景步骤 4
     * @author Jason7 2018-07-04
     * @param  Request $request
     * @return array
     */
    public function batchMove(Request $request)
    {
        $TaskSceneModel = new TaskSceneModel();
        $scenes = $request->input('scenes');
        $groups = $request->input('groups');
        foreach ($scenes as $key => $value) {
            $TaskSceneModel->where('id',$value)->update(['groupId'=>$scenes[$key],'status' => 0]);
        }
        return array('msg'=>'选择成功','status'=>1);
    }
}

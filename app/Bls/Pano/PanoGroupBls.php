<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoGroupModel;
use Auth;
class PanoGroupBls
{
    /**
     * 获取分组列表
     * @author Jason7 2018-05-25
     * @return stdclass
     */
    public function getGroupList($panoId = 0)
    {
        return PanoGroupModel::where('merchantId',Auth::user()->id)->where('panoId',$panoId)->get();
    }

    /**
     * 删除全景分组
     * @author Jason7 2018-05-25
     * @param  it $id 
     * @return int
     */
    public function deleteGroup($id = 0)
    {
        return PanoGroupModel::where('merchantId',Auth::user()->id)->where('id',$id)->delete();
    }

    /**
     * 批量添加分组
     * @author Jason7 2018-06-13
     * @param  array $data 
     * @return bool
     */
    public function batchInsertGroup($data = [])
    {
        if (!isset($data['groupIds'])) return false;
        foreach ($data['groupIds'] as $key => $value) {
            $groupData[$key]['merchantId'] = Auth::user()->id;
            $groupData[$key]['panoId'] = $data['panoId'];
            $groupData[$key]['groupTitle'] = $value;
            $groupData[$key]['created_at'] = now();
            $groupData[$key]['updated_at'] = now();
        }

        $PanoGroupModel = new PanoGroupModel();
        return $PanoGroupModel->insert($groupData);
    }
}
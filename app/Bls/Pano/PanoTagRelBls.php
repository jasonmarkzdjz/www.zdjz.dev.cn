<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoTagRelModel;

class PanoTagRelBls
{
    /**
     * 批量写入标签关系表
     * @author Jason7 2018-05-30
     * @param  array $data
     * @return bool
     */
    public function batchInsertTagRel($data = [])
    {
        foreach ($data['tagIds'] as $key => $value) {
            $tagData[$key]['panoId'] = $data['panoId'];
            $tagData[$key]['tagId'] = $value;
            $tagData[$key]['created_at'] = now();
            $tagData[$key]['updated_at'] = now();
        }
        $PanoTagRelModel = new PanoTagRelModel();
        return $PanoTagRelModel->insert($tagData);
    }

    public function getTagRelList($panoId)
    {
        return PanoTagRelModel::where('panoId',$panoId)->get();
    }
}
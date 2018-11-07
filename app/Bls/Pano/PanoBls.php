<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoModel;
use App\Bls\Pano\Model\TaskSceneModel;
use Auth;

class PanoBls
{
    /**
     * 查询全景项目列表
     * @author Jason7 2018-06-07
     * @param  integer $cateId
     * @param  integer $pageSize
     * @param  string  $order
     * @param  string  $sort
     * @param  array   $searchData 
     * @return array
     */
    public function getPanoList($cateId=0 ,$searchData = [], $pageSize = 10, $order = 'id desc')
    {
        $PanoModel = PanoModel::query();

        if(!empty($searchData)) {

            if(isset($searchData['startTime']) && !empty($searchData['startTime'])) {
                $PanoModel->where('created_at', '>=', $searchData['startTime']);
            }

            if(isset($searchData['endTime']) && !empty($searchData['endTime'])) {
                $PanoModel->where('created_at', '<=', $searchData['endTime']);
            }

            if(isset($searchData['panoTitle']) && !empty($searchData['panoTitle'])) {
                $PanoModel->where('panoTitle', 'like', '%'.$searchData['panoTitle'].'%');
            }
        }
        return $PanoModel->where('merchantId',Auth::user()->id)->where('cateId',$cateId)->orderByRaw($order)->paginate($pageSize,['id','viewId','panoTitle','coverImg','cdnHost','isRd','likesCount','pvCount','status']);
    }

    /**
     * 写入pano表返回ID
     * @author Jason7 2018-06-13
     * @param  array $data
     * @return bool
     */
    public function insertPanoId($data = [])
    {
        // 插件默认配置
        $data['pluginConfig'] = 
        [
            'point' => 
            [
                "mode"=>"default",
                "default"=> "0",
            ],
            "roam" =>
            [
                "mode"=>"default",
                "default"=>"0"
            ],
            "nav" =>
            [
                "id"=>"0"
            ],
            "ctrbtn" =>
            [
                "id"=>"0"
            ],
            "loading" =>
            [
                "mode"=>"default",
                "default"=>"0",
                "custom" =>
                [
                    "bgcolor"=>"#000000",
                    "imgurl"=>"/ucenter/images/VREdit_fileimg_01.jpg"
                ]
            ],
            "worldofwar" =>
            [
                "status"=>"0",
                "imgurl"=>"/ucenter/images/VREdit_tab06_01.jpg",
                "bgcolor"=>"#000000",
                "alpha"=>"0",
                "sleeptime"=>"2.5",
                "loadtype"=>"1"
            ],
            "fnicon" =>
            [
                "id"=>"2"
            ]
        ];

        // 保存数据
        $PanoModel = new PanoModel();
        $PanoModel->viewId = $data['viewId'];
        $PanoModel->merchantId = Auth::user()->id;
        $PanoModel->panoTitle = $data['panoTitle'];
        $PanoModel->panoDesc = '暂无';
        $PanoModel->coverImg = asset('ucenter/images/scene-review.jpg'); // 封面图
        $PanoModel->isRd = $data['isRd']; // 允许推荐
        // 部分默认字段无需写入 ... 
        $PanoModel->provinceName = $data['provinceName']; // 省名称
        $PanoModel->provinceId = $data['provinceId']; // 省ID
        $PanoModel->cityName = $data['cityName']; // 城市名称
        $PanoModel->cityId = $data['cityId']; // 城市ID
        $PanoModel->districtName = $data['districtName']; // 街区名称
        $PanoModel->districtId= $data['districtId']; // 街区ID
        $PanoModel->agreeId= $data['agreeId']; // 协议ID
        $PanoModel->pluginConfig= $data['pluginConfig']; // 协议ID
        $PanoModel->save();
        return $PanoModel->id;
    }

    public function updatePano($data = [])
    {
        $Pano = PanoModel::find($data['id']);
        $Pano->panoTitle = $data['panoTitle'];
        $Pano->panoDesc = $data['panoDesc'];
        $Pano->coverImg = $data['coverImg'];
        $Pano->fnAllowList = isset($data['fnAllowList']) ? $data['fnAllowList'] : null;
        $Pano->pluginConfig = isset($data['pluginConfig']) ? $data['pluginConfig'] : null;
        $Pano->goodsConfig = isset($data['goodsConfig']) ? $data['goodsConfig'] : null;
        $Pano->navConfig = isset($data['navConfig']) ? $data['navConfig'] : null;
        return $Pano->save();
    }

    /**
     * 登记一个切图任务
     * @author Jason7 2018-06-06
     * @param  array $data
     * @param  int   $status 状态 0或者4
     * @return bool
     */
    public function submitTaskScene($data = [], $status = 4)
    {
        foreach ($data['scenePaths'] as $key => $value) {
            $TaskSceneData[$key]['merchantId'] = Auth::user()->id;
            $TaskSceneData[$key]['panoId'] = $data['panoId'];
            $TaskSceneData[$key]['groupId'] = $data['groupId'];
            $TaskSceneData[$key]['sceneTitle'] = $data['sceneTitles'][$key];
            $TaskSceneData[$key]['scenePath'] = $value;
            $TaskSceneData[$key]['cdnHost'] = $data['sceneCdnhosts'][$key];
            $TaskSceneData[$key]['status'] = 4;
            $TaskSceneData[$key]['created_at'] = now();
            $TaskSceneData[$key]['updated_at'] = now();
        }
        $TaskSceneModel = new TaskSceneModel();
        return $TaskSceneModel->insert($TaskSceneData);
    }
}
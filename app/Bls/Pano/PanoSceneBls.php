<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoSceneModel;

class PanoSceneBls
{

	/**
	 * 提交数据到场景表
	 * @author Jason7 2018-05-30
	 * @param  array $data 
	 * @return bool
	 */
	public function insertScene($data = [])
	{
		$PanoSceneModel = new PanoSceneModel();
		$PanoSceneModel->sceneName = $data['sceneName'];
		$PanoSceneModel->scenePath = $data['scenePath'];
		$PanoSceneModel->sceneSize = $data['sceneSize'];
		$PanoSceneModel->sceneThumbPath = $data['sceneThumbPath'];
		$PanoSceneModel->uniqueId = $data['uniqueId'];
		return $PanoSceneModel->save();
	}

	/**
	 * 获取场景图列表
	 * @author Jason7 2018-06-29
	 * @param  int $panoId 全景项目ID
	 * @return array
	 */
	public function getSceneList($panoId = 0)
	{
		return PanoSceneModel::where('panoId',$panoId)->get();
	}
}
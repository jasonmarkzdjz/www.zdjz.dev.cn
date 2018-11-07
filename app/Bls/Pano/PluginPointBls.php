<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginPointModel;

class PluginPointBls
{
	public function getPointList()
	{
		return PluginPointModel::get();
	}
}
<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoTagModel;

class PanoTagBls
{
	public function getTagList()
	{
		return PanoTagModel::get();
	}
}
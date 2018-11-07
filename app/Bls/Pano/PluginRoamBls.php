<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginRoamModel;

class PluginRoamBls
{
    public function getRoamList()
    {
        return PluginRoamModel::get();
    }
}
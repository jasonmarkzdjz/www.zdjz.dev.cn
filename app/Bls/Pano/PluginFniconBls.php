<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginFniconModel;

class PluginFniconBls
{
    public function getFniconList()
    {
        return PluginFniconModel::get();
    }
}
<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginLoadingModel;

class PluginLoadingBls
{
    public function getLoadingList()
    {
        return PluginLoadingModel::get();
    }
}
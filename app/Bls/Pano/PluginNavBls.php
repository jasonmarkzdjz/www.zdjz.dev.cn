<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginNavModel;

class PluginNavBls
{
    public function getNavList()
    {
        return PluginNavModel::get();
    }
}
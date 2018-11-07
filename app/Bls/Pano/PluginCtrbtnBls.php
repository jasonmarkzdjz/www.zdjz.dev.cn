<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginCtrbtnModel;

class PluginCtrbtnBls
{
    public function getCtrbtnList()
    {
        return PluginCtrbtnModel::get();
    }
}
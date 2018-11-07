<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginRoamCustomModel;
use Auth;

class PluginRoamCustomBls
{
    public function getRoamCustomList($id = 0)
    {
        $PluginRoamCustomModel = PluginRoamCustomModel::query();
        if ($id > 0) {
            $PluginRoamCustomModel->where('id',$id);
        }
        return $PluginRoamCustomModel->where('merchantId', Auth::user()->id)->get();
    }

    public function deleteRoamCustom($id = 0)
    {
        return PluginRoamCustomModel::where('merchantId', Auth::user()->id)->where('id',$id)->delete();
    }

    public function saveRoamCustom($data = [], $id = 0)
    {
        if ($id > 0) {
            $PluginRoamCustomModel = PluginRoamCustomModel::find($id);
        } else {
            $PluginRoamCustomModel = new PluginRoamCustomModel();
        }

        $PluginRoamCustomModel->name = $data['name'];
        $PluginRoamCustomModel->merchantId = Auth::user()->id;
        $PluginRoamCustomModel->coverImg = $data['coverImg'];
        $PluginRoamCustomModel->imgsInfo = $data['imgsInfo'];
        $PluginRoamCustomModel->sort = $data['sort'];
        return $PluginRoamCustomModel->save();
    }
}
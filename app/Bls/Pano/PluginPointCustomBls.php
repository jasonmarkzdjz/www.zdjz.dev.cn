<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PluginPointCustomModel;
use Auth;

class PluginPointCustomBls
{
    public function getPointCustomList($id = 0)
    {
        $PluginPointCustomModel = PluginPointCustomModel::query();
        if ($id > 0) {
            $PluginPointCustomModel->where('id',$id);
        }
        return $PluginPointCustomModel->where('merchantId', Auth::user()->id)->get();
    }

    public function deletePointCustom($id = 0)
    {
        return PluginPointCustomModel::where('merchantId', Auth::user()->id)->where('id',$id)->delete();
    }

    public function savePointCustom($data = [], $id = 0)
    {
        if ($id > 0) {
            $PluginPointCustomModel = PluginPointCustomModel::find($id);
        } else {
            $PluginPointCustomModel = new PluginPointCustomModel();
        }

        $PluginPointCustomModel->name = $data['name'];
        $PluginPointCustomModel->merchantId = Auth::user()->id;
        $PluginPointCustomModel->coverImg = $data['coverImg'];
        $PluginPointCustomModel->imgsInfo = $data['imgsInfo'];
        $PluginPointCustomModel->sort = $data['sort'];
        return $PluginPointCustomModel->save();
    }
}
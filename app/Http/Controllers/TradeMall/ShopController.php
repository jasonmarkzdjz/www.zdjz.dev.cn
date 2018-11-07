<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/7/4
 * Time: 14:29
 */
namespace App\Http\Controllers\TradeMall;

use App\Bls\Pano\PanoBls;
use App\Bls\Pano\PanoCateBls;
use App\Bls\TradeMall\Model\ShopBannerModel;
use App\Bls\TradeMall\Model\ShopModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use library\Service\Response\JsonResponse;
use library\Service\Upload\Upload;
use Auth;

class ShopController extends Controller{

    /**
     * 店铺信息
     *
     * */
    public function shopInfo(Request $request){
        if($request->isMethod('post')){
            $shop = new ShopModel();
            $input = Input::all();
            //判断店铺是否存在
            $shopModel = ShopModel::query()->where('mId',Auth::user()->id)->where('type',$input['type'])->first();
            if(!empty($shopModel)){
                $shop = ShopModel::find($shopModel->id);
            }
            $upobject = new Upload();
            $fileObject = $request->file('logofile');
            if($fileObject){
                $upload = $upobject->Image($fileObject);
                $upload = json_decode($upload,true);
                if(!$upload['code']){
                    return JsonResponse::error(0,$upload['message']);
                }
                $shop->logoOriginUrl = $upload['data']['originurl'];
                $shop->logoCdnUrl = $upload['data']['cdnurl'];
            }
            $shop->mId = Auth::user()->id;
            $shop->type=$input['type'];
            $shop->shopSn = \helper::getRandomString('8','vr');
            $shop->shopName = $input['shopname'];
            $shop->brief = $input['birf'];
            $shop->save();
            $bannerModel = ShopBannerModel::query()->where('shopId',$shop->getKey())->get(['id'])->toArray();
            if(!empty($bannerModel)){
                ShopBannerModel::destroy($bannerModel);
            }
            foreach ($input['banner64'] as $value){
                $bannObject = $upobject->base64Upload($value);
                $bannObject = json_decode($bannObject,true);
                if(!$bannObject['code']){
                    return JsonResponse::error(0,$bannObject['message']);
                }
                $shopBann = new ShopBannerModel();
                $shopBann->shopId =$shop->getKey();
                $shopBann->originalImage = $bannObject['data']['originurl'];
                $shopBann->remoteImage = $bannObject['data']['cdnurl'];
                $shopBann->ip = \helper::getClientIp();
                $shopBann->save();
            }
            return JsonResponse::success();
        }
        $shop = Auth::user()->shop;
        $shopBanner= [];
        if($shop){
            $shopBanner = ShopBannerModel::query()->where('shopId',$shop->id)->get();
        }
        $panoCatebls = new PanoCateBls();
        $panoCate = $panoCatebls->getCateList();
        return View::make('trademall.shop',['shop'=>$shop,'panoCate'=>$panoCate,'shopBanner'=>$shopBanner,'data'=>Input::all()]);
    }
    public function getPanoByCateId(){
        $cateId = Input::get('cateId');
        $panoBls = new PanoBls();
        $panolist = $panoBls->getPanoList($cateId);
        return JsonResponse::success($panolist);
    }
}
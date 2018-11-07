<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/7/9
 * Time: 13:46
 */
namespace App\Http\Controllers\TradeMall\House;
use App\Bls\TradeMall\HouseBls;
use App\Bls\TradeMall\Model\House\HouseModel;
use App\Bls\TradeMall\Model\IndoorImageModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use library\Service\Contst\Common\GoodsSourceTypeConst;
use library\Service\Response\JsonResponse;
use library\Service\Upload\Upload;
use Auth;
class HouseController extends Controller{

    /**
     *
     * 发布产品
     * */
    public function publish(Request $request){
        if($request->isMethod('post')){
            $input = Input::all();
            $houseModel = new HouseModel();
            $houseModel->shopId = Auth::user()->shop->id;
            $houseModel->resblock =$input['resblock'];
            $houseModel->type =$input['radios'];
            $houseModel->building =$input['building'];
            $houseModel->unit =$input['unit'];
            $houseModel->area = $input['area'];
            $houseModel->huXing = json_encode(array('room'=>$input['room'],'hall'=>$input['hall'],'toilet'=>$input['toilet']));
            $houseModel->expectprice = $input['expectprice'];
            $houseModel->storey = $input['storey'];
            $houseModel->storeyTotal = $input['storeyTotal'];
            $houseModel->decorate = $input['decorate'];
            $houseModel->evaluate = $input['evaluate'];
            $bannerImage = $input['banner64'];
            $houseModel->save();
            if($bannerImage){
                $upobject = new Upload();
                foreach ($bannerImage as $item){
                    $bannObject = $upobject->base64Upload($item);
                    $bannObject = json_decode($bannObject,true);
                    if(!$bannObject['code']){
                        return JsonResponse::error(0,$bannObject['message']);
                    }
                    $indorImage = new IndoorImageModel();
                    $indorImage->shopId = Auth::user()->shop->id;
                    $indorImage->productId = $houseModel->getKey();
                    if($input['radios'] == 1){
                        $indorImage->type = GoodsSourceTypeConst::HOMESTAY;
                    }else if($input['radios'] == 2){
                        $indorImage->type = GoodsSourceTypeConst::HOURSE;
                    }
                    $indorImage->originalImage = $bannObject['data']['originurl'];
                    $indorImage->remoteImage = $bannObject['data']['cdnurl'];
                    $indorImage->ip = \helper::getClientIp();
                    $indorImage->save();
                }
            }
        }
       return View::make('trademall.house.publish');
    }
    /**
     * 产品列表
     *
     * */
    public function Index(Request $request){
        $input = Input::all();
        $searchData['startTime'] = !empty($input['startTime']) ? $input['startTime'] : '';
        $searchData['endTime'] = !empty($input['endTime']) ? $input['endTime'] : '';
        $searchData['type'] = !empty($input['type']) ? $input['type'] : 1;
        $searchData['status'] = isset($input['status']) ? $input['status'] : 1;
        $bls = new HouseBls();
        $houselist = $bls->getHouseList($searchData);
        !empty($searchData['startTime']) ? $houselist->appends('startTime',$searchData['startTime']) : '';
        !empty($searchData['endTime']) ? $houselist->appends('endTime',$searchData['endTime']) : '';
        !empty($searchData['type']) ? $houselist->appends('type',$searchData['type']) : '';
        if(!empty($houselist)){
            foreach ($houselist as $k=>$item){
                $houselist[$k]['banner'] = IndoorImageModel::query()->where('productId',$item->id)->first();
            }
        }
        return View::make('trademall.house.index',['data'=>$houselist,'searchData'=>$searchData]);
    }
}
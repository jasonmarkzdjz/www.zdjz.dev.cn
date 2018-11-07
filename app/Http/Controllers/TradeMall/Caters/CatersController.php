<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/24
 * Time: 13:33
 */
namespace App\Http\Controllers\TradeMall\Caters;

use App\Bls\Trademall\CatersBls;
use App\Bls\TradeMall\Model\Caters\CaterGoodsImagesModel;
use App\Bls\TradeMall\Model\Caters\CaterGoodsModel;
use App\Bls\Trademall\Model\Caters\CatersImageModel;
use App\Bls\Trademall\Model\Caters\CatersModel;
use App\Bls\TradeMall\Model\IndoorImageModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Auth;
use library\Service\Contst\Common\GoodsSourceTypeConst;
use library\Service\Contst\Common\OrderTypeConst;
use library\Service\Response\JsonResponse;
use library\Service\Upload\Upload;

class CatersController extends Controller{

    use CatersTraits;

    protected $errorMessage = [
        'realPicture.required'=>'请上传真实照片',
        'location.required'=>'请选择地址',
        'catersName.required'=>'请输入票务名称',
        'catersPrice.required'=>'请输入价格',
        'catersTime.required'=>'请输入时间',
        'introduce.required'=>'请输入详细介绍',
    ];
    protected $errorValidate = [
        'realPicture'=>'required',
        'location'=>'required',
        'catersName'=>'required',
        'catersPrice'=>'required',
        'catersTime'=>'required',
        'introduce'=>'required',
    ];

    /**
     * @author:发布列表
     * @date:2018/05/20
     * @return object
     * */
    public function getCatersList(Request $request){
        $searchForm['mId'] = Auth::user()->id;
        $searchForm['catersName'] = !empty($request->get('catersame')) ? $request->get('catersame'): '';
        $searchForm['startTime'] = !empty($request->get('starttime')) ? $request->get('starttime') : '';
        $searchForm['endTime'] = !empty($request->get('endtime')) ?$request->get('endtime'):'';
        $searchForm['status'] = $request->exists('status') ? $request->get('status') :1;
        $bls = new CatersBls();
        $caterResult = $bls->getCatersList($searchForm);
        foreach ($caterResult as $k=>$cater){
            $caterResult[$k]['banner'] = IndoorImageModel::query()->where('productId',$cater->id)->first();
        }
        return View::make('trademall.caters.index',['data'=>$caterResult,'searchData'=>$searchForm]);
    }
    /**
     * @author jason
     * @date 2018/05/20
     * @return object
     * @desc 详情信息
     * */
    public function catersDetail(Request $request){
        $catersId = intval($request->get('catersId')) > 0 ? intval($request->get('catersId')) : 0;
        $bls = new CatersBls();
        if($catersId){
            $caters = $bls->getCaters($catersId);
            $catersTimeSlot= !is_null(json_decode($caters->caterTime,true)) ? json_decode($caters->caterTime,true) : [];
            $caters->catersAm = !empty($catersTimeSlot['catersAm']) ? $catersTimeSlot['catersAm'] : '';
            $caters->catersPm = !empty($catersTimeSlot['catersPm']) ? $catersTimeSlot['catersPm'] : '';
            $catersPriceSlot = !is_null(json_decode($caters->catersPrice,true)) ? json_decode($caters->catersPrice,true):[];
            $caters->adultPrice = !empty($catersPriceSlot['adultPrice']) ? $catersPriceSlot['adultPrice'] : '';
            $caters->childrenPrice = !empty($catersPriceSlot['childrenPrice']) ? $catersPriceSlot['childrenPrice'] : '';
            $caters->studentPrice = !empty($catersPriceSlot['studentPrice']) ? $catersPriceSlot['studentPrice']:'';
        }
        return View::make('merchant.trademall.cartes.detail',['data'=>$caters]);
    }

    /**
     * @author:jason
     * @desc 发布产品
     *
     * */
    public function  publishCater(Request $request){
        $input = Input::all();
        if($request->isMethod('get')){
            if(isset($input['step']) && !empty($input['step'])){
                return View::make('trademall.caters.publish_step'.$input['step']);
            }
            return View::make('trademall.caters.publish');
        }
        if(isset($input['step']) && !empty($input['step'])){
            $key='merchantcaterspublis:'.Auth::user()->mobile.Auth::user()->shop->id.$input['step'];
            if(isset($input['step']) && !empty($input['step'])){
                Cache::store('redis')->put($key,$input,5);
            }
            return $this->success();
        }
        $upobject = new Upload();
        if(Cache::store('redis')->get('merchantcaterspublis:'.Auth::user()->mobile.Auth::user()->shop->id.'2')){
            $data = Cache::store('redis')->get('merchantcaterspublis:'.Auth::user()->mobile.Auth::user()->shop->id.'2');
            $caterModel = new CatersModel();
            $caterModel->mId = Auth::user()->id;
            $caterModel->shopId = Auth::user()->shop->id;
            $caterModel->location = $data['sever_add'];
            $caterModel->ip = \helper::getClientIp();
            $caterModel->save();
            foreach ($data['banner64'] as $value){
                $bannObject = $upobject->base64Upload($value);
                $bannObject = json_decode($bannObject,true);
                if(!$bannObject['code']){
                    return JsonResponse::error(0,$bannObject['message']);
                }
                $indorImage = new IndoorImageModel();
                $indorImage->shopId = Auth::user()->shop->id;
                $indorImage->productId = $caterModel->getKey();
                $indorImage->type = GoodsSourceTypeConst::CATES;
                $indorImage->originalImage = $bannObject['data']['originurl'];
                $indorImage->remoteImage = $bannObject['data']['cdnurl'];
                $indorImage->ip = \helper::getClientIp();
                $indorImage->save();
            }

            foreach ($input['foodName'] as $k=>$item){
                $goods = new CaterGoodsModel();
                $goods->productId =$caterModel->getKey();
                $goods->goodsSn = \helper::getRandomString(8);
                $goods->goodsName = $input['foodName'][$k];
                $goods->goodsPrice = $input['foodPrice'][$k];
                $goods->goodsSpec = '份';
                $goods->save();
                $googsImage = $upobject->Image($input['file0'][$k]);
                $googsImage = json_decode($googsImage,true);
                if(!$googsImage['code']){
                    return JsonResponse::error(0,$googsImage['message']);
                }
                $goodsImageModel = new CaterGoodsImagesModel();
                $goodsImageModel->goodsId = $goods->getKey();
                $goodsImageModel->goodsImg = $googsImage['data']['originurl'];
                $goodsImageModel->goodsCdnImg = $googsImage['data']['cdnurl'];
                $goodsImageModel->ip = \helper::getClientIp();
                $goodsImageModel->save();
            }
        }
        //确认发布
//        print_r($input);
//
//        $input = Input::all();
//        $validate = Validator::make($input,$this->errorMessage,$this->errorValidate);
//        if($validate->fails()){
//            return $this->retError(0,'验证错误',$validate->errors()->toArray());
//        }
//        $cater = new CatersModel();
//        $cater->mid = Auth::user()->id;
//        $cater->location = $input['location'];
//        $cater->catersName = $input['catersName'];
//        $catersPrice['adultPrice'] = !empty($input['adultPrice']) ? $input['adultPrice'] : '';
//        $catersPrice['childrenPrice'] = !empty($input['childrenPrice']) ? $input['childrenPrice'] : '';
//        $catersPrice['studentPrice'] = !empty($input['studentPrice']) ? $input['studentPrice'] : '';
//        $cater->catersPrice = json_encode($catersPrice);
//        $catersTime['catersAm'] = !empty($input['catersAm']) ? $input['catersAm'] : '';
//        $catersTime['catersAm']= !empty($input['catersPm']) ? $input['catersPm'] : '';
//        $cater->catersTime  = json_encode($catersTime);
//        $cater->introduce = $input['introduce'];
//        $cater->isDistribution = $input['isDistribution'];
//        $cater->perdozen = $input['perdozen'];
//        $cater->ip = \helper::getClientIp();
//        $caterId = $cater->save();
//        //添加产品图片
//        if(is_array($input::get('realPicture'))){
//            foreach ($input::get('realPicture') as $v){
//                $cater = new CatersImageModel();
//                $cater->catersId = $caterId;
//                $cater->originalImage = $originalImage;
//                $cater->remoteImage = $remoteImage;
//                $cater->ip= \helper::getClientIp();
//            }
//        }

        return $this->success();
    }

    /**
     * 上下架商品
     *
     * */
    public function setPublish(){
        $cId = Input::get('cId');
        if(!intval($cId)){
            return JsonResponse::error(0,'参数错误');
        }
        $status = Input::get('status');
        $bls = new CatersBls();
        $pubish = $bls->setInventory($cId,$status);
        if($pubish){
            return JsonResponse::success();
        }
        return JsonResponse::error(0,'操作失败');
    }
}
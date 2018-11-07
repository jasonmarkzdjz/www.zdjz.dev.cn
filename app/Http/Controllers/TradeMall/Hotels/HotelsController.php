<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/28
 * Time: 13:49
 */
namespace App\Http\Controllers\TradeMall\Hotels;
use App\Bls\Trademall\HotelsBls;
use App\Bls\Trademall\Model\Hotels\HotelImageModel;
use App\Bls\Trademall\Model\Hotels\HotelsModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use library\Service\Common\CacheConst;
use library\Service\Contst\Common\HotelsConst;
use Auth;
use library\Service\Response\JsonResponse;

class HotelsController extends Controller {

    protected $errorRoomMessage = [
        'checkInType.required'=>'请上传真实照片',
        'houseArea.required'=>'请选择地址',
        'houseType.required'=>'请输入票务名称',
        'toilet.required'=>'请输入价格',
        'livableNum.required'=>'请输入时间',
        'bedtypeInfo.required'=>'请输入详细介绍',
        'bedInstead.required'=>'请输入详细介绍',
    ];
    protected $errorRoomValidate = [
        'checkInType'=>'required',
        'houseArea'=>'required',
        'houseType'=>'required',
        'toilet'=>'required',
        'livableNum'=>'required|numeric',
        'bedtypeInfo'=>'required',
        'bedInstead'=>'required'
    ];

    protected $errorRoomDescValidate = [
        'hotelTitile'=>'required',
        'indivialityDesc'=>'required',
        'insideDesc'=>'required',
        'trafficDesc'=>'required',
        'peripheryDesc'=>'required',
    ];

    protected $errorRoomDescMessage = [
        'hotelTitile.required'=>'请输入房源标题',
        'indivialityDesc.required'=>'请输入个性描述',
        'insideDesc.required'=>'请输入内部描述',
        'trafficDesc.required'=>'请输入交通情况',
        'peripheryDesc.required'=>'请输入周边介绍',
    ];

    protected $errorRoomAssortValidate = [
        'bathroom'=>'required',
        'electric'=>'required',
        'facilities'=>'required',
        'specialFacil'=>'required',
        'demand'=>'required',
    ];

    protected $errorRoomAssortMessage = [
        'bathroom.required'=>'请选择卫浴',
        'electric.required'=>'请选择电器',
        'facilities.required'=>'请选择设施',
        'specialFacil.required'=>'请选择特殊设施',
        'demand.required'=>'请选择要求',
    ];


    protected $errorRoomPriceRuleValidate = [
        'dailyRent'=>'required',
        'timeRent'=>'required',
        'deposit'=>'required',
        'tradRules'=>'required',
        'refundsRules'=>'required',
        'minChe'=>'numeric|min:1',
    ];

    protected $errorRoomPriceRuleMessage = [
        'dailyRent.required'=>'日租价格不能为空',
        'timeRent.required'=>'时租价格不能为空',
        'deposit.required'=>'押金不能为空',
        'tradRules.required'=>'交易规则不能为空',
        'refundsRules.required'=>'退款规则不能为空',
        'minChe.numeric'=>'入住时间只能为整数',
        'minChe.min'=>'入住时间最少为1天',
    ];

    use  HotelsTraits;

    public function getHotelsList(Request $request){
        $searchForm['merchantId'] = Auth::user()->id;
        $searchForm['hotelsName'] = !empty($request->get('hotelsname')) ? $request->get('hotelsname'): '';
        $searchForm['startTime'] = !empty($request->get('starttime')) ? $request->get('starttime') : '';
        $searchForm['endTime'] = !empty($request->get('endtime')) ?$request->get('endtime'):'';
        $bls = new HotelsBls();
        $ticketsResult = $bls->getHotelsByList($searchForm);
        foreach($ticketsResult as $k=>$items){
            $ticketsResult[$k]->checkInTypeDesc=HotelsConst::getDescByItem($items->checkInType);
        }
        !empty($searchForm['hotelsName']) ? $ticketsResult->appends('hotelsname',$searchForm['hotelsName']):'';
        !empty($searchForm['startTime']) ? $ticketsResult->appends('starttime',$searchForm['starttime']):'';
        !empty($searchForm['endTime']) ? $ticketsResult->appends('endtime',$searchForm['endtime']):'';

        return View::make('merchant.trademall.hotels.hotelslist',['data'=>$ticketsResult]);
    }

    /**
     * 发布酒店产品
     *
     * */
    public function publisRoomhHotel(Request $request){
        if($request->isMethod('get')){
            return View::make('merchant.trademall.hotels.publish');
        }
        $input = Input::all();
        $validate = Validator::make($input,$this->errorRoomMessage,$this->errorRoomValidate);
        if($validate->fails()){
            return $this->retError(0,'验证错误',$validate->errors()->toArray());
        }
        Cache::store('redis')->set(CacheConst::MERCHANT_TARADETAMLL.'hotelroom:'.Auth::user()->id.Auth::user()->mobile,serialize($validate));
        return $this->success();
    }
    /**
     *
     * 房源描述
     *
     * */
    public function publisRoomdescribe(Request $request){
        if($request->isMethod('get')){
            return View::make('merchant.trademall.hotels.roomdesc');
        }
        $input = Input::all();
        $validate = Validator::make($input,$this->errorRoomDescMessage,$this->errorRoomDescValidate);
        if($validate->fails()){
            return $this->retError(0,'验证错误',$validate->errors()->toArray());
        }
        Cache::store('redis')->set(CacheConst::MERCHANT_TARADETAMLL.'hotelrommdesc:'.Auth::user()->id.Auth::user()->mobile,serialize($validate));
        return $this->success();
    }

    /**
     * 配套设施
     *
     * */
    public function publisRoomAssort(Request $request){
        if($request->isMethod('get')){
            return View::make('merchant.trademall.hotels.assort');
        }
        $input = Input::all();
        $data['bathroom'] = !empty($input['bathroom ']) ? $input['bathroom '] :'';
        $data['electric'] = !empty($input['electric']) ? $input['electric'] : '';
        $data['demand'] = !empty($input['demand']) ? $input['demand']:'';
        $data['specialFacil'] = !empty($input['specialFacil']) ? !$input['specialFacil'] : '';
        $validate = Validator::make($data,$this->errorRoomAssortMessage,$this->errorRoomAssortValidate);
        if($validate->fails()){
            return $this->retError(0,'验证错误',$validate->errors()->toArray());
        }
        Cache::store('redis')->set(CacheConst::MERCHANT_TARADETAMLL.'hotelroomassort:'.Auth::user()->id.Auth::user()->mobile,serialize($data));
        return $this->success();
    }

    /**
     *
     *价格规则
     *
     * */
    public function publishPriceRule(Request $request){
        if($request->isMethod('get')){
            return View::make('merchant.trademall.hotels.pricerule');
        }
        $input = Input::all();
        $validate = Validator::make($input,$this->errorRoomPriceRuleMessage,$this->errorRoomPriceRuleValidate);
        if($validate->fails()){
            return $this->retError(0,'验证错误',$validate->errors()->toArray());
        }
        $hotelroom = unserialize(Cache::store('redis')->get(CacheConst::MERCHANT_TARADETAMLL.'hotelroom:'.Auth::user()->id.Auth::user()->mobile));
        $hotel = new HotelsModel();
        $hotel->location = $hotelroom['location'];
        $hotel->checkInType = $hotelroom['checkInType'];
        $hotel->houseArea = $hotelroom['houseArea'];
        $hotel->houseType = $hotelroom['houseType'];
        $hotel->toilet = $hotelroom['toilet'];
        $hotel->livableNum = $hotelroom['livableNum'];
        $hotel->bedtypeInfo = $hotelroom['bedtypeInfo'];
        $hotel->bedInstead = $hotelroom['bedInstead'];
        //房源描述
        $roomdesc = unserialize(Cache::store('redis')->get(CacheConst::MERCHANT_TARADETAMLL.'hotelrommdesc:'.Auth::user()->id.Auth::user()->mobile));
        $hotel->hotelTitile = $roomdesc['hotelTitile'];
        $hotel->indivialityDesc = $roomdesc['indivialityDesc'];
        $hotel->insideDesc = $roomdesc['insideDesc'];
        $hotel->trafficDesc = $roomdesc['trafficDesc'];
        $hotel->peripheryDesc = $roomdesc['peripheryDesc'];
        //配套
        $assort = unserialize(Cache::store('redis')->get(CacheConst::MERCHANT_TARADETAMLL.'hotelroomassort:'.Auth::user()->id.Auth::user()->mobile));
        $hotel->bathroom = $assort['bathroom'];
        $hotel->electric = $assort['electric'];
        $hotel->facilities = $assort['facilities'];
        $hotel->specialFacil = $assort['specialFacil'];
        $hotel->demand = $assort['demand'];
        //价格规则
        $hotel->dailyRent = $input['dailyRent'];
        $hotel->timeRent = $input['timeRent'];
        $hotel->deposit = $input['deposit'];
        $hotel->isAddGuest = $input['isAddGuest'];
        $hotel->premium = $input['premium'];
        $hotel->tradRules = $input['tradRules'];
        $hotel->refundsRules = $input['refundsRules'];
        $hotel->minCheckIn = $input['minCheckIn'];
        $hotel->otherDemand = $input['otherDemand'];
        $hotel->isRecceptAbroad = $input['isRecceptAbroad'];
        $hotel->save();
        //添加产品图片
        $hotelImage = new HotelImageModel();
        $hotelImage->originalImage = $originalImage;
        $hotelImage->remoteImage = $remoteImage;
        $hotelImage->ip = \helper::getClientIp();
        $hotelImage->save();
        //发布成功清除上级缓存
        Cache::store('redis')->delete(CacheConst::MERCHANT_TARADETAMLL.'hotelroom:'.Auth::user()->id.Auth::user()->mobile);
        Cache::store('redis')->delete(CacheConst::MERCHANT_TARADETAMLL.'hotelrommdesc:'.Auth::user()->id.Auth::user()->mobile);
        Cache::store('redis')->delete(CacheConst::MERCHANT_TARADETAMLL.'hotelroomassort:'.Auth::user()->id.Auth::user()->mobile);

    }

    /**
     * 上下架商品
     *
     * */
    public function setPublish(){
        $hId = Input::get('cId');
        if(!intval($hId)){
            return JsonResponse::error(0,'参数错误');
        }
        $bls = new HotelsBls();
        $pubish = $bls->setHotels($hId);
        if($pubish){
            return JsonResponse::success();
        }
        return JsonResponse::error(0,'操作失败');
    }
}
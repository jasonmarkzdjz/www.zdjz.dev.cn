<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/24
 * Time: 20:17
 */
namespace App\Http\Controllers\TradeMall\Tickets;
use App\Bls\Trademall\Model\Tickets\TicketImagesModel;
use App\Bls\Trademall\Model\Tickets\TicketsModel;
use App\Bls\Trademall\TicketsBls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Auth;
class TicketsController extends Controller {

    use TickersTraits;

    protected $errorMessage = [
        'realPicture.required'=>'请上传真实照片',
        'location.required'=>'请选择地址',
        'ticketsName.required'=>'请输入票务名称',
        'ticketsPrice.required'=>'请输入价格',
        'ticketsTime.required'=>'请输入时间',
        'introduce.required'=>'请输入详细介绍',
    ];
    protected $errorValidate = [
        'realPicture'=>'required',
        'location'=>'required',
        'ticketsName'=>'required',
        'ticketsPrice'=>'required',
        'ticketsTime'=>'required',
        'introduce'=>'required',
        'perdozen'=>'required',
    ];

    /**
     * @author Jason
     * @data 2018/05//24
     * @desc 票务信息列表
     * @return object
     * */
    public function getTicketsList(Request $request){

        $searchForm['ticketsName'] = !empty($request->get('ticketsName')) ? $request->get('ticketsName'): '';
        $searchForm['startTime'] = !empty($request->get('startTime')) ? $request->get('startTime') : '';
        $searchForm['endTime'] = !empty($request->get('endTime')) ?$request->get('endTime'):'';
        $bls = new TicketsBls();
        $ticketsResult = $bls->getTicketsList($searchForm);

        return View::make('trademall.tickets.index',['data'=>$ticketsResult]);
    }

    /**
     * @author: jason
     * @desc 获取票务信息
     * @data 2018/05/20
     * */
    public function getTickets(Request $request){
        $ticketsId = intval($request->get('ticketsId')) > 0 ? intval($request->get('ticketsId')) : 0;
        $bls = new TicketsBls();
        if($ticketsId){
            $tickets= $bls->getTickets($ticketsId);
            $ticketsTimeSlot= !is_null(json_decode($tickets->caterTime,true)) ? json_decode($tickets->caterTime,true) : [];
            $tickets->catersAm = !empty($ticketsTimeSlot['catersAm']) ? $ticketsTimeSlot['catersAm'] : '';
            $tickets->catersPm = !empty($ticketsTimeSlot['catersPm']) ? $ticketsTimeSlot['catersPm'] : '';
            $ticketsPriceSlot = !is_null(json_decode($tickets->ticketsPrice,true)) ? json_decode($tickets->ticketsPrice,true):[];
            $tickets->adultPrice = !empty($ticketsPriceSlot['adultPrice']) ? $ticketsPriceSlot['adultPrice'] : '';
            $tickets->childrenPrice = !empty($ticketsPriceSlot['childrenPrice']) ? $ticketsPriceSlot['childrenPrice'] : '';
            $tickets->studentPrice = !empty($ticketsPriceSlot['studentPrice']) ? $ticketsPriceSlot['studentPrice']:'';
        }
        return View::make('trademall.tickets.detail',['data'=>$tickets]);
    }

    /**
     * @author:jason
     * @desc 发布产品
     *
     * */
    public function  publishTickets(Request $request){
        if($request->isMethod('get')){
            return View::make('trademall.ticket.publish');
        }
        $input = Input::all();
        $validate = Validator::make($input,$this->errorMessage,$this->errorValidate);
        if($validate->fails()){
            return $this->retError(0,'验证错误',$validate->errors()->toArray());
        }
        $ticket = new TicketsModel();
        $ticket->mid = Auth::user()->id;
        $ticket->location = $input['location'];
        $ticket->ticketsName = $input['ticketsName'];
        $ticketsPrice['adultPrice'] = !empty($input['adultPrice']) ? $input['adultPrice'] : '';
        $ticketsPrice['childrenPrice'] = !empty($input['childrenPrice']) ? $input['childrenPrice'] : '';
        $ticketsPrice['studentPrice'] = !empty($input['studentPrice']) ? $input['studentPrice'] : '';
        $ticket->ticketsPrice = json_encode($ticketsPrice);
        $ticketsTime['catersAm'] = !empty($input['catersAm']) ? $input['catersAm'] : '';
        $ticketsTime['catersAm']= !empty($input['catersPm']) ? $input['catersPm'] : '';
        $ticket->ticketsTime  = json_encode($ticketsTime);
        $ticket->introduce = $input['introduce'];
        $ticket->isDistribution = $input['isDistribution'];
        $ticket->perdozen = $input['perdozen'];
        $ticket->ip = \helper::getClientIp();
        $ticketId = $ticket->save();
        //添加产品图片
        if(is_array($input::get('realPicture'))){
            foreach ($input::get('realPicture') as $v){
                $tickets = new TicketImagesModel();
                $tickets->ticketId = $ticketId;
                $tickets->originalImage = $originalImage;
                $tickets->remoteImage = $remoteImage;
                $tickets->ip= \helper::getClientIp();
            }
        }
        return $this->success();
    }
}
<?php
namespace App\Http\Controllers\Activity\View;


use Auth;
use Illuminate\Http\Request;
use App\Bls\Activity\Config;
use App\Bls\Activity\Redbox;
use App\Bls\Activity\TipImg;
use App\Bls\Activity\Record;
use App\Bls\Activity\Associat;
use App\Bls\Activity\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * 
 */
class RedController extends Controller
{	

	// 红包首页
	public function index(Request $request)
	{
		$input = Input::all();
		$code = $request->get('code');
		$mobile = $request->get('mobile');
		$actRed = 'act_red_'.$request->get('a');
		!$request->get('a') && exit('非法访问！');
		$info = Config::find($request->get('a'));
		!$info->status && exit('活动不存在或已下架！');		
		if ($request->isMethod('post')) {
			if (!$code || !$mobile)
				return $this->reJson(0, '手机号或验证码不能为空！');
			if (!$this->checkCode($mobile, $code))
				return $this->reJson(0, '验证码或手机号错误！');
			session([$actRed=>$mobile]);			
			if (session($actRed)) {
				return $this->reJson(1, '验证成功！');
			} else {
				return $this->reJson(0, '验证失败！');
			}
		}
		echo session($actRed);
		return view('home.act.red', ['info'=>$info, 'isLog'=>session($actRed)]);
	}


	// 领取红包
	public function distribute(Request $request)
	{
		$configId = $request->get('a');
		$actRed = 'act_red_'.$configId;

		if (!$mobile = session($actRed))
			return $this->reJson(0, '未验证手机号！');

		return $this->calculate($mobile, $configId);
		// return $mobile;

		// $flight = Record::create([

		// 			'mobile'		=>	$mobile,
		// 			'record_id'		=>	$this->getNid(),
		// 			'config_id'		=>	$request->get('a'),

		// 	]);
		// if ($flight) {
		// 	$isLog = true;
		// 	return $this->reJson(1, '验证成功！', ['rid'=>$flight->id]);
		// } else {
		// 	return $this->reJson(0, '验证失败！');
		// }

	}


	// 红包分发
	public function calculate($mobile, $cid)
	{
		$cInfo = Config::find($cid);	//配置信息
		$award = Redbox::where(['config_id'=>$cid]);	//奖项信息
		$record = Record::where(['config_id'=>$cid, 'mobile'=>$mobile]);	//活动记录
		$strTime = strtotime(date('Y-m-d'));
		// if (strtotime($cInfo->endTime) < $strTime) return $this->reJson(0, '活动已结束！');
		// if (strtotime($cInfo->startTime) > $strTime) return $this->reJson(0, '活动未开始！');
		// if ($cInfo->people_num !== 0) {
		// 	if ($cInfo->people_num <= $cInfo->take_num)
		// 		return $this->reJson(0, '活动人数已满，请等候下次活动！');
		// }
		$allAward = $award->get();
		$arr = [];		//奖品数组
		foreach ($allAward as $key => $val) {
			$arr[$key] = array_fill(0, $val['num'], $val['money']);
		}
		$merge = call_user_func_array('array_merge',$arr);
		shuffle($merge);
		var_dump($merge);
		



	}


	// 验证码（伪代码，测试用）
	public function reCode(Request $request)
	{
		$prefix = 'act_red';
		$code = rand(100000, 999999);
		$mobile = $request->get('mobile');
		$sesKey = $prefix . '_' . trim($mobile);
		$sesVal = ['code'=>$code, 'time'=>time()];
		if (!$request->get('mobile'))
			return $this->reJson(0, '手机号不能为空！');
		if (session($sesKey) !== null) {
			if ((time() - session($sesKey)['time']) < 60) {
				return $this->reJson(0, '60秒后获取验证码！');
			}
			session([$sesKey=>$sesVal]);
			return $this->reJson(1, 'SUCCESS', ['code'=>session($sesKey)['code']]);
		}
		session([$sesKey=>$sesVal]);
		return $this->reJson(1, 'SUCCESS', ['code'=>session($sesKey)['code']]);
	}

	// 检查验证码
	public function checkCode($mobile, $code)
	{

		$sesKey = 'act_red' . '_' . trim($mobile);

		return (session($sesKey)['code'] == trim($code)) ? true : false;

	}

	// 返回Json
	public function reJson($code, $message, $data = [])
	{
		return ['code'=>$code, 'message'=>$message, 'data'=>$data];
	}

	// 不重复ID(按时间戳，活动记录表record_id用)
	public function getNid()
	{
		list($s, $t) = explode(' ', microtime());
		return date('Ymd',$t).substr($s, 2, 6).rand(10,99);
	}

}
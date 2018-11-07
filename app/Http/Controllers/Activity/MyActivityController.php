<?php
namespace App\Http\Controllers\Activity;

use Auth;
use Illuminate\Http\Request;
use App\Bls\Activity\Config;
use App\Bls\Activity\Associat;
use App\Bls\Activity\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
/**
 * 活动
 */
class MyActivityController extends Controller
{
	//我的活动
	public function index(Request $request) {
		$input = Input::all();
		$userId = Auth::user()->id;
		$list = Config::where(['merchantID'=>$userId])->get();
		$delCnt = Config::query()->where(['merchantID'=>$userId])->count();

		return view('activity.my',compact('list','delCnt'));
	}

	// 发布
	public function release(Request $request)
	{
		$type = 1;

		$error = '发布失败！';

		$success = '发布成功！';

		$input = Input::all();

		$actCon = Config::find($input['actid']);

		if (isset($input['type'])) {
			if ($input['type'] == 'n') {
				$type = 0;
				$error = '下架失败！';
				$success = '下架成功！';
			}
		}

		if ($actCon->update(['status'=>$type])){

			return ['code'=>1,'message'=>$success];

		}

		return ['code'=>0,'message'=>$error];

	}

	// 删除
	public function destroy(Request $request){
		$input = Input::all();
		$actCon = Config::query()->where('id',$input['actid'])->first();
		if (isset($input['type'])) {
			if ($input['type'] == 'y') {
				if ($actCon->delete()){
					return ['code'=>1,'message'=>'删除成功！'];
				}
			}
			if ($actCon->restore()) {
				return ['code'=>1,'message'=>'恢复成功！'];
			}
		}
		return ['code'=>0,'message'=>'操作失败！'];

	}

}
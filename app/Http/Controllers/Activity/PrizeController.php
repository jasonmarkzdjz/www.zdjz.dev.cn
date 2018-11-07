<?php
namespace App\Http\Controllers\Activity;

use Auth;
use App\Bls\Activity\Prize;
use Illuminate\Http\Request;
use App\Bls\Activity\Associat;
use App\Bls\Activity\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
/**
 * 活动
 */
class PrizeController extends Controller
{

	//奖品管理
	public function index(Request $request)
	{

		return view('activity.prize');
	}
	
}
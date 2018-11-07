<?php
namespace App\Http\Controllers\Activity;

use Auth;
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
class InspectionController extends Controller
{
	//核销管理
	public function index(Request $request)
	{
		return view('activity.inspect');
	}

	
}
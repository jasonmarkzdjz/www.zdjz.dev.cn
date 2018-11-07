<?php
namespace App\Http\Controllers\Activity;

use Auth;
use Illuminate\Http\Request;
use App\Bls\Activity\Config;
use App\Bls\Activity\Redbox;
use App\Bls\Activity\TipImg;
use App\Bls\Activity\Associat;
use App\Bls\Activity\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
/**
 * 活动
 */
class IndexController extends Controller
{

	/* 活动首页 */
	public function index(Request $request)
	{
		$userId = Auth::user()->id;

		$actID = Associat::where('merchantID',$userId)->pluck('activityID')[0];

		$list = Activity::whereIn('id',explode(',', $actID))->get();

		return view('activity.index',['list'=>$list]);
	}


	/* 创建活动 */
	public function create(Request $request)
	{

		$input = Input::all();

		$userId = Auth::user()->id;

		if (!$request->get('actid')) 
			return redirect(route('activity'))->withErrors('非法访问！');

//		//创建红包
//		if ($request->get('actid') != 10)
//			return redirect(route('activity'))->withErrors('该活动正在开发中！');

		if (!$request->isMethod('post') && !isset($input['edit'])) {
			if (Config::where(['activityID'=>$input['actid'],'merchantID'=>$userId])->count())
				return redirect(route('activity'))->withErrors('重复创建活动！');
		}		

		$act = Config::where(['activityID'=>$input['actid'],'merchantID'=>$userId])->first();

		if ($request->isMethod('post')) {
			
			$input['merchantID'] = $userId;

			$input['activityID'] = $request->get('actid');

			unset($input['_token']); unset($input['actid']);

			foreach ($input as $key => $val) $input[$key] = $val ?: '';

			if (!$act) {

				if (Config::create($input))
					return redirect(route('act.creat.config',['a'=>$request->get('actid')]));

			} elseif (Config::where(['merchantID'=>$userId,'activityID'=>$request->get('actid')])->update($input)) {

				return redirect(route('act.creat.config',['a'=>$request->get('actid')]));
			
			}

		}

		$act ?: $act = Activity::find($input['actid']);

		return view('activity.create.index',compact('act'));
	}


	/* 基础设置 */
	public function setConfig(Request $request)
	{
		$input = Input::all();
		$userId = Auth::user()->id;
		$actid = $request->get('a');
		$confID = Config::where(['activityID'=>$actid, 'merchantID'=>$userId])->value('id');
		if ($request->isMethod('post')) {

			$input['latandlng'] = $input['lat'].','.$input['lng'];
			$input['frequency']	= $input['frequency'].','.$input['num'];
			unset($input['a']);
			unset($input['lat']);
			unset($input['lng']);
			unset($input['num']);
			unset($input['file']);
			unset($input['prev']);
			unset($input['next']);
			unset($input['_token']);
			foreach ($input as $key => $val) {

				$input[$key] = $val ?? '';
				empty($input['startTime']) && $input['startTime'] = null;
				empty($input['endTime']) && $input['endTime'] = null;
				empty($input['deadline']) && $input['deadline'] = null;

			}

			Config::where('id', $confID)->update($input);

			if ($request->get('prev')) {

				return Response::json([
					'code'		=>	1,
					'message'	=>	'prev',
					'route'		=>	route('act.creat',['edit'=>'y','actid'=>$actid])
				]);
			
			}

			if ($request->get('next')) {
				
				if (!$input['startTime'] || !$input['endTime'] || !$input['deadline'])
					return Response::json(['code'=>0,'message'=>'请输入活动日期及截止日期']);

				return Response::json([
					'code'		=>	1,
					'message'	=>	'next',
					'route'		=>	route('act.creat.prize',['actid'=>$actid])
				]);

			}

		}

		$info = Config::where('id', $confID)->first();

		return view('activity.create.config',compact('info'));

	}



	/* 奖品设置 */
	public function setPrize(Request $request)
	{

		$input = Input::all();

		$userId = Auth::user()->id;

		$confID = Config::where(['activityID'=>$input['actid'], 'merchantID'=>$userId])->value('id');

		$info = Redbox::where('config_id',$confID)->first();

		/* 现金红包 */
		if ($input['actid'] == 10 ) {			

			if ($request->isMethod('post')) {

				$data = [
					'config_id'		=> $confID,
					'money'			=> $this->yuanToCent($input['money']),
					'num'			=> $input['num'],
				];

				($info == null) && Redbox::create($data);

				Redbox::where('config_id',$confID)->update($data);

				if ($input['lose']) {

					Config::where('id',$confID)->update(['losText'=>$input['lose']]);
				
				};

				if ($request->get('prev')) {

					return Response::json([
						'code'		=>	1,
						'message'	=>	'prev',
						'route'		=>	route('act.creat.config',['a'=>$request->get('actid')])
					]);
				
				}

				if ($request->get('next')) {

					return Response::json([
						'code'		=>	1,
						'message'	=>	'next',
						'route'		=>	route('act.creat.notice',['actid'=>$request->get('actid')])
					]);

				}

			}

			$info['lose'] = Config::where('id',$confID)->value('losText');

			$info['money'] = $this->centToYuan($info['money']);

			return view('activity.create.prize.red',compact('info'));

		}

		return view('activity.create.prize.default');

	}

	/* 中奖告知 */
	public function winNotice(Request $request)
	{
		$input = Input::all();

		$userId = Auth::user()->id;

		$pics['win'] = TipImg::where(['user'=>$userId,'type'=>'win'])->get();
		$pics['los'] = TipImg::where(['user'=>$userId,'type'=>'los'])->get();

		$pic['win'] = Config::where(['activityID'=>$input['actid'],'merchantID'=>$userId])->value('winPic');
		$pic['los'] = Config::where(['activityID'=>$input['actid'],'merchantID'=>$userId])->value('losPic');

		if ($request->isMethod('post')) {
			
			Config::where(['activityID'=>$input['actid'],'merchantID'=>$userId])
					->update(['winPic'=>$input['win'],'losPic'=>$input['los']]);

			return Response::json(['code'=>1,'message'=>route('act.my')]);

		}

		return view('activity.create.notice.index',compact('pic','pics'));
	}

	//上传音乐
	public function upMusic(Request $request)
	{
		$file       = Input::file('file');
        $filePath   = $file->path();
        $fileSize   = $file->getClientSize();
        $extension  = $file->getClientOriginalExtension();
        if (!in_array($extension, ['mp3', 'wav', 'ogg']))
            return Response::json(['code'=>0,'message'=>'请上传MP3、WAV或OGG格式文件！']);
        if ($fileSize > (3 * 1024 * 1024))
            return Response::json(['code'=>0,'message'=>'请上传3M以下文件！']);
        $fileName   = $request->get('aid').'.'.$extension;
        $newName    = 'BGM_'.Auth::user()->id.'_'.$fileName;
        $disk = \Storage::disk('qiniu');
        $disk->exists($newName) && $disk->delete($newName);
        if ($disk->put($newName,file_get_contents($filePath))){
            return Response::json([
                'code' => 1,
                'message' => $disk->url(['path' => $newName, 'domainType' => 'custom']),
            ]);
        }else{
            return Response::json(['code'=>0,'message'=>'文件上传失败！']);
        }
	}

	public function uploadImage(Request $request)
	{
		$file       = Input::file('file');
        $filePath   = $file->path();
        $fileSize   = $file->getClientSize();
        $disk 		= \Storage::disk('qiniu');
        $extension  = $file->getClientOriginalExtension();
        if (!in_array($extension, ['jpg','png']))
            return Response::json(['code'=>0,'message'=>'请上传JPG或PNG格式文件']);
        if ($fileSize > (1 * 1024 * 1024))
            return Response::json(['code'=>0,'message'=>'请上传1M以下文件！']);
        $fileName   = $request->get('type').time().'.'.$extension;
        $newName    = 'Act_'.Auth::user()->id.'_'.$fileName;
        if ($disk->put($newName,file_get_contents($filePath))){
        	$imgPath = $disk->url(['path' => $newName, 'domainType' => 'custom']);
        	$data = [
        		'img_name' 	=> $newName,
        		'img_path'	=> $imgPath,
        		'type'		=> $request->get('type'),
        		'user'		=> Auth::user()->id,
        	];
        	if (TipImg::create($data)) {
        		return Response::json([
	                'code' => 1,
	                'message' => $imgPath,
	            ]);
        	}

        	return Response::json(['code'=>0,'message'=>'SQL ERROR!']);

        }

        return Response::json(['code'=>0,'message'=>'文件上传失败！']);

	}

	public function yuanToCent($yuan)
	{
		return $yuan * 100;
	}

	public function centToYuan($cent)
	{
		return $cent / 100;
	}

}
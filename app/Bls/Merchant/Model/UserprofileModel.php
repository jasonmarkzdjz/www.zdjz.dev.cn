<?php
namespace App\Bls\Merchant\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
/**
 * 
 */
class UserprofileModel extends BaseModel
{
	
	protected $table = 'userprofile';
	protected $fillable = [
				'merchantId',
				'mobile',
				'email',
				'qq',
				'provice',
				'proviceId',
				'city',
				'cityId',
				'district',
				'districtId',
				'industryType',
				'isReward',
				'isMobile',
				'brief',
				'primaryPicture',
				'thumbnail',
				'picUrl',
				'ip',
		];

	public static function addChange($uid,$input,$uInfo)
	{
		$data['merchantId'] = $uid;
        $data['mobile'] = auth()->user()->mobile;
        $data['email'] = $input['email'];
        $data['qq'] = $input['qq'];
        $data['provice'] = $input['provinceTitle'];
        $data['proviceId'] = $input['province'];
        $data['city'] = $input['cityTitle'];
        $data['cityId'] = $input['city'];
        $data['district'] = $input['areaTitle'];
        $data['districtId'] = $input['area'];
        $data['industryType'] = $input['type'];
        $data['isReward'] = $input['reward'];
        $data['isMobile'] = $input['isphone'];
        $data['brief'] = !empty($input['brief']) ? $input['brief'] : '';
        $data['primaryPicture'] = $input['bigPic'] ?: ' ';
        $data['thumbnail'] = $input['bigPic'] ?: ' ';
        $data['picUrl'] = $input['picUrl'];
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        if ($uInfo){
        	if ($uInfo['primaryPicture'] !== $input['bigPic']) {
        		// Storage::disk('public')->delete([
        		// 	str_replace('storage/','',$uInfo['picUrl'])
        		// ]);
        	}        	
          	$data['ip'] = $uInfo['ip'];
        	$doIt = UserprofileModel::where('merchantId',$uid)->update($data);
        }else{
        	$doIt = UserprofileModel::create($data);
        }
		$back = $doIt ? ['code'=>1,'message'=>'保存成功'] : ['code'=>0,'message'=>'保存失败！'];
		return Response::json($back);
	}

}
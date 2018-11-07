<?php
namespace App\Bls\Merchant\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
/**
 * 个人认证
 */
class PersonauthModel extends BaseModel
{
	
	protected $table = 'personauth';
	protected $fillable = ['merchantId','trueName','idCard','idCardFacade','idCardIdentity','ip','isExamin','submitTime'];

	public static function addChange($uid,$input,$userInfo)
	{
        $data['isExamin']       = 0;
        $data['merchantId']     = $uid;
        $data['trueName']       = $input['userName'];
        $data['idCard']         = $input['idNum'];
        $data['idCardFacade']   = $input['upper'];
        $data['idCardIdentity'] = $input['under'];
        $data['submitTime']     = date('Y-m-d H:i:s',time());
        $data['ip']             = $_SERVER['REMOTE_ADDR'];
        if ($userInfo){
            // Storage::disk('public')->delete([
            //     str_replace('storage/','',$userInfo['idCardFacade']),
            //     str_replace('storage/','',$userInfo['idCardIdentity']),
            // ]);
          $data['ip'] = $userInfo['ip'];
        	$doIt = PersonauthModel::where('merchantId',$uid)->update($data);
        }else{
        	$doIt = PersonauthModel::create($data);
        }
		$back = $doIt ? ['code'=>1,'message'=>'保存成功'] : ['code'=>0,'message'=>'保存失败！'];
		return Response::json($back);
	}
}
<?php
namespace App\Bls\Merchant\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

/**
 * 
 */
class CompanyauthModel extends BaseModel
{
	protected $table = 'companyauth';
	protected $fillable = [
			'merchantId',
			'CompanyName',
			'OrganizCode',
			'OfficeAddress',
			'operateAddress',
			'tel',
			'submitTime',
			'legalCardFacade',
			'legalCardIdentity',
			'bankAccountLicens',
			'businessLicense',
			'isExamin',
			'ip',
		];

	public static function addChange($uid,$input,$companyInfo)
	{
		$data['isExamin']       	= 0;
		$data['merchantId'] 		= $uid;
		$data['CompanyName'] 		= $input['companyName'];
		$data['OrganizCode'] 		= $input['group'];
		$data['OfficeAddress'] 		= $input['offAddress'];
		$data['operateAddress'] 	= $input['busAddress'];
		$data['tel'] 				= $input['tel'];
		$data['submitTime'] 		= date('Y-m-d H:i:s',time());
		$data['legalCardFacade'] 	= $input['personUp'];
		$data['legalCardIdentity'] 	= $input['personUn'];
		$data['bankAccountLicens'] 	= $input['account'];
		$data['businessLicense'] 	= $input['license'];
		$data['ip'] 				= $_SERVER['REMOTE_ADDR'];
		if ($companyInfo) {
			// Storage::disk('public')->delete([
			// 	str_replace('storage/','',$companyInfo['legalCardFacade']),
			// 	str_replace('storage/','',$companyInfo['legalCardIdentity']),
			// ]);
        	$data['ip'] = $companyInfo['ip'];
        	$doIt = CompanyauthModel::where('merchantId',$uid)->update($data);
		}else{
			$doIt = CompanyauthModel::create($data);
		}
		$back = $doIt ? ['code'=>1,'message'=>'资料上传成功，请等待审核！'] : ['code'=>0,'message'=>'上传失败！'];
		return Response::json($back);
	}
}
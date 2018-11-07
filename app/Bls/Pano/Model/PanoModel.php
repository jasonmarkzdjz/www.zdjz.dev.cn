<?php
namespace App\Bls\Pano\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PanoModel extends BaseModel
{
	/**
	* 使用软删除
	*/
	use SoftDeletes;

	/**
	 * 定义表名称
	 * @var string
	 */
    protected $table = 'pano';

    protected $casts = [
        'pluginConfig' => 'array',
        'goodsConfig' => 'array',
        'navConfig' => 'array',
    ];

    public function getFnAllowListAttribute($value)
    {
        return explode(',', $value);
    }

    public function setFnAllowListAttribute($value)
    {
        !empty($value) && $this->attributes['fnAllowList'] = implode(',', $value);
    }
}
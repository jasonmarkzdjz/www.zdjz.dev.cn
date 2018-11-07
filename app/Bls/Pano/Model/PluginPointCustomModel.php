<?php
namespace App\Bls\Pano\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PluginPointCustomModel extends BaseModel
{
	/**
	* 使用软删除
	*/
	use SoftDeletes;

	/**
	 * 定义表名称
	 * @var string
	 */
    protected $table = 'plugin_point_custom';

    public function getImgsInfoAttribute($value)
    {
    	return json_decode($value);
    }

    public function setImgsInfoAttribute($value)
    {
    	$this->attributes['imgsInfo'] = json_encode($value);
    }
}
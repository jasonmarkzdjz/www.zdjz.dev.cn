<?php
namespace app\Bls\Pano\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
	/**
	 * 定义数据库名称
	 * @var string
	 */
    protected $connection = 'db_vr_panorama';
}
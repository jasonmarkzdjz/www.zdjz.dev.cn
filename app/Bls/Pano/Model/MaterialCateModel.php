<?php
namespace App\Bls\Pano\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class MateriaCateModel extends BaseModel
{
	/**
	* 使用软删除
	*/
	use SoftDeletes;

	/**
	 * 定义表名称
	 * @var string
	 */
    protected $table = 'material_cate';
}
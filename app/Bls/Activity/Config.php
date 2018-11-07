<?php
namespace App\Bls\Activity;

use App\Bls\Activity\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 */
class Config extends BaseModel
{
//	use SoftDeletes;

	protected $table = 'config';

//	protected $guarded = [];
//
//	protected $dates = ['deleted_at'];
}
<?php
namespace App\Bls\Activity;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class BaseModel extends Model
{
	protected $connection = 'db_vr_activity';

	protected $guarded = [];
}
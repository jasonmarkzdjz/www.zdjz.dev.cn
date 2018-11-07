<?php
namespace App\Bls\Pano\Model;

class PanoFnConfigModel extends BaseModel
{
    /**
     * 定义表名称
     * @var string
     */
    protected $table = 'pano_fn_config';

    public function getYearmoneyAttribute($value)
    {
        return number_format($value,2);
    }

    public function getMonthmoneyAttribute($value)
    {
        return number_format($value,2);
    }
}
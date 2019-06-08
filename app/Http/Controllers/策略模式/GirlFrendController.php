<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:47
 */
<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:44
 */

namespace App\Http\Controllers\策略模式;

use App\Http\Controllers\Controller;

class GirlFrendController extends Controller{

    protected $xingge;

    public function __construct($xingge)
    {
        $this->xingge = $xingge;
    }

    public function sajiao(){
        $this->xingge->sajiao();
    }

}
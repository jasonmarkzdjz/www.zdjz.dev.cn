<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;


/**
 *
 * @author Jason
 * @desc 首页相关信息
 *
 * */
class IndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('/home/index');
    }
    public function example(){
        return view('/home/example');
    }
    public function special(){
        return view('/home/special');
    }
    public function news(){
        return view('/home/news_list');
    }
}

<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Bls\Pano\MaterialBls;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $cateId = $request->input('cateid',0);
        abort_if(empty($cateId), 404, '未找到相关页面');
        if ($request->input('keywords')) {
            $keywords = base64_decode($request->input('keywords'));
            parse_str(urldecode($keywords),$parseData);
            // 验证规则
            $rules = [
                'search.maTitle' => 'max:50',
                'search.startTime' => 'date_format:Y-m-d H:i:s',
                'search.endTime' => 'date_format:Y-m-d H:i:s',
            ];

            $messages = [
                'search.maTitle.max' => '素材名称度最大50个字符。',
                'search.startTime.date_format' => '开始时间格式有误。',
                'search.endTime.date_format' => '结束时间格式有误。',
            ];

            // 验证数据
            \Validator::make($parseData,$rules,$messages)->validate();

            $searchData = $parseData['search'];
        } else {
            $searchData = [];
        }

        $MaterialBls = new MaterialBls();
        $pageSize = 10;
        $maList = $MaterialBls->getMaterialList($cateId, $pageSize, 'id', 'asc', $searchData);

        // var_dump($maList);
        // 素材分类
        // $MaterialBls = new MaterialBls();
        // $cateList = $MaterialBls->getCateList();
        
        // return view('/pano/index',['maList' => $maList, 'pageSize' => $pageSize, 'cateList' => $cateList]);
    }

    public function edit(Request $request)
    {

    }

    public function editPost(Request $request)
    {

    }

    public function add(Request $request)
    {

    }

    public function addPost(Request $request)
    {

    }

    public function delete()
    {

    }
}

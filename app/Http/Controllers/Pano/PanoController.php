<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Bls\Pano\PanoBls;
use App\Bls\Pano\PanoTagRelBls;
use App\Bls\Pano\PanoTagBls;
use App\Bls\Pano\PanoGroupBls;
use App\Bls\Pano\PanoCateBls;
use App\Bls\Pano\PanoSceneBls;
use App\Bls\Pano\CommonBls;

use App\Bls\Pano\PanoFnConfigBls;
use App\Bls\Pano\PluginPointBls;
use App\Bls\Pano\PluginRoamBls;
use App\Bls\Pano\PluginPointCustomBls;
use App\Bls\Pano\PluginRoamCustomBls;
use App\Bls\Pano\PluginLoadingBls;
use App\Bls\Pano\PluginFniconBls;
use App\Bls\Pano\PluginCtrbtnBls;
use App\Bls\Pano\PluginNavBls;

use App\Bls\Pano\Model\PanoModel;
use Illuminate\Support\Facades\DB;
use Auth;
class PanoController extends Controller
{

    /**
     * 全景项目列表
     * @author Jason7 2018-05-29
     * @return miexd
     */
    public function index(Request $request)
    {
        $cateId = $request->input('cateid',0);

        if ($request->input('keywords')) {
            $keywords = base64_decode($request->input('keywords'));
            parse_str(urldecode($keywords),$parseData);
            // 验证规则
            $rules = [
                'search.panoTitle' => 'max:50',
                'search.startTime' => 'date_format:Y-m-d H:i:s',
                'search.endTime' => 'date_format:Y-m-d H:i:s',
            ];

            $messages = [
                'search.panoTitle.max' => '场景名称度最大50个字符。',
                'search.startTime.date_format' => '开始时间格式有误。',
                'search.endTime.date_format' => '结束时间格式有误。',
            ];

            // 验证数据
            \Validator::make($parseData,$rules,$messages)->validate();

            $searchData = $parseData['search'];
        } else {
            $searchData = [];
        }
        
        $PanoBls = new PanoBls();
        $pageSize = 10;
        $panoList = $PanoBls->getPanoList($cateId,$searchData,$pageSize,'id desc');
        $PanoCateBls = new PanoCateBls();
        $cateList = $PanoCateBls->getCateList();

        return view('/pano/index',['panoList' => $panoList, 'pageSize' => $pageSize, 'cateList' => $cateList]);
    }

    /**
     * Ajax 删除
     * @author Jason7 2018-06-08
     * @param  Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $rules = [
            'ids' => 'required|array',
        ];

        $Validator = \Validator::make($request->all(),$rules);

        if ($Validator->fails()) {
            return array('msg' => $Validator->errors()->first(), 'status' => 0);
        }

        $ids = $request->input('ids');
        $PanoModel = new PanoModel();

        if (false !== $PanoModel->destroy($ids)) {
            return array('msg' => '删除成功。', 'status' => 1);
        } else {
            return array('msg' => '删除失败。', 'status' => 0);
        }
    }

    /**
     * Ajax 移动
     * @author Jason7 2018-06-08
     * @param  Request $request
     * @return bool
     */
    public function move(Request $request)
    {
        $rules = [
            'ids' => 'required|array',
            'cateId' => 'required|integer'
        ];

        $Validator = \Validator::make($request->all(),$rules);

        if ($Validator->fails()) {
            return array('msg' => $Validator->errors()->first(), 'status' => 0);
        }

        $ids = $request->input('ids');
        $PanoModel = new PanoModel();
        foreach ($ids as $key => $value) {
            $PanoModel->where('id',$value)->update(['cateId'=>$request->input('cateId')]);
        }
        return array('msg'=>'移动成功','status'=>1);
        
    }

    /**
     * 提交信息
     * @author Jason7 2018-05-29
     * @param  Request $request
     * @return array
     */
    public function addPost(Request $request)
    {
        // 验证规则
        $rules = [
            'sceneTitles.*' => 'required',
            'scenePaths.*' => 'required',
            'tagIds' => 'required',
            'provinceId' => 'required',
            'cityId' => 'required',
            'districtId' => 'required',
            'agreeId' => 'required',
        ];

        $messages = [
            'sceneTitles.*.required' => '场景名称不能为空。',
            'scenePaths.*.required' => '请先上传场景图。',
            'tagId.required' => '请至少选择一个标签。',
            'provinceId.required' => '请选择所在的 省。',
            'cityId.required' => '请选择所在的 市。',
            'districtId.required' => '请选择所在的 区/县。',
            'agreeId.required' => '请选择协议。',
        ];

        // 验证数据
        $Validator = \Validator::make($request->all(),$rules,$messages);

        if ($Validator->fails()) {
            return array('msg' => $Validator->errors()->first(), 'status' => 0);
        }

        $data = $request->all();
        
        $CommonBls = new CommonBls();

        $data['groupId'] = 0;

        // 创建16位唯一ID
        $data['viewId'] = $CommonBls->create_unique_id(16);
        // 全景项目表写入
        $data['provinceName'] = $CommonBls->get_area_name($data['provinceId']);
        $data['districtName'] = $CommonBls->get_area_name($data['districtId']);
        $data['cityName'] = $CommonBls->get_area_name($data['cityId']);

        // 开启事务
        DB::connection('db_vr_panorama')->beginTransaction();
        try {
            $PanoBls = new PanoBls();
            if ($lastId = $PanoBls->insertPanoId($data)) {
                $data['panoId'] = $lastId;
                // 提交任务
                if (false === $PanoBls->submitTaskScene($data)) {
                    throw new \Exception('全景任务添加失败。');
                }

                // 创建分组
                if (!empty($data['groupIds']) && isset($data['groupIds'])) {
                    $PanoGroupBls = new PanoGroupBls();
                    $PanoGroupBls->batchInsertGroup($data);
                }

                // 创建标签关系
                if (!empty($data['tagIds']) && isset($data['tagIds'])) {
                    $TagRelBls = new PanoTagRelBls();
                    $TagRelBls->batchInsertTagRel($data);
                } else {
                    throw new \Exception('全景标签添加失败。');
                }
                // 提交
                DB::connection('db_vr_panorama')->commit();

                return array('msg' => '全景项目添加成功。', 'status' => 1, 'saveid' => $lastId);
            } else {
                throw new \Exception('全景项目添加失败。');
            }
        } catch (\Exception $e) {
            DB::connection('db_vr_panorama')->rollBack();
            return array('msg' => $e->getMessage(), 'status' => 0);
        }
    }

    /**
     * 全景项目编辑器
     * @author Jason7 2018-06-27
     * @param  Request $request
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $viewId = $input['id'];
        // 当前全景项目查询
        $pano = PanoModel::where('viewId',$viewId)->first();

        abort_if(empty($pano), 404, '未找到相关页面');

        // 功能列表查询
        $PanoFnConfigBls = new PanoFnConfigBls();
        $fnList = $PanoFnConfigBls->getFnConfigList(1, 'asc');

        // 标签查询
        $PanoTagBls = new PanoTagBls();
        $tagList = $PanoTagBls->getTagList();

        // 当前选中标签
        $PanoTagRelBls = new PanoTagRelBls();
        $tagRelList = $PanoTagRelBls->getTagRelList($pano['id']);

        // 标注点样式查询
        $PluginPointBls = new PluginPointBls();
        $pointList = $PluginPointBls->getPointList();

        // 自定义全局标注点样式查询
        $PluginPointCustomBls = new PluginPointCustomBls();
        $pointCustomList = $PluginPointCustomBls->getPointCustomList();

        // 漫游点样式查询
        $PluginRoamBls = new PluginRoamBls();
        $roamList = $PluginRoamBls->getRoamList();

        // 自定义全局漫游点样式查询
        $PluginRoamCustomBls = new PluginRoamCustomBls();
        $roamCustomList = $PluginRoamCustomBls->getRoamCustomList();

        // 加载样式查询
        $PluginLoadingBls = new PluginLoadingBls();
        $loadingList = $PluginLoadingBls->getLoadingList();

        // 功能图标样式查询
        $PluginFniconBls = new PluginFniconBls();
        $fniconList = $PluginFniconBls->getFniconList();

        // 控制按钮样式查询
        $PluginCtrbtnBls = new PluginCtrbtnBls();
        $ctrbtnList = $PluginCtrbtnBls->getCtrbtnList();

        // 导航样式查询
        $PluginNavBls = new PluginNavBls();
        $navList = $PluginNavBls->getNavList();
        // dd($pano->navConfig);
        return view('/pano/edit', ['pano' => $pano, 'fnList' => $fnList, 'tagList' => $tagList, 'selectedTagList' => $tagRelList, 'pointList' => $pointList, 'pointCustomList' => $pointCustomList, 'roamList' => $roamList, 'roamCustomList' => $roamCustomList, 'loadingList' => $loadingList, 'fniconList' => $fniconList, 'ctrbtnList' => $ctrbtnList, 'navList' => $navList]);
    }

    /**
     * 全景项目编辑提交
     * @author Jason7 2018-06-27
     * @param  Request $request
     */
    public function editPost(Request $request)
    {
        $rules = [
            'panoTitle' => 'required',
            'panoDesc' => 'required',
            'tagIds' => 'required|array|max:3', // 标签多选
            'coverImg' => 'required',
            'fnAllowList' => 'array', // 功能列表
            'pluginConfig' => 'array|required', // 插件样式配置
            'goodsConfig' => 'array', // 商品配置
        ];

        $messages = [
            'panoTitle.required' => '全景名称不能为空。',
            'panoDesc.required' => '全景介绍不能为空。',
            'tagIds.required' => '请至少选择一个标签。',
            'tagIds.max' => '标签最多选择3个。',
            'coverImg.required' => '请选择封全景面图。',
        ];

        // 验证数据
        $Validator = \Validator::make($request->all(),$rules,$messages);

        if ($Validator->fails()) {
            return array('msg' => $Validator->errors()->first(), 'status' => 0);
        }

        $input = $request->all();
        $PanoBls = new PanoBls();
        if ($PanoBls->updatePano($input)) {
            return array('msg' => '保存成功。', 'status' => 1);
        }
    }

    /**
     * 全景项目预览
     * @author Jason7 2018-06-27
     * @param  Request $request
     */
    public function view(Request $request)
    {
        $viewId = $request->input('id');
        $pano = PanoModel::where('viewId',$viewId)->first();
        return view('/pano/view/view',['pano'=>$pano]);
    }

    /**
     * 全景项目用到的XML文件
     * @author Jason7 2018-06-29
     * @param  Request $request [description]
     * @return xml
     */
    public function xml(Request $request)
    {
        $PanoSceneBls = new PanoSceneBls();
        $sceneList = $PanoSceneBls->getSceneList($request->input('id'));
        return view('/pano/view/xml',['sceneList'=>$sceneList]);
    }
    
    public function audio()
    {
        echo "测试";
    }
}

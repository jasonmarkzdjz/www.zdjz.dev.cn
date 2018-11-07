<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bls\Pano\Model\PanoModel;
use App\Bls\Pano\Model\PanoSceneModel;
use App\Bls\Pano\Model\TaskSceneModel;
use App\Bls\Pano\Model\TaskLogModel;
use App\Bls\Pano\CommonBls;
use Illuminate\Support\Facades\DB;

class SceneCube extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Scene:cube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '场景切图';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * 任务状态
         */
        defined('TASK_WAITING') or define('TASK_WAITING', 0); // 等待操作
        defined('TASK_DOWNLOAD') or define('TASK_DOWNLOAD', 1); // 下载成功
        defined('TASK_CUBE_OVER') or define('TASK_CUBE_OVER', 2); // 切图完毕
        defined('TASK_SCCUESS') or define('TASK_SCCUESS', 3); // 任务完成
        defined('TASK_FAIL') or define('TASK_FAIL', 4); // 任务失败

        /**
         * 项目状态
         */
        defined('PANO_REVIEW') or define('PANO_REVIEW', 0); // 审核中
        defined('PANO_PASS') or define('PANO_PASS', 1); // 审核通过
        defined('PANO_FAIL') or define('PANO_FAIL', 2); // 审核失败 

        /**
         * 日志状态
         */
        defined('LOG_SCCUESS') or define('LOG_SCCUESS', 1); // 任务完成
        defined('LOG_FAIL') or define('LOG_FAIL', 0); // 任务失败

        /**
         * KRPANO 配置
         */
        defined('KRPANO') or define('KRPANO', strtoupper(substr(PHP_OS,0,3))=='WIN' ? base_path() . '/krpano/krpano_win/make.bat' : base_path() .'/krpano/krpano_linux/make.sh'); // KRPANO 命令位置
        defined('KRPANO_TEMP_DIR') or define('KRPANO_TEMP_DIR', config('filesystems.disks.krpano_temp.root') . '/'); // KRPANO 临时文件路径

        /**
         * krpano 切图脚本
         */
        
        // 第一步：查询 待执行 的一条任务
        $task = TaskSceneModel::where('status',TASK_WAITING)->first();

        if (empty($task)) {
            dd('队列暂无数据');
        }
        
        echo "队列有数据\n";

        try {
            echo "下载全景图到本地\n";
            $savePath =  $task['merchantId'] . '/' . date('YmdHis') . '/';
            // 第二步：下载全景图片
            $result = $this->downloadFile($task['scenePath'],$savePath);

            if (empty($result)) { // 下载失败
                throw new \Exception('下载失败');
            }

            echo "下载全景图到本地成功，开始切图\n";
            // 第三步：执行切图
            $exe = exec(KRPANO . ' ' . KRPANO_TEMP_DIR . $result['LocalSavePath']);

            if($exe == 'error') {
                throw new \Exception('KRPANO程序执行失败');
            }

            $xmlPath = $result['LocalSaveDir'] . 'vtour/tour.xml';

            if (!file_exists(KRPANO_TEMP_DIR . $xmlPath)) { // 切图失败
                throw new \Exception('切图失败');
            }

            echo "切图成功，开始上传\n";
            // 第四步：切片数据上传至七牛
            if (false === $this->uploadFile($result['LocalSaveDir'] . 'vtour/panos/' . $result['viewId'] . '/', $task['panoId'] . '/works/' . $result['viewId'] . '/')) {
                throw new \Exception('切片上传失败');
            }

            echo "上传成功，保存数据中\n";
            // 第五步：数据保存至数据库
            DB::connection('db_vr_panorama')->beginTransaction();
            try {
                // 场景表在这一步写入
                $PanoSceneModel = new PanoSceneModel();
                $PanoSceneModel->panoId = $task['panoId'];
                $PanoSceneModel->groupId = $task['groupId'];
                $PanoSceneModel->viewId = $result['viewId'];
                $PanoSceneModel->sceneTitle = $task['sceneTitle'];
                $PanoSceneModel->scenePath = $task['scenePath'];
                $PanoSceneModel->sceneSize = $result['fileSize'];
                $PanoSceneModel->sceneThumbPath = $task['panoId'] . '/works/' . $result['viewId'] . '/thumb.jpg';
                $PanoSceneModel->cdnHost = "http://".$task['cdnHost'].'/'; // 地址补全;

                if (false === $PanoSceneModel->save()) {
                    throw new \Exception('场景表存储数据失败');
                }

                // 任务表修改状态
                $TaskSceneModel = TaskSceneModel::find($task['id']);
                $TaskSceneModel->status = TASK_CUBE_OVER;
                if (false !== $TaskSceneModel->save()) {
                    // 提交
                    DB::connection('db_vr_panorama')->commit();
                } else {
                    throw new \Exception('任务表修改状态失败');
                }
               
                // 项目状态修改
                // 查询当前场景同项目是否有未完成任务
                $panosWaiting = TaskSceneModel::where('status',TASK_WAITING)->where('panoId', $task['panoId'])->first();
                
                if (empty($panosWaiting)) { // 同项目无未完成任务
                    $PanoModel = PanoModel::find($task['panoId']);
                    $PanoModel->status = PANO_PASS;
                    $PanoModel->cdnHost = "http://".$task['cdnHost'].'/'; // 地址补全
                    $PanoModel->coverImg = $task['panoId'] . '/works/' . $result['viewId'] . '/thumb.jpg';
                    $PanoModel->save();
                }

            } catch (\Exception $e) {
                // 回滚
                DB::connection('db_vr_panorama')->rollBack();
                throw new \Exception($e->getMessage());
            }

            // 成功日志信息
            $message =  '执行成功';
            $status  = LOG_SCCUESS;

        } catch (\Exception $e) {
            echo "正在生成本次切图日志\n";
            // 错误日志信息
            $message = $e->getMessage();
            $status  = LOG_FAIL;

            // 失败：修改任务表状态
            $TaskSceneModel =TaskSceneModel::find($task['id']);
            $TaskSceneModel->status = TASK_FAIL;
            $TaskSceneModel->save();
        }
        echo "正在生成本次切图日志\n";
        // 第六步：提交任务日志
        $TaskLogModel = new TaskLogModel();
        $TaskLogModel->taskId = $task['id'];
        $TaskLogModel->msg = $message;
        $TaskLogModel->status = $status;
        $TaskLogModel->save();

        dd($message);
    }

    /**
     * 下载七牛文件到服务器临时目录
     * @author Jason7 2018-06-04
     * @param  int $mid
     * @param  string  $originPath 源文件地址
     * @param  string  $localSaveDir 下载保存目录
     * @return array
     */
    private function downloadFile($originPath = null, $localSaveDir = null)
    {
        if (empty($originPath) || empty($localSaveDir)) {
            return array();
        }

        // 文件后缀
        $ext = substr(strrchr($originPath, '.'), 1);
        // 16位不重复
        $CommonBls = new CommonBls();
        $viewId = $CommonBls->create_unique_id(16);
        // 完整文件名
        $fileName = $viewId . '.' . $ext;
        // 下载后的保存地址
        $filePath =  $localSaveDir . $fileName;
        // 保存至服务器
        $qiniuDisk = \Storage::disk('qiniu');
        $krpanoTempDisk = \Storage::disk('krpano_temp');
        // 获取cdn文件
        $content = $qiniuDisk->get($originPath);
        // 开始下载
        if($krpanoTempDisk->put($filePath, $content)) { // 下载成功
            return array(
                'LocalSavePath' => $filePath, //
                'LocalSaveDir' => $localSaveDir,
                'viewId' => $viewId,
                'fileSize' => $qiniuDisk->size($originPath),
            );
        }
    }

    /**
     * 循环上传目录内的文件至七牛
     * @author Jason7 2018-06-06
     * @param  string $localSaveDir 本地文件所在目录
     * @param  string $cdnSaveDir CDN保存目录
     * @return bool
     */
    private function uploadFile($localSaveDir = null, $cdnSaveDir = null)
    {
        if (empty($localSaveDir) || empty($cdnSaveDir)) {
            return false;
        }

        $krpanoTempDisk = \Storage::disk('krpano_temp');
        $qiniuDisk = \Storage::disk('qiniu');

        foreach (glob(KRPANO_TEMP_DIR.$localSaveDir.'*.*') as $key => $value) {
            $content = $krpanoTempDisk->get(str_replace(KRPANO_TEMP_DIR,'',$value));
            $fileName = basename($value);
            $qiniuDisk->put($cdnSaveDir.$fileName, $content);
        }

        foreach (glob(KRPANO_TEMP_DIR.$localSaveDir.'mobile/'.'*.*') as $key => $value) {
            $content = $krpanoTempDisk->get(str_replace(KRPANO_TEMP_DIR,'',$value));
            $fileName = basename($value);
            $qiniuDisk->put($cdnSaveDir.'mobile/'.$fileName, $content);
        }

        return true;
    }

}

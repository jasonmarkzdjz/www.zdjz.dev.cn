<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\PanoBls;
use App\Bls\Pano\CommonBls;
use Auth;
class SpeechController extends Controller
{
    /**
     * @author Jason7 2018-06-30
     * @param  Request $request 支持GET/POST
     * @return 合成的语音二进制
     */
    public function previewMp3(Request $request)
    {
        // header('Content-Type: audio/mpeg');
        $input = $request->all();

        // 验证规则
        $rules = [
            'text' => 'required|max:1024',
            'spd'  => 'required|between:0,9',
            'pit'  => 'required|between:0,9',
            'vol'  => 'required|between:0,15',
            'per'  => 'required',
            'cuid' => 'max:60',
        ];

        // 验证数据
        \Validator::make($input,$rules)->validate();

        $CommonBls = new CommonBls();
        exit($CommonBls->text2speech($input['text'], $input['spd'], $input['pit'], $input['vol'], $input['per']));
    }
}

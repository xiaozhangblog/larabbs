<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-03-12
 * Time: 11:42
 */
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Tool\Validate\ValidateCode;
use Illuminate\Http\Request;

class ValidateController extends  Controller{

    // 生成验证码
    public function create(Request $request)
    {
        $validateCode = new ValidateCode;
        // 验证码保存到SESSION中
        $request->session()->put('validate_code', $validateCode->getCode());
        return $validateCode->doimg();
    }
}



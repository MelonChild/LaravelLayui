<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class BaseController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Base Controller
      |--------------------------------------------------------------------------
      |
      | 基控制器
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return 
     */
    public function __construct() {
        $this->middleware('auth:web');
    }

    /**
     * 上传文件
     *
     * @return 
     */
    public function upload(Request $request) {
        $return['errno'] = true;
        $return['message'] = "上传失败";

        //保存信息
        $file = Input::file('file');
        if ($request->isMethod('post') && $file->isValid()) {
            $path = '/uploads/other';
            $filename = md5(time()) . '.' . $file->getClientOriginalExtension();
            $file->move('.' . $path, $filename);
            $path = $path . '/' . $filename;

            if ($path) {
                $return['errno'] = false;
                $return['message'] = "上传成功";
                $return['path'] = "$path";
            }
        }
        return $return;
    }

}

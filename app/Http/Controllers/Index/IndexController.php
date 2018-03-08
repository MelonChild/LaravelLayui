<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Index\BaseController;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController {
    /*
      |--------------------------------------------------------------------------
      | Base Controller
      |--------------------------------------------------------------------------
      |
      | 首页控制器
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return 
     */

    /**
     * 首页
     *
     * @return 
     */
    public function index() {
      
        return view('index.index.index');
    }

    /**
     * 首页
     *
     * @return 
     */
    public function dashboard() {

        return view('index.index.dashboard');
    }

    /**
     * 菜单
     *
     * @return 
     */
    public function navs() {
        return session('navs');
    }

}

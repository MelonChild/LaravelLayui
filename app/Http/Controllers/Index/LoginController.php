<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Libs\Geetest\geetest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | 登录相关控制器
      |
     */

use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return 
     */
    public function __construct() {

        $this->middleware('guest:web')->except('logout');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * 登录用户名
     *
     * @return 
     */
    public function username() {
        return 'username';
    }

    /**
     * 定义看守器
     *
     * @return 
     */
    public function guard() {
        return Auth::guard();
    }

    /**
     * 登录界面
     *
     * @return 
     */
    public function login() {
//         $pwd = bcrypt('admin');dd($pwd);
        return view('index.login.login');
    }

    /**
     * 极验展示
     *
     * @return 
     */
    public function geetest() {
        // 实例化极验 
        $geetest = new \GeetestLib('21c9c9f206ceee4ab79010e720923934', '684188e5cd83b45d87bc60142f726444');
        session_start();

        $data = array(
            "user_id" => "test", # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $geetest->pre_process($data, 1);
        $session['gtserver'] = $status;
        $session['user_id'] = $data['user_id'];
        session($session);
        return $geetest->get_response_str();
    }

    /**
     * 极验验证
     *
     * @return 
     */
    public function verifyGeetest(Request $request) {
        //获取客户端传入值
        $input = $request->all();
        //返回值
        $return['status'] = 'fail';

        if ($request->isMethod('post')) {
            //实例化极验
            $geetest = new \GeetestLib('21c9c9f206ceee4ab79010e720923934', '684188e5cd83b45d87bc60142f726444');

            //获取参考值
            $data = array(
                "user_id" => session('user_id'), # 网站用户id
                "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
                "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
            );



            //服务器正常
            if (session('gtserver') == 1) {
                $result = $geetest->success_validate($input['geetest_challenge'], $input['geetest_validate'], $input['geetest_seccode'], $data);
                if (!$result) {
                    return $return;
                }
            } else {  //服务器宕机,走failback模式
                $result = $geetest->fail_validate($input['geetest_challenge'], $input['geetest_validate'], $input['geetest_seccode']);
                if (!$result) {
                    return $return;
                }
            }
            //用户账号密码判断
            if (Auth::guard()->attempt(['username' => $input['username'], 'password' => $input['password'], 'active' => 1])) {

                //用户登录成功
                $return['status'] = 'success';

                //存储用户信息
                session(['userInfo' => Auth::guard()->user()->toArray()]);
            }
        }

        return $return;
    }

    /**
     * 登出
     *
     * @return 
     */
    public function logout() {
        //注销用户
        session(['navs'=>null]);
        Auth::guard()->logout();
        return redirect()->route('index.login');
    }

    public function test() {
        
    }

}

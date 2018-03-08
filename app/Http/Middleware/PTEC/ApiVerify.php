<?php

namespace App\Http\Middleware\PTEC;
use App\Models\BakMenu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

use Closure;

class ApiVerify {

    /**
     * 检查用户中间件;
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $input = $request->input();
        if (!isset($input["token"])|| strlen($input["token"])!=42) {
            return response()->json(["status" => 100, "info" => "token check error1"]);
        }
        list($app_key, $time) = str_split($input["token"], 32);
        $web = md5(config("api.mptoken"));//生成token值

        if (($time < time() - 600) || ($app_key !== $web)) {                    //10分钟内，正确请求
            return response()->json(["status" =>100, "info" => "token check error2"]);
        }
        return $next($request);
    }

}


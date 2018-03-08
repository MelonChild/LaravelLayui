<?php
/*
  |--------------------------------------------------------------------------
  | 助手函数
  |--------------------------------------------------------------------------
 */

//引用
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;


/**
 * curl请求
 */
function curl_post($url, $postFields,$post=true) {
//初始化curl

    $ch = curl_init();
    // $postFields = arr2xml($postFields);
    //参数设置  
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, $post);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));


    $result = curl_exec($ch);

    //连接失败

    if ($result == FALSE) {
        if ($this->BodyType == 'json') {
            $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
        } else {
            $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>";
        }
    }

    curl_close($ch);
    return $result;
}

//创建所给路径所有目录
function setdir($dir) {
    $dirs = explode('/', $dir);
    $string = "";
    foreach ($dirs as $k => $v) {
        if ($k > 0) {
            $string .= '/' . $dirs[$k];
            if (!file_exists('.' . $string)) {
                mkdir('.' . $string);
            }
        }
    }
}

function deldir($dir) {
    //删除目录下的文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
}
function del_pic($picture) {
    if (@file_exists('.' . $picture)) {
        @unlink('.' . $picture);
    }
}
/**
 * 下载远程图片到本地
 *
 * @param string $url 远程文件地址
 * @param string $filename 1为原文件名,0随机生成文件名
 * @param array $fileType 允许的文件类型
 * @param string $dirName 文件保存的路径（路径其余部分根据时间系统自动生成）
 * @param int $type 远程获取文件的方式
 * @return json 返回文件名、文件的保存路径
 * @author blog.snsgou.com
 */
function downloadImage($url, $dirName, $fileName = 1, $type = 1) {
    if ($url == '') {
        return false;
    }


    // 获取文件原文件名
    $defaultFileName = basename($url);

    // 获取文件类型
//    $suffix = substr(strrchr($url, '.'), 1);
//    if (!in_array($suffix, $fileType)) {
//        return false;
//    }

    // 设置保存后的文件名
    $fileName = $fileName == 1 ? $defaultFileName : time() . rand(0, 9) . '.' . $suffix;

    // 获取远程文件资源
    if ($type) {
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $file = ob_get_contents();
        ob_end_clean();
    }

    // 设置文件保存路径
    $dirName = "." . $dirName . "/";
    if (!file_exists($dirName)) {
        mkdir($dirName, 0777, true);
    }

    // 保存文件
    $res = fopen($dirName . '/' . $fileName, 'a');
    fwrite($res, $file);
    fclose($res);

    return array(
        'fileName' => $fileName,
        'saveDir' => $dirName
    );
}

function tscoding($res){
    $encode = mb_detect_encoding($res, array('ASCII', 'GB2312', 'GBK', 'UTF-8'));
    if ($encode != "UTF-8") {
        $res = iconv($encode, 'UTF-8//ignore', $res);
    }
    return $res;
}

/**
 * get_current_url
 * 获取当前路由信息
 *
 * @access public
 * @param type $param remark
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function get_current_url() {
    $action = Route::current()->getActionName();
    list($class, $method) = explode('@', $action);
    $class = str_replace('Controller', '', substr(strrchr($class, '\\'), 1));

    return ['controller' => $class, 'action' => $method];
}

/**
 * get_model_instance
 * 获取model实例
 *
 * @access public
 * @param type $param remark
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function get_model_instance() {
    $url = get_current_url();
    $controller = strtolower($url['controller']);

    //实例model
    $use = '\App\Models\\' . $url['controller'];

    return new $use;
}

/**
 * send_sms
 * 发送短信
 *
 * @access public
 * @param type $param remark
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function send_sms($phone, $datas, $tempId = '') {
    $return['errno'] = 1;
    $return['message'] = "手机号有误";

    //判断手机号
    if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
        return $return;
    }

    // 初始化REST SDK
    $accountSid = config('common.sms.sid');
    $accountToken = config('common.sms.token');
    $appId = config('common.sms.appid');
    $serverIP = 'app.cloopen.com';
    $serverPort = '8883';
    $softVersion = '2013-12-26';
    $tempId = $tempId ? $tempId : config('common.sms.temp');

    $rest = new \REST($serverIP, $serverPort, $softVersion);
    $rest->setAccount($accountSid, $accountToken);
    $rest->setAppId($appId);

    // 发送模板短信
    $result = $rest->sendTemplateSMS($phone, $datas, $tempId);
    if ($result == NULL) {
        $return['errno'] = 2;
        $return['message'] = "短信服务器出错";

        return $return;
    }
    if ($result->statusCode != 0) {
        $return['errno'] = 3;
        $return['message'] = "短信服务器：" . $result->statusMsg;
    } else {
        $return['errno'] = 0;
        $return['message'] = "发送成功";
    }

    return $return;
}

/**
 * send_verify_code
 * 发送短信验证码
 *
 * @access public
 * @param type $phone 手机号
 * @param type $time 有效时间 默认4分钟
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function send_verify_code($phone, $time = 5) {
    $return['errno'] = 1;
    $return['message'] = "手机号有误";

    if ($phone && preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
        //生成验证码
        $data['code'] = rand(1000, 9999);
        $data['phone'] = $phone;
        $data['created_at'] = time() + $time * 60;

        DB::table('code')->where('phone', $phone)->delete();
        $code = DB::table('code')->insert($data);

        if ($code) {
            $datas = array($data['code'], $time);
            $return = send_sms($phone, $datas);
        } else {
            $return['errno'] = 4;
            $return['message'] = "生成验证码失败";
        }
    }

    return $return;
}

/**
 * check_verify_code
 * 发送短信验证码
 *
 * @access public
 * @param type $phone 手机号
 * @param type $code 验证码
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function check_verify_code($phone, $code = 0) {
    $return['errno'] = 1;
    $return['message'] = "手机号有误";

    if ($phone && preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
        //验证验证码
        $verifycode = DB::table('code')->where('phone', $phone)->first();

        if ($verifycode && time() < $verifycode->created_at && $code == $verifycode->code) {
            DB::table('code')->where('phone', $phone)->delete();
            $return['errno'] = 0;
            $return['message'] = "验证码正确";
        } else {
            $return['errno'] = 2;
            $return['message'] = "验证码不正确";
        }

        //苹果验证
        if ($phone == 15151866990) {
            DB::table('code')->where('phone', $phone)->delete();
            $return['errno'] = 0;
            $return['message'] = "验证码正确";
        }
    }

    return $return;
}

/**
 * generate_formate_data
 * 生成指定数组
 *
 * @access public
 * @param type $arrays 原有数组
 * @param type $fills 填充数组
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function generate_formate_data($model, $arrays = '', $guardeds = array('id')) {

    $return['errno'] = 1;
    $return['message'] = "数据不存在";

    if (is_array($arrays)) {
        //获取字段值
        $columns = Schema::getColumnListing($model);

        foreach ($columns as $key => $value) {
            isset($arrays[$value]) && ($data[$value] = $arrays[$value]);
        }
        //剔除指定字段
        $data = array_except($data, $guardeds);

        $return['errno'] = 0;
        $return['data'] = $data;
        $return['message'] = "成功";
    }

    return $return;
}

/**
 * mbstringtoarray
 * 插入一段字符串 
 *
 * @access public
 * @param type $arrays 原有数组
 * @param type $fills 填充数组
 * @return array
 * @author melon<melonchild@outlook.com>
 */
function mbstringtoarray($str, $cut_len, $charset, $inter = "") {
    $strlen = mb_strlen($str, $charset);
    $array = array();
    while ($strlen) {
        $array[] = mb_substr($str, 0, $cut_len, $charset);
        $str = mb_substr($str, $cut_len, $strlen - $cut_len, $charset);
        $strlen = mb_strlen($str, $charset);
    }
    return implode($inter, $array);
}

/**
 * create_txt_pic
 * 文字创建为图片
 *
 * @access public
 * @param $card 画板
 * @param $pos 数组， color() rgb数组 top距离画板顶端的距离，fontsize文字的大小，width宽度，left距离左边的距离，hang_size行高
 * @param $str 要写的字符串
 * @param $iswrite  是否输出，ture，  花出文字，false只计算占用的高度
 * @param $iswrite  是否加粗，ture，  加粗
 * @return int 返回整个字符所占用的高度
 * @author melon<melonchild@outlook.com>
 */
function create_txt_pic($card, $pos, $str, $iswrite, $blod = false) {
    $_str_h = $pos["top"];
    $fontsize = $pos["fontsize"];
    $linewidth = 0;
    $width = $pos["width"];
    $margin_lift = $pos["left"];
    $hang_size = $pos["hang_size"];
    $temp_string = "";
    $font_file = "msyh.ttc";
    $tp = 0;

    $font_color = imagecolorallocate($card, $pos["color"][0], $pos["color"][1], $pos["color"][2]);
    for ($i = 0; $i < mb_strlen($str); $i++) {

        $box = imagettfbbox($fontsize, 0, $font_file, $temp_string);
        $_string_length = $box[2] - $box[0];
        $temptext = mb_substr($str, $i, 1);

        $temp = imagettfbbox($fontsize, 0, $font_file, $temptext);

        if ($_string_length + $temp[2] - $temp[0] < $width) {//长度不够，字数不够，需要继续拼接字符串。
            $temp_string .= mb_substr($str, $i, 1);

            if ($i == mb_strlen($str) - 1) {//是不是最后半行。不满一行的情况
                $_str_h += $hang_size; //计算整个文字换行后的高度。
                $tp++; //行数
                if ($iswrite) {//是否需要写入，核心绘制函数
                    imagettftext($card, $fontsize, 0, $margin_lift, $_str_h, $font_color, $font_file, $temp_string);
                    $blod && imagettftext($card, $fontsize, 0, $margin_lift + 1, $_str_h, $font_color, $font_file, $temp_string);
                    imagesavealpha($card, true);
                }
            }
            $linewidth = ($_string_length + $temp[2] - $temp[0]) > $linewidth ? ($_string_length + $temp[2] - $temp[0]) : $linewidth;
        } else {//一行的字数够了，长度够了。
            //打印输出，对字符串零时字符串置null
            $texts = mb_substr($str, $i, 1); //零时行的开头第一个字。
            //判断默认第一个字符是不是符号；
            $isfuhao = preg_match("/[\\pP]/u", $texts) ? true : false; //一行的开头这个字符，是不是标点符号
            if ($isfuhao) {//如果是标点符号，则添加在第一行的结尾
                $temp_string .= $texts;

                //判断如果是连续两个字符出现，并且两个丢失必须放在句末尾的，单独处理
                $f = mb_substr($str, $i + 1, 1);
                $fh = preg_match("/[\\pP]/u", $f) ? true : false;
                if ($fh) {
                    $temp_string .= $f;
                    $i++;
                }
            } else {
                $i--;
            }

            $tmp_str_len = mb_strlen($temp_string);
            $s = mb_substr($temp_string, $tmp_str_len - 1, 1); //取零时字符串最后一位字符

            if (is_firstfuhao($s)) {//判断零时字符串的最后一个字符是不是可以放在见面
                //讲最后一个字符用“_”代替。指针前移动一位。重新取被替换的字符。
                $temp_string = rtrim($temp_string, $s);
                $i--;
            }

            //计算行高，和行数。
            $_str_h += $hang_size;
            $tp++;
            if ($iswrite) {
                imagettftext($card, $fontsize, 0, $margin_lift, $_str_h, $font_color, $font_file, $temp_string);
                $blod && imagettftext($card, $fontsize, 0, $margin_lift + 1, $_str_h, $font_color, $font_file, $temp_string);
                imagesavealpha($card, true);
            }
            //写完了改行，置null该行的临时字符串。
            $temp_string = "";
        }
    }

    $return['height'] = $tp * $hang_size + $pos["hang_size"];
    $return['width'] = $linewidth + $pos["left"] * 2;
    return $return;
}

function is_firstfuhao($str) {
    $fuhaos = array("\"", "“", "'", "<", "《",);

    return in_array($str, $fuhaos);
}

/**
 * create_pic
 * 创建图片
 *
 * @access public
 * @param $card 画板
 * @param $pos 数组， color() rgb数组 top距离画板顶端的距离，fontsize文字的大小，width宽度，left距离左边的距离，hang_size行高
 * @param $str 要写的字符串
 * @param $iswrite  是否输出，ture，  花出文字，false只计算占用的高度
 * @return int 返回整个字符所占用的高度
 * @author melon<melonchild@outlook.com>
 */
function create_pic($dir, $type = 'png', $str = '', $width = 300, $height = 100, $colorBg, $colorTxt, $fontsize = 10, $left = 0, $top = 0, $hang = 15, $blod = false) {
    $im = imagecreate($width, $height);
    $limitWidth = $width - $left;
    $temp = array("color" => $colorTxt, "fontsize" => $fontsize, "width" => $limitWidth, "left" => $left, "top" => $top, "hang_size" => $hang);
    //这里我只用它做测量高度，把参数false改为true就是绘制了。
    $str_h = create_txt_pic($im, $temp, $str, false, $blod);
    $width = $str_h['width'] < $width ? $str_h['width'] : $width;
    $im = imagecreate($width, $str_h['height']);
    $bg = imagecolorallocatealpha($im, $colorBg[0], $colorBg[1], $colorBg[2], 10); //背景拾色
    imagealphablending($im, false); //关闭混合模式，以便透明颜色能覆盖原画板
    imagefill($im, 0, 0, $bg); //填充
    $str_h = create_txt_pic($im, $temp, $str, true, $blod);
    //输出图片
    $type == 'png' && imagepng($im, $dir, 5);
    $type == 'jpg' || $type == 'jpeg' && imagejpeg($im, $dir);
    $type == 'gif' && imagegif($im, $dir);

    return true;
}

/**
 * 给图片加边框 by liangjian 
 * @param $ImgUrl   图片地址 
 * @param $SavePath 新图片保存路径 
 * @param $px   边框像素（2表示左右各一像素） 
 * @return Ambigous <boolean, 新图片的路径> 
 */
function image_add_board($ImgUrl, $SavePath, $px = 2) {
    $aPathInfo = pathinfo($ImgUrl);
    // 文件名  
    $sFileName = $aPathInfo ['filename'];
    // 图片扩展名  
    $sExtension = $aPathInfo ['extension'];
    // 获取原图大小  
    list($img_w, $img_h) = getimagesize($ImgUrl);

    // 读取图片  
    if (strtolower($sExtension) == 'png') {
        $resource = imagecreatefrompng($ImgUrl);
        imagesavealpha($resource, true); //这里很重要 意思是不要丢了$sourePic图像的透明色;
    } elseif (strtolower($sExtension) == 'jpg' || strtolower($sExtension) == 'jpeg') {
        $resource = imagecreatefromjpeg($ImgUrl);
    }

    // 282*282的黑色背景图片  
    $im = @imagecreatetruecolor($img_w, $px) or die("Cannot Initialize new GD image stream");

    // 为真彩色画布创建背景，再设置为透明  
    $color = imagecolorallocate($im, 70, 70, 70);
    //imagefill ( $im, 0, 0, $color );  
    //imageColorTransparent ( $im, $color );  
    // 把品牌LOGO图片放到黑色背景图片上，边框是1px  
    imagecopy($resource, $im, 0, 0, 0, 0, $img_w, 1);

    $imgNewUrl = $SavePath . $sFileName . '-n.' . $sExtension;
    if (strtolower($sExtension) == 'png') {
        imagealphablending($resource, false); //不合并颜色,直接用图像颜色替换,包括透明色;
        imagesavealpha($resource, true); //不要丢了$resource图像的透明色;
        $ret = imagepng($resource, $imgNewUrl);
    } elseif (strtolower($sExtension) == 'jpg' || strtolower($sExtension) == 'jpeg') {
        $ret = imagejpeg($resource, $imgNewUrl);
    }
    imagedestroy($im);
    return $ret ? $imgNewUrl : false;
}

/**
 * get_username_by_id
 * 获取用户名
 *
 * @access public
 * @param $id 用户id
 * @return string 返回整个字符
 * @author melon<melonchild@outlook.com>
 */
function get_username_by_id($id) {
    $return = App\Models\User::where('id', $id)->select('nickname')->first();
    return $return['nickname'];
}


//加密函数
function encrypt_str($txt, $key = 'melon') {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $nh = rand(0, 64);
    $ch = $chars[$nh];
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt = base64_encode($txt);
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
        $tmp .= $chars[$j];
    }
    return urlencode($ch . $tmp);
}

//解密函数
function decrypt_str($txt, $key = 'melon') {
    $txt = urldecode($txt);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $ch = $txt[0];
    $nh = strpos($chars, $ch);
    $mdKey = md5($key . $ch);
    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
    $txt = substr($txt, 1);
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for ($i = 0; $i < strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
        while ($j < 0)
            $j+=64;
        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}

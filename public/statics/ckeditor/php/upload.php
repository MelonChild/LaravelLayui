<?php

//var_dump($_FILES["file"]);
//array(5) { ["name"]=> string(17) "56e79ea2e1418.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(43) "C:\Users\asus\AppData\Local\Temp\phpD07.tmp" ["error"]=> int(0) ["size"]=> int(454445) } 
//1.限制文件的类型，防止注入php或其他文件，提升安全
//2.限制文件的大小，减少内存压力
//3.防止文件名重复，提升用户体验
//方法一：  修改文件名    一般为:时间戳+随机数+用户名
// 方法二:建文件夹
//4.保存文件
//判断上传的文件是否出错,是的话，返回错误
if ($_FILES["upload"]["error"]) {
    echo $_FILES["upload"]["error"];
} else {

    $extensions = array("jpg", "bmp", "gif", "png");
    $uploadFilename = $_FILES['upload']['name'];
    $uploadFilesize = $_FILES['upload']['size'];
    if ($uploadFilesize > 1024 * 2 * 1000) {
        echo "<font color=\"red\"size=\"2\">*图片大小不能超过2M</font>";
        exit;
    }

    $extension = pathInfo($uploadFilename, PATHINFO_EXTENSION);
    if (in_array($extension, $extensions)) {
        $uploadPath = "./uploads/";
        $uuid = str_replace('.', '', uniqid("", TRUE)) . $_FILES['upload']['name'];
        $desname = $uploadPath . $uuid;
        $previewname = '/statics/ckeditor/php/uploads/' . $uuid;
        //转码，把utf-8转成gb2312,返回转换后的字符串， 或者在失败时返回 FALSE。
        $desname = iconv("UTF-8", "gb2312", $desname);
        $tag = move_uploaded_file($_FILES['upload']['tmp_name'], $desname);
        $callback = $_REQUEST["CKEditorFuncNum"];
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'" . $previewname . "','');</script>";
    } else {
        echo "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.jpg/.gif/.bmp/.png文件）</font>";
    }
}
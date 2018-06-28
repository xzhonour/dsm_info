<?php



if (!function_exists('str_rand')) {
    /*
     * 生成随机字符串
     * @param int $length 生成随机字符串的长度
     * @param string $char 组成随机字符串的字符串
     * @return string $string 生成的随机字符串
     */
    function str_rand($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if (!is_int($length) || $length < 0) {
            return false;
        }

        $string = '';
        for ($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }

        return $string;
    }

}


if (!function_exists('is_white_list')){

    function is_white_list(){
        global $white_list;
        if (empty($white_list) || !is_array($white_list)) return false;
        if ($value = array_value($white_list,param(0)) AND $value == param(1)) return true;
        return false;
    }
}


if (!function_exists('download')){
    function download($file_url,$new_name = ''){
        if(!isset($file_url) || trim($file_url)== ''){
            http_404();
        }
        if(!file_exists($file_url)){ //检查文件是否存在
            http_404();
        }
        $file_name=basename($file_url);
        $file_type=explode('.',$file_url);
        $file_type=$file_type[count($file_type)-1];
        $file_name=trim($new_name=='')?$file_name:urlencode($new_name);
        $file_type=fopen($file_url,'r'); //打开文件
        //输入文件标签
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($file_url));
        header("Content-Disposition: attachment; filename=".$file_name);
        //输出文件内容
        echo fread($file_type,filesize($file_url));
        fclose($file_type);
        exit();
    }
}
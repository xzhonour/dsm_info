<?php
/**
 * Created by PhpStorm.
 * User: donsen
 * Date: 2018/3/15
 * Time: 下午4:40
 */



// 检测 是否有上传文件

    function check_upload_file($filename = 'file'){
        return !empty($_FILES) && $_FILES[$filename]['error'] == 0;
    }

    function uploads($save_path,$allowExts='',$maxSize='',$allowTypes=''){
        $upload = new UploadFile($allowExts,$maxSize,$allowTypes);
        $res = $upload->upload($save_path);
        if ($res){
            return $upload->getUploadFileInfo();
        }
        return $upload->getErrorMsg();
    }
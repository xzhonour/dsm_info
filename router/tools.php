<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);


if ($action == 'validate_code'){
    $_vc = new ValidateCode();  //实例化一个对象
    $_vc->doimg();
    $_SESSION['validate_code'] = $_vc->getCode();//验证码保存到SESSION中
}else{
    http_404();
}

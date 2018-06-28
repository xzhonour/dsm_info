<?php

// 检测 php版本是否为 php 7.0 以上
if(version_compare(PHP_VERSION,'7.0', '<')){
    exit('当前版本为'.phpversion().',请更换为PHP7.0以上版本!');
}

// 0: 线上模式; 1: 调试模式;
!defined('DEBUG') AND define('DEBUG', 1);
define('APP_PATH', dirname(__FILE__).'/'); // __DIR__
!defined('JISHUWAN_PATH') AND define('JISHUWAN_PATH', APP_PATH.'jishuwan/');

$_SERVER['conf'] = $conf = (@include APP_PATH.'conf/conf.php') OR exit('App is not complete.');
$_SERVER['lang'] = $lang = include APP_PATH."lang/$conf[lang]/dsm.php";
include JISHUWAN_PATH.'jishuwan.php';

$sid = sess_start();



// 支持 Token 接口（token 与 session 双重登陆机制，方便 REST 接口设计，也方便 $_SESSION 使用）
$uid = intval(_SESSION('uid'));

empty($uid) AND $uid = user_token_get() AND $_SESSION['uid'] = $uid;

$user = user_read($uid);

$gid = empty($user) ? 0 : intval($user['gid']);

$gid_default = isset($conf['gid_default'])?$conf['gid_default']:1;

$white_list = ['user'=>'login','user'=>'reg','tools'=>'validate_code'];

if (empty($user) AND !is_white_list()){
    $_REQUEST[0] = 'user';
    $_REQUEST[1] = 'login';
}

$route = param(0, 'index');

if(!defined('SKIP_ROUTE')) {
//    define('MESSAGE_HTM_PATH',APP_PATH.'view/htm/message.htm');

    // 按照使用的频次排序，增加命中率，提高效率
    switch ($route) {
        case 'index': 	include APP_PATH.'router/index.php'; 	break;
        case 'user': 	include APP_PATH.'router/user.php'; 	break;
        case 'tools': 	include APP_PATH.'router/tools.php'; 	break;
        case 'tags': 	include APP_PATH.'router/tags.php'; 	break;

        default:
            include APP_PATH.'router/index.php'; 	break;
    }
}

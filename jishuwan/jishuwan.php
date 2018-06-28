<?php

!defined('DEBUG') AND exit('app is error!');

//!defined('DEBUG') AND define('DEBUG', 1); // 1: 开发模式， 2: 线上调试：日志记录，0: 关闭
//!defined('APP_PATH') AND define('APP_PATH', './');
//!defined('JISHUWAN_PATH') AND define('JISHUWAN_PATH', dirname(__FILE__).'/');

function_exists('ini_set') AND ini_set('display_errors', DEBUG ? '1' : '0');
error_reporting(DEBUG ? E_ALL : 0);

$get_magic_quotes_gpc = get_magic_quotes_gpc();

$starttime = microtime(1);
$time = time();

//define('IN_CMD', !empty($_SERVER['SHELL']) || empty($_SERVER['REMOTE_ADDR']));
//if(IN_CMD) {
//    !isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] = '';
//    !isset($_SERVER['REQUEST_URI']) AND $_SERVER['REQUEST_URI'] = '';
//    !isset($_SERVER['REQUEST_METHOD']) AND $_SERVER['REQUEST_METHOD'] = 'GET';
//} else {
    header("Content-type: text/html; charset=utf-8");
//}

// ----------------------------------------------------------> db cache class
include JISHUWAN_PATH.'db/db_pdo_mysql.class.php';
// ----------------------------------------------------------> tools class
include JISHUWAN_PATH.'uploadFile/upload_file.class.php';
include JISHUWAN_PATH.'tools/validate_code.class.php';

// ----------------------------------------------------------> 全局函数

include JISHUWAN_PATH.'db/db.func.php';
include JISHUWAN_PATH.'function/array.func.php';
include JISHUWAN_PATH.'function/misc.func.php';
include JISHUWAN_PATH.'function/action.php';
include JISHUWAN_PATH.'function/session.func.php';
include JISHUWAN_PATH.'function/user.func.php';
include JISHUWAN_PATH.'function/uploads.func.php';
include JISHUWAN_PATH.'function/check.func.php';
include JISHUWAN_PATH.'function/xn_encrypt.func.php';


define('COMPOSER_AUTOLOAD',APP_PATH.'vendor/autoload.php');
// 导入 composer
file_exists(COMPOSER_AUTOLOAD) AND include COMPOSER_AUTOLOAD;


$ip = ip();
$longip = ip2long($ip);
$longip < 0 AND $longip = sprintf("%u", $longip); // fix 32 位 OS 下溢出的问题


$useragent = _SERVER('HTTP_USER_AGENT');

// 全局的错误，非多线程下很方便。
$errno = 0;
$errstr = '';

empty($conf['timezone']) AND $conf['timezone'] = 'Asia/Shanghai';
date_default_timezone_set($conf['timezone']);

// 超级全局变量
!empty($_SERVER['HTTP_X_REWRITE_URL']) AND $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
!isset($_SERVER['REQUEST_URI']) AND $_SERVER['REQUEST_URI'] = '';

$_SERVER['REQUEST_URI'] = str_replace('/index.php?', '/', $_SERVER['REQUEST_URI']); // 兼容 iis6
$_REQUEST = array_merge($_COOKIE, $_POST, $_GET, xn_url_parse($_SERVER['REQUEST_URI']));

// IP 地址
!isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] = '';
!isset($_SERVER['SERVER_ADDR']) AND $_SERVER['SERVER_ADDR'] = '';
$ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(trim($_SERVER['HTTP_X_REQUESTED_WITH'])) == 'xmlhttprequest') || param('ajax');
$method = $_SERVER['REQUEST_METHOD'];


// 保存到超级全局变量，防止冲突被覆盖。
$_SERVER['starttime'] = $starttime;
$_SERVER['time'] = $time;
$_SERVER['ip'] = $ip;
$_SERVER['longip'] = $longip;
$_SERVER['useragent'] = $useragent;
$_SERVER['conf'] = $conf;
$_SERVER['errno'] = $errno;
$_SERVER['errstr'] = $errstr;
$_SERVER['method'] = $method;
$_SERVER['ajax'] = $ajax;
$_SERVER['get_magic_quotes_gpc'] = $get_magic_quotes_gpc;


// 初始化 db cache，这里并没有连接，在获取数据的时候会自动连接。
$db = !empty($conf['db']) ? db_new($conf['db']) : NULL;

$_SERVER['db'] = $db;


db_connect() OR exit($errstr);
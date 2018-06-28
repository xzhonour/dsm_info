<?php

/*
	配置文件
	支持多台 DB，主从配置好以后，xn 会自动根据 SQL 读写分离。
	支持各种 cache，本机 apc/xcache, 网络: redis/memcached/mysql
	支持 CDN，如果前端开启了 CDN 请设置 cdn_on=>1, 否则获取 IP 会不准确
	支持临时目录设置，独立 Linux 主机，可以设置为 /dev/shm 通过内存加速
*/
return array (
    'db' => array (
        'type' => 'pdo_mysql',
        'pdo_mysql' => array (
            'master' => array (
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'name' => 'dsm_info',
                'tablepre' => 'dsm_',
                'charset' => 'utf8',
                'engine' => 'innodb',
            ),
            'slaves' => array (),
        ),
    ),
    'log_path' => './log/',		// 日志目录
    'docs_path' => './docs/',

    'gid_default' => 1, // 管理用户组
    'groups' => [
        -1 => '冻结用户',0 => '普通用户', 1 => '管理组', 2 => '试用用户'
    ],

    'view_url' => '/view/',		// 可以配置单独的 CDN 域名：比如：http://static.domain.com/view/

    'sitename' => '开放设计建筑信息管理系统',
    'sitebrief' => 'DSM 群辉 开放式建筑设计信息系统',
    'timezone' => 'Asia/Shanghai',	// 时区，默认中国
    'lang' => 'zh-cn',

    'cookie_domain' => '',
    'cookie_path' => '',
    'auth_key' => 'efdkjfjiiiwurjdmclsldow753jsdj438',

    'pagesize' => 20,
    'postlist_pagesize' => 100,

    'online_update_span' => 120,	// 在线更新频度，大站设置的长一些
    'online_hold_time' => 3600,	// 在线的时间
    'session_delay_update' => 0,
    /* 支持多种 URL 格式：
        0: ?thread-create-1.htm
        1: thread-create-1.htm
        2: ?/thread/create/1  不支持
        3: /thread/create/1   不支持
    */
    'url_rewrite_on' => 0,
    'version' => '0.1',
    'installed' => 0,
);
?>
<?php


!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

$location_action = ['login'];

(in_array($action,$location_action) AND !empty($user)) AND http_location(url('index'));

$gids = ['list','add','del'];
(in_array($action,$gids) AND $gid != 1) AND http_location(url('index'));



if ($action == 'login'){

    if ($method == 'GET'){
        $header['title'] = lang('user_login');
        include APP_PATH.'view/htm/user_login.htm';
    }elseif ($method == 'POST'){
        $username = param('username');			// 用户名或者手机号 / username or mobile
        $password = param('password');
        $validate_code = param('validate_code');

        $validate_code != $_SESSION['validate_code'] AND message('validate_code', lang('validate_code_error'));

        empty($username) AND message('username', lang('username_empty'));

        if(is_mobile($username, $err)) {
            $_user = user_read_by_mobile($username);
            empty($_user) AND message('username', lang('username_or_password_error'));
        } else {
            $_user = user_read_by_username($username);
            empty($_user) AND message('username', lang('username_or_password_error'));
        }

        (strlen($password) >=18 || strlen($password) <=3) AND message('password', lang('password_length'));
        md5($password.$_user['salt']) != $_user['password'] AND message('password', lang('username_or_password_error'));

        if ($_user['gid'] < 0){
            message(1, lang('username_frozen'));
        }
        // 更新登录时间和次数
        user_update($_user['uid'], array('login_ip'=>$ip, 'login_date' =>$time , 'logins+'=>1));

        $uid = $_user['uid'];

        $_SESSION['uid'] = $uid;

        user_token_set($_user['uid']);

        // 设置 token，下次自动登陆。

        message(0, lang('login_success'));
    }
}elseif ($action == 'loginout'){
    $uid = 0;
    $_SESSION['uid'] = $uid;
    user_token_clear();
    http_location(url('index'));
}elseif ($action == 'info'){
    $uid = intval(param(2));
    $gid != $gid_default AND $uid != $user['uid'] AND message(1,lang('not_auth'));
    ($uid <= 0 || !$now_user = db_find_one('user',['uid'=>$uid])) AND  message(1,lang('username_not_exists'));

    if ($method == 'GET'){
        $header['title']    = lang('user_edit_info',['user'=>$now_user['username']]);
        include APP_PATH.'view/htm/user-info.htm';
    }elseif ($method == 'POST'){
        $data['username'] = _POST('username');
        (strlen($data['username']) >=18 || strlen($data['username']) <=3) AND message('username', lang('username_length'));
        if ($data['username'] != $now_user['username']){
            !empty(db_find_one('user',['uid'=>['<>'=>$uid],'username' => $data['username']])) AND message('username', lang('username_exists'));
        }
        $data['mobile'] = _POST('mobile');
        if ( !empty($data['mobile']) && !is_mobile($data['mobile'],$err)) {
            message('mobile',lang('mobile_not_normal'));
        }
        if ($data['mobile'] != $now_user['mobile']){
            !empty(db_find_one('user',['uid'=>['<>'=>$uid],'mobile' => $data['mobile']])) AND message('mobile', lang('mobile_exists'));
        }
        $data['realname'] = _POST('realname');
        if ( !empty($data['realname']) && !is_realname($data['realname'],$err)){
            message('realname',lang('realname_not_normal'));
        }
        $data['idnumber'] = _POST('idnumber');
        if ( !empty($data['idnumber']) && !is_idnumber($data['idnumber'],$err)){
            message('idnumber',lang('idnumber_not_normal'));
        }
        $data['qq'] = _POST('qq');
        if ( !empty($data['qq']) && !is_qq($data['qq'],$err)){
            message('qq',lang('qq_not_normal'));
        }
        $password = _POST('password');
        $old_password = _POST('old_password');
        if (!empty($password)){
            $gid != $gid_default AND md5($old_password.$now_user['salt']) != $now_user['password'] AND message('old_password', lang('old_password_not_normal'));
            $password2 = _POST('password2');
            $old_password = _POST('old_password');
            $len = strlen($password);
            if ($len > 16 || $len < 5 || $password != $password2){
                message('password',lang('password_repeat_not_normal'));
            }
            $data['password'] = md5($password.$now_user['salt']);
        }
        $data['gid'] = _POST('gid');
        $res = db_update('user',['uid'=>$uid],$data);
        if ($res){
            message(0,lang('update_success'));
        }
        message(1,lang('update_error'));
    }
}elseif ($action == 'list'){
    // 用户列表
    $users = db_find('user');
    $header['title'] = lang('user_list');
    include APP_PATH.'view/htm/user-list.htm';

}elseif ($action == 'add'){
    //添加用户
    $gid != $gid_default  AND message(1,lang('not_auth'));
    if ($method == 'GET'){
        $header['title']    = lang('user_add_info');
        include APP_PATH.'view/htm/user-add.htm';
    }elseif ($method == 'POST'){
        $data['username'] = _POST('username');
        (strlen($data['username']) >=18 || strlen($data['username']) <=3) AND message('username', lang('username_length'));

        !empty(db_find_one('user',['username' => $data['username']])) AND message('username', lang('username_exists'));

        $data['mobile'] = _POST('mobile');
        if ( !empty($data['mobile']) && !is_mobile($data['mobile'],$err)) {
            message('mobile',lang('mobile_not_normal'));
        }
        !empty($data['mobile']) AND !empty(db_find_one('user',['mobile' => $data['mobile']])) AND message('mobile', lang('mobile_exists'));

        $data['realname'] = _POST('realname');
        if ( !empty($data['realname']) && !is_realname($data['realname'],$err)){
            message('realname',lang('realname_not_normal'));
        }
        $data['idnumber'] = _POST('idnumber');

        if ( !empty($data['idnumber']) && !is_idnumber($data['idnumber'],$err)){
            message('idnumber',lang('idnumber_not_normal'));
        }

        $data['qq'] = _POST('qq');
        if ( !empty($data['qq']) && !is_qq($data['qq'],$err)){
            message('qq',lang('qq_not_normal'));
        }

        $password = _POST('password');
        $password2 = _POST('password2');
        $len = strlen($password);
        if (empty($password) || $password != $password2 || $len > 16 || $len < 5){
            message('password',lang('password_error'));
        }
        $data['salt'] = str_rand(16);
        $data['password'] = md5($password.$data['salt']);
        $data['gid'] = _POST('gid');
        $data['create_date'] = time();

        $res = db_insert('user',$data);
        if ($res){
            message(0,lang('add_success'));
        }
        message(1,lang('add_error'));
    }

}elseif ($action == 'del' && $method == 'POST'){
    $n_uid = intval(param(2));
    ($n_uid == 0 OR !db_find_one('user',['uid'=>$n_uid])) AND message(1,lang('not_auth'));
    $res = db_delete('user',['uid'=>$n_uid]);
    if ($res){
        message(0,lang('delete_success'));
    }
    message(1,lang('delete_error'));
}else{
    http_404();
}


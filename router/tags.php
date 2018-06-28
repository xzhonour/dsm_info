<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

$gid != $gid_default  AND message(1,lang('not_auth'));


if ($action == 'list'){
        if ($method == 'GET'){
            $tags = db_find('tags',['level'=>1],['sort'=>0]);
            $tags_data = xn_json_encode($tags);
            $header['title'] = lang('tags_list_title');
            include APP_PATH.'view/htm/tags-list.htm';
        }elseif ($method == 'POST'){
            $tid = intval(param(2));
            $tags = db_find('tags',['pid'=>$tid],['sort'=>0]);
            message(0,$tags);
        }
}elseif ($action == 'add'){
    $pid = intval(param(2));
    $pres = [];
    $pid != 0 AND $pres = db_find_one('tags',['tid'=>$pid]);
    if ($method == 'GET'){
        $tags = db_find('tags',['level'=>1],['sort'=>'desc']);
        $tags_data = xn_json_encode($tags);
        $header['title'] = lang('tags_add_title');
        include APP_PATH.'view/htm/tags-add.htm';
    }elseif ($method == 'POST'){
            $data['name'] = param('name');
            empty($data['name']) AND message('name',lang('tags_name_empty'));
            $data['pid'] = intval(param('pid'));
            $data['info'] = param('info');
            $data['color'] = param('color');
            empty($data['color']) AND message('color',lang('tags_color_empty'));
            $data['status'] = intval(param('status'));
            $data['sort'] = intval(param('sort'));
            $data['create_date'] = time();
            $data['level'] = 1;
            if ($data['pid'] != 0){
               $parent_tag =  db_find_one('tags',['tid'=>$data['pid']]);
               $level = $parent_tag['level']+1;
               $level > 4 AND message('pidbutton',lang('not_auth'));
                $data['level'] = $level;
            }
            $res = db_insert('tags',$data);
            if ($res){
                message(0,lang('add_success'));
            }
            message(1,lang('add_error'));
    }
}elseif($action == 'edit'){
    $tid = intval(param(2));
    ($tid <= 0 OR !$tag = db_find_one('tags',['tid'=>$tid])) AND message(1,lang('not_auth'));

    if ($method == 'GET'){
        $tags = db_find('tags',['level'=>1]);
        $header['title'] = lang('tags_edit_title');
        $parent_tag = ['pid'=>0,'name'=>'根标签'];
        $tag['level'] != 1 AND $parent_tag = db_find_one('tags',['tid'=>$tag['pid']]);
        include APP_PATH.'view/htm/tags-edit.htm';
    }elseif ($method == 'POST'){
        $data['name'] = param('name');
        empty($data['name']) AND message('name',lang('tags_name_empty'));
        $data['pid'] = intval(param('pid'));
        if ($data['pid'] != 0 AND $data['pid'] != $tag['pid']){
               $childNode = db_find_one('tags',['pid'=>$tag['tid']]);
               !empty($childNode) AND message('pidbutton',lang('tags_pid_not_empty'));
        }
        $data['info'] = param('info');
        $data['color'] = param('color');
        empty($data['color']) AND message('color',lang('tags_color_empty'));
        $data['status'] = intval(param('status'));
        $data['sort'] = intval(param('sort'));
//        $data['level'] = ($data['pid'] == 0)?1:(db_find_one('tags',['tid'=>$data['pid']])['level']+1);
        $data['level'] = 1;
        if ($data['pid'] != 0){
            $parent_tag =  db_find_one('tags',['tid'=>$data['pid']]);
            $level = $parent_tag['level']+1;
            $level > 4 AND message('pidbutton',lang('not_auth'));
            $data['level'] = $level;
        }
        $res = db_update('tags',['tid'=>$tid],$data);
        if ($res){
            message(0,lang('update_success'));
        }
        message(1,lang('update_error'));
    }

}elseif ($action == 'delete' && $method == 'POST'){
    $tid = intval(param(2));
    $tid == 0 AND !db_find_one('tags',['tid'=>$tid]) AND message(1,lang('not_auth'));
    $child = db_find_one('tags',['pid'=>$tid]);
    !empty($child) AND message(1,lang('tags_child_exists'));
    $res = db_delete('tags',['tid'=>$tid]);
    if ($res){
        message(0,lang('delete_success'));
    }
    message(1,lang('delete_error'));
}else{
    http_404();
}

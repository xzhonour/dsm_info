<?php


!defined('DEBUG') AND exit('Access Denied.');
$route = 'index';

$action = param(1);
// 模块
header('Cache-Control:no-cache,must-revalidate');
header('Pragma:no-cache');


if (empty($action) || $action == 'doc'){
    if ($method == 'GET'){
        $w = param('w');
        $my_doc = empty(param('w'))?[]:db_find('docs',['doc_name'=>['like'=>'%'.$w.'%'],'uid'=>$uid]);
        $header['title'] = lang('create_doc');
        include APP_PATH.'view/htm/create_doc.htm';
    }elseif ($method == 'POST'){
       $doc_name =  param('doc_name');
       $len = strlen($doc_name);
       ($len < 3 || $len >100) AND message('doc_name',lang('create_doc_length'));
       $data = [
            'doc_name'  =>  $doc_name,
            'uid'       =>  $uid,
            'create_time' => time(),
            'update_time' => time()
       ];
       $res = db_insert('docs',$data);
       if ($res){
           message(0,lang('add_success'),['did'=>$res]);
       }
        message(1,lang('add_error'));
    }
}elseif ($action == 'doc_step'){

    $step = intval(param(2)); // 步进
    $did = intval(param(3)); // doc_id
    $tid = intval(param(4)); // tag id
//    ($did == 0 OR $step == 0 OR !$doc =  db_find_one('docs',['did'=>$did]) OR $doc['step'.$step] != 0) AND message(1,lang('not_auth'));
    ($did == 0 OR $step == 0 OR $step > 4 OR !$doc =  db_find_one('docs',['did'=>$did]) OR ($gid != $gid_default && $uid != $doc['uid'])) AND message(1,lang('not_auth'));

    $history_tags_where = [];
    for ($i=1;$i < ($step-1);$i++){
        $history_tags_where[] = $doc['step'.$i];
    }

    $history_tags = [];
    if (!empty($history_tags_where)){
        $history_tags = db_find('tags',['tid'=>$history_tags_where],['level'=>1]);
    }
    // old stepid
    if ($step > 1 AND $tid != 0 AND $tid_insert = db_find_one('tags',['tid'=>$tid])){
        array_pop($history_tags_where);
        in_array($tid,$history_tags_where) AND http_location(http_referer());
        if ($tid != $doc['step'.($step-1)]){
            !db_update('docs',['did'=>$did],['step'.($step-1)=>$tid,'step'=>($step-1),'update_time'=>time()]) AND http_location(http_referer());
//            !db_update('docs',['did'=>$did],['step'.($step-1)=>$tid,'step'=>($step-1),'update_time'=>time()]) AND http_location(http_referer());
            $doc['step'.($step-1)] = $tid;
        }else{
            !db_update('docs',['did'=>$did],['step'=>($step-1),'update_time'=>time()]) AND http_location(http_referer());
        }
        array_push($history_tags,$tid_insert);
    }

    if (empty($history_tags) AND $step !=1){
        message(1,lang('unknown_error'));
    }

    $tags = [];
    if ($step <=4){
        $tags = db_find('tags',['level'=>$step,'pid'=>($step-1)== 0?0:$doc['step'.($step-1)]]);
    }
    if ($step == 4){

        $four_tags = empty($doc['four_tags'])?[]:explode(',',$doc['four_tags']);
    }
    $header['title'] = lang('doc_step_title',['step'=>$step-1]);
    include APP_PATH.'view/htm/step.htm';

}elseif ($action == 'save_content' && $method == 'POST'){
    $did = intval(param(2));
    ($did == 0 OR !$doc = db_find_one('docs',['did'=>$did]) OR $doc['uid'] != $uid) AND message(1,'unknown_error');
    $step3 = intval(param(3));
    $doc['step3'] != $step3 AND message(1,'unknown_error');
    $content = param('content');
    (empty($content) || strlen($content) < 3 || strlen($content) > 50) AND message('content',lang('doc_content_error'));

    $four_tags = _POST('four_tags');
    $four_tags = is_array($four_tags)?implode(',',$four_tags):'';

    $res = db_update('docs',['did'=>$did,'step3'=>$step3],['content'=>$content,'four_tags'=>$four_tags,'update_time'=>time()]);
    if ($res) {
        message(0,lang('update_success'));
    }
    message(1,lang('update_success'));
}elseif ($action == 'doc_manage'){
    $gid != $gid_default AND message(1,lang('not_auth'));
    $sql = 'SELECT A.*,B.username,B.gid FROM '.$db->tablepre.'docs AS A LEFT JOIN '.$db->tablepre.'user AS B ON A.uid=B.uid ORDER BY A.update_time DESC';
    $docs = db_sql_find($sql);
    $header['title'] = lang('doc_manage_title');
    include APP_PATH.'view/htm/doc_manage.htm';
}elseif ($action == 'delete' && $method == 'POST'){
    $did = intval(param(2));
    (($gid != $gid_default AND $gid !=0) OR ($did == 0 OR !db_find_one('docs',['did'=>$did]))) AND message(1,lang('not_auth'));
    $res = db_delete('docs',['did'=>$did]);
    if ($res) {
        message(0,lang('delete_success'));
    }
    message(1,lang('delete_success'));
}elseif ($action == 'print_to_word'){

}else{
    http_404();
}

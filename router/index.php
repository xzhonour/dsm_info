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
    $rid = intval(param('rid'));
    $doc_id = intval(param('did'));
    if ($doc_id != 0 && $rid_now = db_sql_find_one('SELECT * FROM '.$db->tablepre.'docs AS A LEFT JOIN '.$db->tablepre.'docs_route AS B ON A.did = B.did WHERE A.did='.$doc_id.($rid != 0?' AND B.rid='.$rid:'')) AND ($gid == $gid_default OR $rid_now['uid'] == $uid)){
        $step = intval($rid_now['step'])+1;
        $did = $doc_id;
        $tid = intval($rid_now['tag_3']);
        $_SESSION['route'][$did] = ['step'=>$step,'tag_1'=>intval($rid_now['tag_1']),'tag_2'=>intval($rid_now['tag_2']),'tag_3'=>intval($rid_now['tag_3'])];
    }
    (($did == 0 OR $step == 0 OR $step > 4 OR !$doc =  db_find_one('docs',['did'=>$did])) OR ($gid != $gid_default && $uid != $doc['uid'])) AND message(1,lang('not_auth'));

    $level = $step-1;
    // 验证 tid 传递是否存在

    ( $step !=1  AND db_count('tags',['tid'=>$tid,'level'=>$level]) != 1 ) AND message(1,lang('not_auth'));
    // 解决方案存到session
    $did_now = [];
    if (!isset($_SESSION[$did]) && $step == 1){
        // 新建 进入 session
        $_SESSION['route'][$did] = ['step'=>$step,'tag_1'=>0,'tag_2'=>0,'tag_3'=>0];
    }
    $did_now = &$_SESSION['route'][$did];
    for ($i = $level;$i < 4;$i++){
        $did_now['tag_'.$i] = 0;
    }
    if ($step > 1 AND $step < 5 AND $tid != 0 AND  db_find_one('tags',['tid'=>$tid,'pid'=> (($level-1 <= 0)?0:$did_now['tag_'.($level-1)]) ])){
        $did_now['tag_'.$level] = $tid;
    }
    $did_now['step'] = $level;


    // 查找历史标签
    $history_tags_where = [];
    for ($i=1;$i < $step;$i++){
        $history_tags_where[] = $did_now['tag_'.$i];
    }

    $history_tags = [];
    if (!empty($history_tags_where)){
        $history_tags = db_find('tags',['tid'=>$history_tags_where],['level'=>1]);
    }

    if (empty($history_tags) AND $step !=1){
        message(1,lang('unknown_error'));
    }

    $tags = [];
    if ($step <=4){
        $tags = db_find('tags',['level'=>$step,'pid'=>$level <= 0?0:$did_now['tag_'.$level]]);
    }

    if ($step == 4){
        $docs_route_now = db_find_one('docs_route',['did'=>$did,'step'=>$level,'tag_1'=>$did_now['tag_1'],'tag_2'=>$did_now['tag_2'],'tag_3'=>$did_now['tag_3']]);
        $four_tags = empty($docs_route_now['four_tags'])?[]:explode(',',$docs_route_now['four_tags']);
        // 查路径
        $sql = 'SELECT A.rid,A.did,A.tag_1,A.tag_2,A.tag_3,B1.name as tag_1_name,B2.name as tag_2_name,B3.name as tag_3_name,B1.color as tag_1_color,B2.color as tag_2_color,B3.color as tag_3_color FROM '.$db->tablepre.'docs_route AS A LEFT JOIN '.$db->tablepre.'tags AS B1 ON A.tag_1=B1.tid LEFT JOIN '.$db->tablepre.'tags AS B2 ON A.tag_2=B2.tid LEFT JOIN '.$db->tablepre.'tags AS B3 ON A.tag_3=B3.tid WHERE A.did='.$did;
        $docs_route = db_sql_find($sql);
    }
    $header['title'] = lang('doc_step_title',['step'=>$level]);
    include APP_PATH.'view/htm/step.htm';

}elseif ($action == 'save_content' && $method == 'POST'){
    $did = intval(param(2));
    (!isset($_SESSION['route']) OR !isset($_SESSION['route'][$did]) OR $did == 0 OR !$doc = db_find_one('docs',['did'=>$did]) OR $doc['uid'] != $uid) AND message(1,'unknown_error');
    $did_now = $_SESSION['route'][$did];

    $content = param('content');
    (empty($content) || strlen($content) < 3 || strlen($content) > 50) AND message('content',lang('doc_content_error'));

    $four_tags = _POST('four_tags');
    $four_tags = is_array($four_tags)?implode(',',$four_tags):'';

    $data = ['did'=>$did,'step'=>$did_now['step'],'tag_1'=>$did_now['tag_1'],'tag_2'=>$did_now['tag_2'],'tag_3'=>$did_now['tag_3']];
    // 更新 OR 插入
    $docs_route = db_find_one('docs_route',$data);
    if (!empty($docs_route)){
        $res = db_update('docs_route',['rid'=>$docs_route['rid']],['content'=>$content,'four_tags'=>$four_tags]);
    }else{
        $data = array_merge($data,['content'=>$content,'four_tags'=>$four_tags]);
//        $res = db_insert('docs_route',['did'=>$did,'step'=>$did_now['step'],'tag_1'=>$did_now['tag_1'],'tag_2'=>$did_now['tag_2'],'tag_3'=>$did_now['tag_3'],'content'=>$content,'four_tags'=>$four_tags]);
        $res = db_insert('docs_route',$data);
    }
    if ($res) {
        db_update('docs',['did'=>$did],['update_time'=>time()]);
        message(0,lang((!empty($docs_route)?'update':'add').'_success'));
    }
    message(1,lang((!empty($docs_route)?'update':'add').'_error'));
}elseif ($action == 'doc_manage'){
    $gid != $gid_default AND message(1,lang('not_auth'));
    /*$sql = 'SELECT A.*,B.username,B.gid,C.*,D.name as tag_1_name,E.name as tag_2_name,F.name as tag_3_name,D.color as tag_1_color,E.color as tag_2_color,F.color as tag_3_color FROM '.$db->tablepre.'docs AS A LEFT JOIN '.$db->tablepre.'user AS B ON A.uid=B.uid LEFT JOIN '.$db->tablepre.'docs_route AS C ON C.did = A.did LEFT JOIN '.$db->tablepre.'tags AS D ON D.tid= C.tag_1 LEFT JOIN '.$db->tablepre.'tags AS E ON E.tid= C.tag_2 LEFT JOIN '.$db->tablepre.'tags AS F ON F.tid= C.tag_2 ORDER BY A.update_time DESC'*/;
    $sql = 'SELECT A.*,B.username,B.gid FROM '.$db->tablepre.'docs AS A LEFT JOIN '.$db->tablepre.'user AS B ON A.uid=B.uid  ORDER BY A.update_time DESC';
    $docs = db_sql_find($sql);
    $header['title'] = lang('doc_manage_title');
    include APP_PATH.'view/htm/doc_manage.htm';
}elseif ($action == 'delete' && $method == 'POST'){
    $did = intval(param(2));
    (($gid != $gid_default AND $gid !=0) OR ($did == 0 OR !db_find_one('docs',['did'=>$did]))) AND message(1,lang('not_auth'));
//    $res = db_delete('docs',['did'=>$did]);
    $res = $db->exec('DELETE A,B FROM '.$db->tablepre.'docs_route AS A INNER JOIN '.$db->tablepre.'docs AS B ON A.did = B.did WHERE A.did='.$did);
    if ($res > 0) {
        message(0,lang('delete_success'));
    }
    message(1,lang('delete_success'));
}elseif ($action == 'delete_route' && $method == 'POST'){
    $rid = intval(param(2));
    ( ($rid == 0 OR !$now = db_sql_find_one('SELECT * FROM '.$db->tablepre.'docs_route AS A INNER JOIN '.$db->tablepre.'docs AS B ON A.did = B.did WHERE A.rid='.$rid)) OR ($gid != $gid_default AND $uid != $now['uid'])) AND message(1,lang('not_auth'));
    $res = db_delete('docs_route',['rid'=>$rid]);
//    var_dump();
    if ($res) {
        message(0,lang('delete_success'));
    }
    message(1,lang('delete_success'));
}elseif ($action == 'print_to_word'){
    $did = intval(param(2));
    $did !=0 AND $gid != 0 AND $gid != $gid_default AND http_404();
    $sql = 'SELECT A.*,B.username,B.gid,C.*,D.name as tag_1_name,E.name as tag_2_name,F.name as tag_3_name,D.color as tag_1_color,E.color as tag_2_color,F.color as tag_3_color FROM '.$db->tablepre.'docs AS A LEFT JOIN '.$db->tablepre.'user AS B ON A.uid=B.uid LEFT JOIN '.$db->tablepre.'docs_route AS C ON C.did = A.did LEFT JOIN '.$db->tablepre.'tags AS D ON D.tid= C.tag_1 LEFT JOIN '.$db->tablepre.'tags AS E ON E.tid= C.tag_2 LEFT JOIN '.$db->tablepre.'tags AS F ON F.tid= C.tag_3 WHERE A.did = '.$did.' ORDER BY E.sort DESC';
    $all = db_sql_find($sql);
    empty($all)  AND http_404();

    $print_array = [];
    foreach ($all as $k=>$v){
        $print_array['doc_name']  = $v['doc_name'];
        $print_array['update_time']  = date('Y-m-d H:i',$v['update_time']);
        $print_array['create_time']  = date('Y-m-d H:i',$v['create_time']);
        $print_array['username']  = $v['username'];
	    $print_array['did'] = $v['did'];
        $four_tags = [];
        if (!empty($v['four_tags'])){
            $four_tags = db_sql_find('SELECT name FROM '.$db->tablepre.'tags WHERE tid in('.$v['four_tags'].') ORDER BY sort DESC');
        }
        array_push($four_tags,['name'=>$v['content']]);
        $routers = [['name'=>$v['tag_1_name'],'tag_1'=>$v['tag_1']],['name'=>$v['tag_2_name'],'tag_2'=>$v['tag_2']],['name'=>$v['tag_3_name'],'tag_3'=>$v['tag_3']],'four_tags'=>$four_tags];
        $print_array['routers'][] = $routers;
    }
    $temp = [];
    foreach ($print_array['routers'] as $v){
       !isset($temp[$v[0]['tag_1']]) ? $temp[$v[0]['tag_1']] = ['name'=>$v[0]['name']]:'';
       !isset($temp[$v[0]['tag_1']]['childs'][$v[1]['tag_2']]) ? $temp[$v[0]['tag_1']]['childs'][$v[1]['tag_2']] = ['name'=>$v[1]['name']]:'';
       !isset($temp[$v[0]['tag_1']]['childs'][$v[1]['tag_2']]['childs'][$v[2]['tag_3']]) ? $temp[$v[0]['tag_1']]['childs'][$v[1]['tag_2']]['childs'][$v[2]['tag_3']] = ['name'=>$v[2]['name'],'four_tags'=>$v['four_tags']]:'';
    }
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    $section = $phpWord->addSection();
    $footer = $section->addFooter();
    $footer->addText($print_array['username'].'于'.$print_array['create_time'].'创建,最后更新于'.$print_array['update_time'].'。本系统由"技术湾"提供技术支持！'.'系统版本 V ;'.$conf['version']);
    $header = $section->addHeader();
    $header->addText('"'.$print_array['doc_name'].'"文档由"'.$conf['sitename'].'" 于'.date('Y-m-d H:i:s').'生成。');
    $phpWord->addTitleStyle(1,
        [
            'bold' => true , 'size' => 25, 'name' => 'SimSun'
        ],[
            'alignment'=>\PhpOffice\PhpWord\SimpleType\Jc::CENTER
        ]);
    $section->addTitle($print_array['doc_name']);
    $section->addTextBreak(3);
    //以下内容均由建设单位逐条确认（至少由建设单位专业负责人签字盖章，双方各持一份）
    $section->addText('以下内容均由建设单位逐条确认（至少由建设单位专业负责人签字盖章，双方各持一份）',
        ['bold' => true , 'size' => 18, 'name' => 'SimSun']);
    $section->addTextBreak(3);
    $section->addText('建设单位专业负责人：            年  月  日             建设单位技术主管：              年  月  日',[
        [ 'size' => 18, 'name' => 'SimSun']
    ],[
        'alignment'=>\PhpOffice\PhpWord\SimpleType\Jc::RIGHT
    ]);
    $section->addTextBreak(3);
    $section->addText('（建设单位盖章）',[
        [ 'size' => 18, 'name' => 'SimSun']
    ],[
        'alignment'=>\PhpOffice\PhpWord\SimpleType\Jc::RIGHT
    ]);

    $section->addTextBreak(3);
    // 列表样式
    $style_name = 'list_level';
    $phpWord->addNumberingStyle($style_name,[
        'type'  => 'multilevel',
        'levels'=>  [
            ['format'   => 'decimal','text' =>   '%1.','left'   =>  360, 'hanging' => 360, 'tabPos' => 360],
        ]
    ]);


    foreach ($temp as $v){
        $section->addListItem('阶段 : '.$v['name'],0,['bold'=>true,'name'=>'SimSun','size'=>20]);
        foreach ($v['childs'] as $t){
            $section->addListItem($t['name'],1,['bold'=>true,'name'=>'SimSun','size'=>17]);
            foreach ($t['childs'] as $th){
                $section->addListItem($th['name'],2,['name'=>'SimSun','size'=>15]);
                foreach ($th['four_tags'] as $f){
                    $section->addListItem($f['name'],3,['name'=>'SimSun','size'=>13]);
                }
            }
        }
    }
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $file_name = $print_array['did'].'_'.$print_array['username'].date('_Y_m_d_H_i').'.docx';
    $save_name = $conf['docs_path'].$file_name;
    $down_name = $print_array['doc_name'].'_'.$print_array['username'].date('_Y_m_d_H_i').'.docx';
    $objWriter->save($save_name);
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $down_name . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    $objWriter->save("php://output");
}else{
    http_404();
}

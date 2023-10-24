#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=测试bugModel->batchUpdate();
cid=1
pid=1

测试批量修改bug1 >> type,codeerror,config;title,BUG1,批量修改bug一
测试批量修改bug2 >> type,config,install;title,BUG2,批量修改bug二
测试批量修改bug3 >> type,install,security;title,BUG3,批量修改bug三

*/

$bugIDList = array('1', '2', '3');

$title = array('1' => '批量修改bug一', '2' => '批量修改bug二', '3' => '批量修改bug三');
$type  = array('1' => 'config', '2' => 'install', '3' => 'security');

$bug = new bugTest();
r($bug->batchUpdateObject($bugIDList, $title[$bugIDList[0]], $type[$bugIDList[0]], $bugIDList[0])) && p('0:field,old,new;1:field,old,new') && e('type,codeerror,config;title,BUG1,批量修改bug一'); // 测试批量修改bug1
r($bug->batchUpdateObject($bugIDList, $title[$bugIDList[1]], $type[$bugIDList[1]], $bugIDList[1])) && p('0:field,old,new;1:field,old,new') && e('type,config,install;title,BUG2,批量修改bug二');   // 测试批量修改bug2
r($bug->batchUpdateObject($bugIDList, $title[$bugIDList[2]], $type[$bugIDList[2]], $bugIDList[2])) && p('0:field,old,new;1:field,old,new') && e('type,install,security;title,BUG3,批量修改bug三'); // 测试批量修改bug3

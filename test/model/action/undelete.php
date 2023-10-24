#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->undelete();
cid=1
pid=1

测试还原action 28 >> 0
测试还原action 87 >> 0
测试还原action 9 >> 0
测试还原未删除action 1 >> 1

*/
$actionIDList = array('48', '87', '9', '1');

$action = new actionTest();

r($action->undeleteTest($actionIDList[0])) && p('extra') && e('0'); // 测试还原action 28
r($action->undeleteTest($actionIDList[1])) && p('extra') && e('0'); // 测试还原action 87
r($action->undeleteTest($actionIDList[2])) && p('extra') && e('0'); // 测试还原action 9
r($action->undeleteTest($actionIDList[3])) && p('extra') && e('1'); // 测试还原未删除action 1

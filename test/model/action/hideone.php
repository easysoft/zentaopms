#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->hideOne();
cid=1
pid=1

隐藏action 28 >> 2
隐藏action 87 >> 2
隐藏已隐藏action 9 >> 2
隐藏非删除action 1 >> 1

*/
$actionIDList = array('48', '87', '9', '1');

$action = new actionTest();

r($action->hideOneTest($actionIDList[0])) && p('extra') && e('2'); // 隐藏action 28
r($action->hideOneTest($actionIDList[1])) && p('extra') && e('2'); // 隐藏action 87
r($action->hideOneTest($actionIDList[2])) && p('extra') && e('2'); // 隐藏已隐藏action 9
r($action->hideOneTest($actionIDList[3])) && p('extra') && e('1'); // 隐藏非删除action 1

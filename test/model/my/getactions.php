#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getActions();
cid=1
pid=1

正常查询action >> 32,,32,,这是一个系统日志测试备注32
正常查询action统计 >> 5

*/

$my = new myTest();

r($my->getActionsTest())        && p('0:id,product,comment') && e('32,,32,,这是一个系统日志测试备注32');// 正常查询action
r(count($my->getActionsTest())) && p()                       && e('5');                                 // 正常查询action统计
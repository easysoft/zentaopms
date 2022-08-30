#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getActions();
cid=1
pid=1

正常查询action >> 0
正常查询action统计 >> 0

*/

$my = new myTest();

r($my->getActionsTest())        && p() && e('0'); // 正常查询action
r(count($my->getActionsTest())) && p() && e('0'); // 正常查询action统计
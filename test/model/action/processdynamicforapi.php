#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->processDynamicForAPI();
cid=1
pid=1

测试处理空数据 >> 0
处理所有动态 >> admin,admin,/home/z/tmp/1.png

*/

$action = new actionTest();

$dynamics = $tester->dao->select('*')->from(TABLE_ACTION)->fetchAll();

r($action->processDynamicForAPITest(array()))   && p() && e('0');  // 测试处理空数据
r($action->processDynamicForAPITest($dynamics)) && p('actor:account,realname,avatar') && e('admin,admin,/home/z/tmp/1.png');  // 处理所有动态

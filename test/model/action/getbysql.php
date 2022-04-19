#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getBySQL();
cid=1
pid=1

查询id like %1的动作个数 >> 12
查询objectType = product的动作个数 >> 5
查询objectID < 10的动作个数 >> 9
查询action = opend的动作个数 >> 3
查询read = 0的动作个数 >> 50

*/

$sql1 = "id like '1%'";
$sql2 = "objectType = 'product'";
$sql3 = "objectID < 10";
$sql4 = "action = 'opened'";
$sql5 = "`read` = '0'";

$action = new actionTest();

r($action->getBySQLTest($sql1)) && p() && e('12'); // 查询id like %1的动作个数
r($action->getBySQLTest($sql2)) && p() && e('5');  // 查询objectType = product的动作个数
r($action->getBySQLTest($sql3)) && p() && e('9');  // 查询objectID < 10的动作个数
r($action->getBySQLTest($sql4)) && p() && e('3');  // 查询action = opend的动作个数
r($action->getBySQLTest($sql5)) && p() && e('50'); // 查询read = 0的动作个数
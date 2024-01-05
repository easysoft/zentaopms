#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(50);

/**

title=测试 actionModel->getBySQL();
timeout=0
cid=1

- 查询id like %1的动作个数 @11
- 查询objectType = product的动作个数 @3
- 查询objectID < 10的动作个数 @9
- 查询action = opend的动作个数 @2
- 查询read = 0的动作个数 @25

*/

$sql1 = "id like '1%'";
$sql2 = "objectType = 'product'";
$sql3 = "objectID < 10";
$sql4 = "action = 'opened'";
$sql5 = "`read` = '0'";

$action = new actionTest();

r($action->getBySQLTest($sql1)) && p() && e('11'); // 查询id like %1的动作个数
r($action->getBySQLTest($sql2)) && p() && e('3');  // 查询objectType = product的动作个数
r($action->getBySQLTest($sql3)) && p() && e('9');  // 查询objectID < 10的动作个数
r($action->getBySQLTest($sql4)) && p() && e('2');  // 查询action = opend的动作个数
r($action->getBySQLTest($sql5)) && p() && e('25'); // 查询read = 0的动作个数
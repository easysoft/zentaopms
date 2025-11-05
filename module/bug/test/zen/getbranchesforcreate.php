#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBranchesForCreate();
timeout=0
cid=0

- 查看更新后的分支属性branch @0
- 查看更新后的分支属性branch @0
- 查看更新后的分支属性branch @0
- 查看更新后的分支属性branch @0
- 查看更新后的分支属性branch @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

// zendata数据准备
zenData('bug')->gen(5);

$zen = initReference('bug');
$func = $zen->getMethod('getBranchesForCreate');

$bug = $tester->loadModel('bug')->fetchByID(1);
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('0'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(2);
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('0'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(3);
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('0'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(4);
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('0'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(5);
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('0'); // 查看更新后的分支
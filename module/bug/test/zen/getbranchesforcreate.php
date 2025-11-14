#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBranchesForCreate();
timeout=0
cid=15448

- 查看更新后的分支属性branch @1
- 查看更新后的分支属性branch @1
- 查看更新后的分支属性branch @1
- 查看更新后的分支属性branch @1
- 查看更新后的分支属性branch @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

// zendata数据准备
zenData('bug')->gen(5);
$product = zenData('product');
$product->type->range('branch');
$product->gen(1);
$branch = zenData('branch');
$branch->name->range('branch');
$branch->product->range('1');
$branch->gen(5);

$zen = initReference('bug');
$func = $zen->getMethod('getBranchesForCreate');

$bug = $tester->loadModel('bug')->fetchByID(1);
$bug->productID = 1;
$bug->branch    = 1;
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('1'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(2);
$bug->productID = 1;
$bug->branch    = 1;
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('1'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(3);
$bug->productID = 1;
$bug->branch    = 1;
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('1'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(4);
$bug->productID = 1;
$bug->branch    = 1;
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('1'); // 查看更新后的分支

$bug = $tester->loadModel('bug')->fetchByID(5);
$bug->productID = 1;
$bug->branch    = 1;
$result = $func->invokeArgs($zen->newInstance(), [$bug]);
r($result) && p('branch') && e('1'); // 查看更新后的分支
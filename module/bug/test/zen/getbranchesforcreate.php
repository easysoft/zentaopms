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

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

// zendata数据准备
zenData('user')->gen(5);
zenData('bug')->gen(0);
$bug = zenData('bug');
$bug->id->range('1-5');
$bug->product->range('1');
$bug->execution->range('0');
$bug->status->range('active{5}');
$bug->title->range('bug{5}');
$bug->deleted->range('0{5}');
$bug->gen(5);
zenData('product')->gen(0);
$product = zenData('product');
$product->id->range('1');
$product->name->range('分支产品');
$product->type->range('branch');
$product->status->range('normal');
$product->acl->range('open');
$product->deleted->range('0');
$product->gen(1);
zenData('branch')->gen(0);
$branch = zenData('branch');
$branch->id->range('1-5');
$branch->name->range('branch');
$branch->product->range('1');
$branch->status->range('active');
$branch->deleted->range('0{5}');
$branch->gen(5);

$company = zenData('company');
$company->admins->range('`,admin,`');
$company->gen(1);
global $app;
$app->company->admins = ',admin,';
su('admin');

$zen = initReference('bug');
$func = $zen->getMethod('getBranchesForCreate');

$tester->loadModel('bug')->mao->cache = null;
restoreObjectTables();
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

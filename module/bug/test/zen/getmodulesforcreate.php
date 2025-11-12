#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getModulesForCreate();
timeout=0
cid=0

- 步骤1:正常产品,有效的模块ID属性moduleID @1
- 步骤2:正常产品,无效的模块ID属性moduleID @~~
- 步骤3:分支产品,指定有效分支属性moduleID @2
- 步骤4:分支为all的情况属性moduleID @1
- 步骤5:模块ID为0的情况属性moduleID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(10);

$module = zenData('module');
$module->id->range('1-20');
$module->root->range('1-10');
$module->branch->range('0{15},1{3},2{2}');
$module->type->range('bug');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->parent->range('0');
$module->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`');
$module->grade->range('1');
$module->deleted->range('0');
$module->gen(20);

zenData('branch')->gen(10);
zenData('user')->gen(10);

su('admin');

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->productID = 1;
$bug1->branch = '0';
$bug1->moduleID = 1;
$bug1->branches = array();

$bug2 = new stdClass();
$bug2->productID = 1;
$bug2->branch = '0';
$bug2->moduleID = 999;
$bug2->branches = array();

$bug3 = new stdClass();
$bug3->productID = 2;
$bug3->branch = '1';
$bug3->moduleID = 2;
$bug3->branches = array('1' => 'Branch1');

$bug4 = new stdClass();
$bug4->productID = 1;
$bug4->branch = 'all';
$bug4->moduleID = 1;
$bug4->branches = array('all' => 'All');

$bug5 = new stdClass();
$bug5->productID = 1;
$bug5->branch = '0';
$bug5->moduleID = 0;
$bug5->branches = array();

r($bugTest->getModulesForCreateTest($bug1)) && p('moduleID') && e('1'); // 步骤1:正常产品,有效的模块ID
r($bugTest->getModulesForCreateTest($bug2)) && p('moduleID') && e('~~'); // 步骤2:正常产品,无效的模块ID
r($bugTest->getModulesForCreateTest($bug3)) && p('moduleID') && e('2'); // 步骤3:分支产品,指定有效分支
r($bugTest->getModulesForCreateTest($bug4)) && p('moduleID') && e('1'); // 步骤4:分支为all的情况
r($bugTest->getModulesForCreateTest($bug5)) && p('moduleID') && e('0'); // 步骤5:模块ID为0的情况
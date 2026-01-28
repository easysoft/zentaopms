#!/usr/bin/env php
<?php

/**

title=测试 biModel::getCorrectGroup();
timeout=0
cid=15164

- 测试步骤1: 测试单个有效的chart模块ID @1
- 测试步骤2: 测试单个有效的pivot模块ID @13
- 测试步骤3: 测试不存在的模块ID @0
- 测试步骤4: 测试多个有效模块ID用逗号分隔 @1,2

- 测试步骤5: 测试包含无效ID和空ID的混合列表 @1,2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$module = zenData('module');
$module->id->range('1-20');
$module->root->range('1');
$module->name->range('产品,项目,测试,组织');
$module->parent->range('0');
$module->path->range(',1,,2,,3,,4,');
$module->grade->range('1');
$module->order->range('1-20');
$module->type->range('chart{10},pivot{10}');
$module->branch->range('0');
$module->from->range('0');
$module->owner->range('``');
$module->collector->range('``');
$module->short->range('``');
$module->deleted->range('0');
$module->gen(20);

su('admin');

$biTest = new biModelTest();

r($biTest->getCorrectGroupTest('32', 'chart')) && p() && e('1'); // 测试步骤1: 测试单个有效的chart模块ID
r($biTest->getCorrectGroupTest('59', 'pivot')) && p() && e('13'); // 测试步骤2: 测试单个有效的pivot模块ID
r($biTest->getCorrectGroupTest('999', 'chart')) && p() && e('0'); // 测试步骤3: 测试不存在的模块ID
r($biTest->getCorrectGroupTest('32,33', 'chart')) && p() && e('1,2'); // 测试步骤4: 测试多个有效模块ID用逗号分隔
r($biTest->getCorrectGroupTest('32,999,33', 'chart')) && p() && e('1,2'); // 测试步骤5: 测试包含无效ID和空ID的混合列表
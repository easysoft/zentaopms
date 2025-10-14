#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleStatisticBlock();
timeout=0
cid=0

- 步骤1：正常产品统计区块属性type @normal
- 步骤2：指定count参数的产品统计区块属性count @10
- 步骤3：无type参数的产品统计区块属性type @~~
- 步骤4：无count参数的产品统计区块属性count @~~
- 步骤5：验证product对象的创建第product条的id属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$table->type->range('normal{5},branch{3},platform{2}');
$table->status->range('normal{8},closed{2}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

// 简化数据准备，只准备基本必要数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->type = 'normal';
$block1->params->count = '5';
r($blockTest->printSingleStatisticBlockTest($block1)) && p('type') && e('normal'); // 步骤1：正常产品统计区块

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->type = 'closed';
$block2->params->count = '10';
r($blockTest->printSingleStatisticBlockTest($block2)) && p('count') && e('10'); // 步骤2：指定count参数的产品统计区块

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->count = '8';
r($blockTest->printSingleStatisticBlockTest($block3)) && p('type') && e('~~'); // 步骤3：无type参数的产品统计区块

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->type = 'active';
r($blockTest->printSingleStatisticBlockTest($block4)) && p('count') && e('~~'); // 步骤4：无count参数的产品统计区块

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->type = 'all';
$block5->params->count = '15';
r($blockTest->printSingleStatisticBlockTest($block5)) && p('product:id') && e('1'); // 步骤5：验证product对象的创建
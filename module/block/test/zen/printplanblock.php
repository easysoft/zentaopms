#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printPlanBlock();
timeout=0
cid=0

- 执行blockTest模块的printPlanBlockTest方法，参数是$block1 属性count @10
- 执行blockTest模块的printPlanBlockTest方法，参数是$block2 属性count @5
- 执行blockTest模块的printPlanBlockTest方法，参数是$block3 属性type @done
- 执行blockTest模块的printPlanBlockTest方法，参数是$block4 属性count @0
- 执行blockTest模块的printPlanBlockTest方法，参数是$block5 
 - 属性count @15
 - 属性type @wait

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_printplanblock', false, 2)->gen(5);

$productplanTable = zenData('productplan');
$productplanTable->loadYaml('productplan_printplanblock', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 10;
$block1->params->type = '';
r($blockTest->printPlanBlockTest($block1)) && p('count') && e('10');

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->count = 5;
$block2->params->type = '';
r($blockTest->printPlanBlockTest($block2)) && p('count') && e('5');

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->count = 8;
$block3->params->type = 'done';
r($blockTest->printPlanBlockTest($block3)) && p('type') && e('done');

$block4 = new stdclass();
$block4->params = new stdclass();
r($blockTest->printPlanBlockTest($block4)) && p('count') && e('0');

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->count = 15;
$block5->params->type = 'wait';
r($blockTest->printPlanBlockTest($block5)) && p('count,type') && e('15,wait');
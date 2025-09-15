#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSinglePlanBlock();
timeout=0
cid=0

- 执行blockTest模块的printSinglePlanBlockTest方法，参数是$block1 属性count @10
- 执行blockTest模块的printSinglePlanBlockTest方法，参数是$block2 
 - 属性count @0
 - 属性planCount @5
- 执行blockTest模块的printSinglePlanBlockTest方法，参数是$block3 属性error @block对象必须包含params属性
- 执行blockTest模块的printSinglePlanBlockTest方法，参数是'invalid' 属性error @参数必须是对象
- 执行blockTest模块的printSinglePlanBlockTest方法，参数是$block5 
 - 属性count @15
 - 属性sessionSet @1
 - 属性productLoaded @1
 - 属性viewSet @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_printsingleplanblock', false, 2)->gen(5);

$productplanTable = zenData('productplan');
$productplanTable->loadYaml('productplan_printsingleplanblock', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况 - count=10
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 10;
r($blockTest->printSinglePlanBlockTest($block1)) && p('count') && e('10');

// 步骤2：边界值 - count=0
$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->count = 0;
r($blockTest->printSinglePlanBlockTest($block2)) && p('count,planCount') && e('0,5');

// 步骤3：异常输入 - 没有params属性
$block3 = new stdclass();
r($blockTest->printSinglePlanBlockTest($block3)) && p('error') && e('block对象必须包含params属性');

// 步骤4：异常输入 - 传入字符串而非对象
r($blockTest->printSinglePlanBlockTest('invalid')) && p('error') && e('参数必须是对象');

// 步骤5：业务规则验证 - count=15的完整验证
$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->count = 15;
r($blockTest->printSinglePlanBlockTest($block5)) && p('count,sessionSet,productLoaded,viewSet') && e('15,1,1,1');
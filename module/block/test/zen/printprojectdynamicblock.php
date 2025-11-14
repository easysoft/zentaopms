#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectDynamicBlock();
timeout=0
cid=15275

- 步骤1：正常情况属性actions @1
- 步骤2：指定数量属性actions @1
- 步骤3：默认参数属性actions @1
- 步骤4：零数量属性actions @1
- 步骤5：无参数对象属性actions @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$actionTable = zenData('action');
$actionTable->loadYaml('action_printprojectdynamicblock', false, 2)->gen(20);

$userTable = zenData('user');
$userTable->loadYaml('user_printprojectdynamicblock', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 创建区块对象用于测试
$normalBlock = new stdclass();
$normalBlock->params = new stdclass();
$normalBlock->params->count = 10;

$countBlock = new stdclass();
$countBlock->params = new stdclass();
$countBlock->params->count = 5;

$emptyBlock = new stdclass();

$zeroCountBlock = new stdclass();
$zeroCountBlock->params = new stdclass();
$zeroCountBlock->params->count = 0;

$noParamsBlock = new stdclass();
$noParamsBlock->params = null;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printProjectDynamicBlockTest($normalBlock)) && p('actions') && e('1'); // 步骤1：正常情况
r($blockTest->printProjectDynamicBlockTest($countBlock)) && p('actions') && e('1'); // 步骤2：指定数量
r($blockTest->printProjectDynamicBlockTest($emptyBlock)) && p('actions') && e('1'); // 步骤3：默认参数
r($blockTest->printProjectDynamicBlockTest($zeroCountBlock)) && p('actions') && e('1'); // 步骤4：零数量
r($blockTest->printProjectDynamicBlockTest($noParamsBlock)) && p('actions') && e('1'); // 步骤5：无参数对象
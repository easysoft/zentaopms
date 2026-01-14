#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::checkAppNameUnique();
timeout=0
cid=16783

- 步骤1：不存在的应用名称 @1
- 步骤2：存在于instance表中的名称 @0
- 步骤3：存在于pipeline表中的名称 @0
- 步骤4：空字符串 @1
- 步骤5：特殊字符 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->name->range('test-app-1,test-app-2,unique-app,existing-app,demo-app');
$instanceTable->deleted->range('0{4},1');
$instanceTable->gen(5);

$pipelineTable = zenData('pipeline');
$pipelineTable->id->range('1-3');
$pipelineTable->name->range('pipeline-app-1,pipeline-app-2,shared-name');
$pipelineTable->deleted->range('0{2},1');
$pipelineTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$instanceTest = new instanceModelTest();

// 5. 执行测试步骤
r($instanceTest->checkAppNameUniqueTest('new-unique-app')) && p() && e('1'); // 步骤1：不存在的应用名称
r($instanceTest->checkAppNameUniqueTest('test-app-1')) && p() && e('0'); // 步骤2：存在于instance表中的名称
r($instanceTest->checkAppNameUniqueTest('pipeline-app-1')) && p() && e('0'); // 步骤3：存在于pipeline表中的名称
r($instanceTest->checkAppNameUniqueTest('')) && p() && e('1'); // 步骤4：空字符串
r($instanceTest->checkAppNameUniqueTest('special@#$%^&*()')) && p() && e('1'); // 步骤5：特殊字符
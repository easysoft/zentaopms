#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildOperateMenu();
timeout=0
cid=0

- 步骤1：正常情况测试返回的菜单结构第mainActions条的0属性 @edit
- 步骤2：边界值测试后缀动作第suffixActions条的0属性 @view
- 步骤3：异常输入测试返回空数组 @0
- 步骤4：空模块名使用默认模块第mainActions条的0属性 @edit
- 步骤5：业务规则测试第二个主要动作第mainActions条的1属性 @delete

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 4. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$testData1 = (object)array('id' => '1', 'name' => '任务1', 'status' => 'wait', 'assignedTo' => 'admin');
$testData2 = (object)array('id' => '999', 'name' => '不存在任务');
$testData3 = (object)array('id' => '1');
$testData4 = (object)array('id' => '2', 'name' => '任务2', 'status' => 'doing');
$testData5 = (object)array('id' => '0', 'name' => '', 'status' => '');

r($commonTest->buildOperateMenuTest($testData1, 'task')) && p('mainActions:0') && e('edit'); // 步骤1：正常情况测试返回的菜单结构
r($commonTest->buildOperateMenuTest($testData2, 'task')) && p('suffixActions:0') && e('view'); // 步骤2：边界值测试后缀动作
r($commonTest->buildOperateMenuTest($testData3, 'invalid_module')) && p() && e('0'); // 步骤3：异常输入测试返回空数组
r($commonTest->buildOperateMenuTest($testData4, '')) && p('mainActions:0') && e('edit'); // 步骤4：空模块名使用默认模块
r($commonTest->buildOperateMenuTest($testData5, 'task')) && p('mainActions:1') && e('delete'); // 步骤5：业务规则测试第二个主要动作
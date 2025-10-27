#!/usr/bin/env php
<?php

/**

title=测试 actionModel::renderAction();
timeout=0
cid=0

- 步骤1：正常渲染 @*
- 步骤2：缺少必要属性 @alse
- 步骤3：自定义描述 @自定义操作描述
- 步骤4：数组描述 @审核通过
- 步骤5：空objectType @alse

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备
zenData('company')->gen(1);
zenData('user')->gen(5);
zenData('action')->gen(0);
zenData('story')->gen(1);
zenData('task')->gen(1);
zenData('bug')->gen(1);
zenData('project')->loadYaml('execution')->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$actionTest = new actionTest();

// 5. 执行测试步骤
r($actionTest->renderActionTest((object)array('objectType' => 'story', 'objectID' => 1, 'action' => 'created', 'actor' => 'admin', 'date' => '2024-01-01 10:00:00', 'extra' => ''))) && p() && e('*'); // 步骤1：正常渲染
r($actionTest->renderActionTest((object)array('id' => 999, 'actor' => 'admin'))) && p() && e(false); // 步骤2：缺少必要属性
r($actionTest->renderActionTest((object)array('objectType' => 'task', 'action' => 'created', 'actor' => 'user1'), '自定义操作描述')) && p() && e('自定义操作描述'); // 步骤3：自定义描述
r($actionTest->renderActionTest((object)array('objectType' => 'story', 'action' => 'reviewed', 'actor' => 'reviewer', 'extra' => 'pass'), array('main' => '审核通过'))) && p() && e('审核通过'); // 步骤4：数组描述
r($actionTest->renderActionTest((object)array('objectType' => '', 'action' => 'created', 'actor' => 'admin'))) && p() && e(false); // 步骤5：空objectType
#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getCustomFields();
timeout=0
cid=0

- 步骤1：正常batchCreate操作 @module,story,assignedTo,estimate,estStarted,deadline,desc,pri

- 步骤2：stage类型排除时间字段 @module,story,assignedTo,estimate,desc,pri

- 步骤3：ops类型排除story字段 @module,assignedTo,estimate,estStarted,deadline,desc,pri

- 步骤4：batchEdit操作获取字段 @module,assignedTo,status,pri,estimate,record,left

- 步骤5：request属性排除story字段 @module,assignedTo,estimate,estStarted,deadline,desc,pri

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$taskTest = new taskTest();

// 4. 创建测试execution对象
$normalExecution = (object)array('id' => 1, 'type' => 'sprint', 'lifetime' => 'short', 'attribute' => 'normal');
$stageExecution = (object)array('id' => 2, 'type' => 'stage', 'lifetime' => 'short', 'attribute' => 'normal');
$opsExecution = (object)array('id' => 3, 'type' => 'sprint', 'lifetime' => 'ops', 'attribute' => 'normal');
$requestExecution = (object)array('id' => 4, 'type' => 'sprint', 'lifetime' => 'short', 'attribute' => 'request');
$reviewExecution = (object)array('id' => 5, 'type' => 'sprint', 'lifetime' => 'short', 'attribute' => 'review');

// 5. 执行测试步骤（至少5个测试步骤）
r($taskTest->getCustomFieldsTestWithObject($normalExecution, 'batchCreate')) && p('0') && e('module,story,assignedTo,estimate,estStarted,deadline,desc,pri'); // 步骤1：正常batchCreate操作
r($taskTest->getCustomFieldsTestWithObject($stageExecution, 'batchCreate')) && p('0') && e('module,story,assignedTo,estimate,desc,pri'); // 步骤2：stage类型排除时间字段
r($taskTest->getCustomFieldsTestWithObject($opsExecution, 'batchCreate')) && p('0') && e('module,assignedTo,estimate,estStarted,deadline,desc,pri'); // 步骤3：ops类型排除story字段
r($taskTest->getCustomFieldsTestWithObject($normalExecution, 'batchEdit')) && p('0') && e('module,assignedTo,status,pri,estimate,record,left'); // 步骤4：batchEdit操作获取字段
r($taskTest->getCustomFieldsTestWithObject($requestExecution, 'batchCreate')) && p('0') && e('module,assignedTo,estimate,estStarted,deadline,desc,pri'); // 步骤5：request属性排除story字段
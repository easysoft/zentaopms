#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getKanbanStatusMap();
timeout=0
cid=16323

- 执行executionTest模块的getKanbanStatusMapTest方法，参数是'0')['task']['wait']['doing']  @start
- 执行executionTest模块的getKanbanStatusMapTest方法，参数是'0')['bug']['wait']['done']  @resolve
- 执行executionTest模块的getKanbanStatusMapTest方法，参数是'1'  @2
- 执行executionTest模块的getKanbanStatusMapTest方法，参数是'0')['task']['doing']['pause']  @pause
- 执行executionTest模块的getKanbanStatusMapTest方法，参数是'0')['bug']['done']['closed']  @close

*/

// 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 用户登录
su('admin');

// 创建测试实例
$executionTest = new executionTest();

// 测试步骤1：测试管理员用户的任务状态映射
r($executionTest->getKanbanStatusMapTest('0')['task']['wait']['doing']) && p() && e('start');

// 测试步骤2：测试管理员用户的bug状态映射
r($executionTest->getKanbanStatusMapTest('0')['bug']['wait']['done']) && p() && e('resolve');

// 测试步骤3：测试返回的状态映射数组结构
r($executionTest->getKanbanStatusMapTest('1')) && p() && e('2');

// 测试步骤4：测试具体的任务状态转换功能
r($executionTest->getKanbanStatusMapTest('0')['task']['doing']['pause']) && p() && e('pause');

// 测试步骤5：测试具体的bug状态转换功能
r($executionTest->getKanbanStatusMapTest('0')['bug']['done']['closed']) && p() && e('close');
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试 executionTao::setKanbanMenu();
timeout=0
cid=16398

- 执行$menu第kanban条的link属性 @看板|execution|kanban|executionID=%s
- 执行$menu第CFD条的link属性 @累积流图|execution|cfd|executionID=%s
- 执行$menu第build条的link属性 @构建|execution|build|executionID=%s
- 执行$menu第settings条的link属性 @设置|execution|view|executionID=%s
- 执行$menu第kanban条的subModule属性 @task
- 执行$menu第build条的alias属性 @bug
- 执行$menu第settings条的class属性 @dropdown dropdown-hover
- 执行$menu2第kanban条的link属性 @看板|execution|kanban|executionID=%s

*/

$executionTester = new executionTest();

// 测试步骤1：验证看板菜单的link属性
$menu = $executionTester->setKanbanMenuTest();
r($menu) && p('kanban:link') && e('看板|execution|kanban|executionID=%s');

// 测试步骤2：验证CFD累积流图菜单的link属性
r($menu) && p('CFD:link') && e('累积流图|execution|cfd|executionID=%s');

// 测试步骤3：验证构建菜单的link属性
r($menu) && p('build:link') && e('构建|execution|build|executionID=%s');

// 测试步骤4：验证设置菜单的基本link属性
r($menu) && p('settings:link') && e('设置|execution|view|executionID=%s');

// 测试步骤5：验证看板菜单的subModule属性
r($menu) && p('kanban:subModule') && e('task');

// 测试步骤6：验证构建菜单的alias属性
r($menu) && p('build:alias') && e('bug');

// 测试步骤7：验证设置菜单的class属性
r($menu) && p('settings:class') && e('dropdown dropdown-hover');

// 测试步骤8：测试多次调用方法的幂等性
$menu2 = $executionTester->setKanbanMenuTest();
r($menu2) && p('kanban:link') && e('看板|execution|kanban|executionID=%s');
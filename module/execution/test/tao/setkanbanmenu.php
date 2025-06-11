#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试executionModel->setKanbanMenu();
timeout=0
cid=1

- 测试替换看板执行的二级菜单第kanban条的link属性 @看板|execution|kanban|executionID=%s
- 查看构建菜单第build条的link属性 @构建|execution|build|executionID=%s
- 查看累积流图菜单第CFD条的link属性 @累积流图|execution|cfd|executionID=%s
- 查看设置菜单第settings条的link属性 @设置|execution|view|executionID=%s

*/

$executionTester = new executionTest();
$menu = $executionTester->setKanbanMenuTest();
r($menu) && p('kanban:link')      && e('看板|execution|kanban|executionID=%s'); // 测试替换看板执行的二级菜单
r($menu) && p('build:link')       && e('构建|execution|build|executionID=%s'); // 查看构建菜单
r($menu) && p('CFD:link')         && e('累积流图|execution|cfd|executionID=%s'); // 查看累积流图菜单
r($menu) && p('settings:link')    && e('设置|execution|view|executionID=%s'); // 查看设置菜单

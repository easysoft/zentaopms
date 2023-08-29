#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

/**

title=测试executionModel->setKanbanMenu();
timeout=0
cid=1

*/

$executionTester = new executionTest();
r($executionTester->setKanbanMenuTest()) && p('kanban:link') && e('看板|execution|kanban|executionID=%s'); // 测试替换看板执行的二级菜单

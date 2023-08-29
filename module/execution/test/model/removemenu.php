#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('execution')->gen(10);

/**

title=测试executionModel->removeMenu();
timeout=0
cid=1

*/

$executionIdList = array(11, 60, 100);

$executionTester = new executionTest();
r($executionTester->removeMenuTest($executionIdList[0])) && p('more:link')   && e('更多|execution|more|%s');                   // 测试移除迭代的导航
r($executionTester->removeMenuTest($executionIdList[1])) && p('task:link')   && e('任务|execution|task|executionID=%s');       // 测试移除阶段的导航
r($executionTester->removeMenuTest($executionIdList[2])) && p('kanban:link') && e('看板|execution|taskkanban|executionID=%s'); // 测试移除看板的导航

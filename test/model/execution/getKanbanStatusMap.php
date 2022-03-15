#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanStatusMapTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanStatusMapTest($count[0])["task"]) && p('wait:doing') && e('start');   // 看板tesk查询
r($execution->getKanbanStatusMapTest($count[0])["bug"])  && p('wait:done')  && e('resolve'); // 看板bug查询
r($execution->getKanbanStatusMapTest($count[1]))         && p()             && e('2');       // 看板统计

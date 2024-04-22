#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
su('admin');

/**

title=测试executionModel->getKanbanStatusMapTest();
cid=1
pid=1

看板tesk查询 >> start
看板bug查询 >> resolve
看板统计 >> 2

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanStatusMapTest($count[0])["task"]) && p('wait:doing') && e('start');   // 看板tesk查询
r($execution->getKanbanStatusMapTest($count[0])["bug"])  && p('wait:done')  && e('resolve'); // 看板bug查询
r($execution->getKanbanStatusMapTest($count[1]))         && p()             && e('2');       // 看板统计
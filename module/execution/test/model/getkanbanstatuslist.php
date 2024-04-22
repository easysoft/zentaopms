#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
su('admin');

/**

title=测试executionModel->getKanbanStatusListTest();
cid=1
pid=1

看板状态列表查询 >> 未开始
看板状态列表数量统计 >> 7

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanStatusListTest($count[0])) && p('wait') && e('未开始');// 看板状态列表查询
r($execution->getKanbanStatusListTest($count[1])) && p()       && e('7');     // 看板状态列表数量统计
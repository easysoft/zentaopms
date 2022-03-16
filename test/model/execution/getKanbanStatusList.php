#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanStatusListTest();
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
r($execution->getKanbanStatusListTest($count[0])) && p('wait') && e('未开始');// 看板状态列表查询
r($execution->getKanbanStatusListTest($count[1])) && p()       && e('7');     // 看板状态列表数量统计

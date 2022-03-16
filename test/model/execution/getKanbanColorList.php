#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanColorListTest();
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
r($execution->getKanbanColorListTest($count[0])) && p('wait') && e('#7EC5FF'); // 看板状态颜色查询
r($execution->getKanbanColorListTest($count[1])) && p()       && e('6');       // 看板状态颜色数量统计

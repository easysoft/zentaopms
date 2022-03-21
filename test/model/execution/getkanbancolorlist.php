#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanColorListTest();
cid=1
pid=1

看板状态颜色查询 >> #7EC5FF
看板状态颜色数量统计 >> 6

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanColorListTest($count[0])) && p('wait') && e('#7EC5FF'); // 看板状态颜色查询
r($execution->getKanbanColorListTest($count[1])) && p()       && e('6');       // 看板状态颜色数量统计
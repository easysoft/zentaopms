#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanColumnsTest();
cid=1
pid=1

看板列查询 >> wait
看板列查询统计 >> 6

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanColumnsTest($count[0])[0]) && p() && e('wait'); // 看板列查询
r($execution->getKanbanColumnsTest($count[1]))    && p() && e('4');    // 看板列查询统计

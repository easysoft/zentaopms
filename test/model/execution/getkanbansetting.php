#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanSettingTest();
cid=1
pid=1

看板设置查询 >> #7EC5FF
看板设置查询统计 >> 6

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanSettingTest($count[0])) && p('colorList:wait') && e('#7EC5FF'); // 看板设置查询
r($execution->getKanbanSettingTest($count[1])) && p()                 && e('6');       // 看板设置查询统计
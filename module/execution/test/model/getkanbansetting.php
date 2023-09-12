#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
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
r($execution->getKanbanSettingTest($count[0])) && p('colorList:wait') && e('~f:7EC5FF$~'); // 看板设置查询。注：以'7EC5FF'结尾，参见 https://ztf.im/book/ztf/specific-regx-186.html
r($execution->getKanbanSettingTest($count[1])) && p()                 && e('6');           // 看板设置查询统计

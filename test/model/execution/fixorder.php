#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->fixOrderTest();
cid=1
pid=1

重置order >> 15

*/

$execution = new executionTest();
r($execution->fixOrderTest()) && p('101:order') && e('15');  // 重置order
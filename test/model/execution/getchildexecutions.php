#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getChildExecutionsTest();
cid=1
pid=1

查询子阶段 >> 子阶段1
查询子阶段数量 >> 1

*/

$executionID = '131';
$count       = array('0','1');

$execution = new executionTest();
r($execution->getChildExecutionsTest($executionID,$count[0])) && p('701')  && e('子阶段1'); // 查询子阶段
r($execution->getChildExecutionsTest($executionID,$count[1])) && p()       && e('1');       // 查询子阶段数量
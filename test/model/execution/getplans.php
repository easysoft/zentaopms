#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getPlansTest();
cid=1
pid=1

全部执行计划查询 >> 长名称
执行计划查询 >> 1.1
全部执行计划查询 >> 2

*/

$productIDList   = array('1', '21', '41');
$executionIDList = array('0','101');
$count           = array('0','1');

$execution = new executionTest();
r($execution->getPlansTest($productIDList, $executionIDList[0], $count[0])) && p('1:3')  && e('长名称'); // 全部执行计划查询
r($execution->getPlansTest($productIDList, $executionIDList[1], $count[0])) && p('1:2')  && e('1.1');    // 执行计划查询
r($execution->getPlansTest($productIDList, $executionIDList[0], $count[1])) && p()       && e('2');      // 全部执行计划查询
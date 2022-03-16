#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getPlansTest();
cid=1
pid=1

敏捷执行关闭 >> status,wait,getPlansd
瀑布执行关闭 >> status,doing,getPlansd
看板执行关闭 >> status,doing,getPlansd
不输入实际完成时间校验 >> 『realEnd』不能为空。

*/

$productIDList   = array('1', '21', '41');
$executionIDList = array('0','101');
$count           = array('0','1');

$execution = new executionTest();
r($execution->getPlansTest($productIDList, $executionIDList[0], $count[0])) && p('1:3')  && e('长名称');                        // 全部执行计划查询
r($execution->getPlansTest($productIDList, $executionIDList[1], $count[0])) && p('1:2')  && e('1.1 [2021-05-23 ~ 2021-09-23]'); // 执行计划查询
r($execution->getPlansTest($productIDList, $executionIDList[0], $count[1])) && p()       && e('2');                             // 全部执行计划查询

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getDateListTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$start = array('2022-01-01', '2025-01-01');
$end   = array('2022-01-07', '2025-01-07');
$count = array('0', '1');
$type  = array('noweekend', 'week');

$execution = new executionTest();
r($execution->getDateListTest($start[0], $end[0], $type[0] ,$count[0])) && p('0:0') && e('01/03/2022'); // 去除工作日日期列表
r($execution->getDateListTest($start[0], $end[0], $type[0] ,$count[1])) && p()      && e('5');          // 去除工作日日期列表统计
r($execution->getDateListTest($start[0], $end[0], $type[1] ,$count[0])) && p('0:0') && e('01/01/2022'); // 未去除工作日日期列表
r($execution->getDateListTest($start[0], $end[0], $type[1] ,$count[1])) && p()      && e('7');          // 未去除工作日日期列表统计
r($execution->getDateListTest($start[1], $end[0], $type[1] ,$count[0])) && p()      && e('无数据');     // 日期输入错误查询

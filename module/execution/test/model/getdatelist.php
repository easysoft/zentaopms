#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试executionModel->getDateListTest();
timeout=0
cid=16310

- 去除工作日日期列表第0条的0属性 @01/03/2022
- 去除工作日日期列表统计 @5
- 未去除工作日日期列表第0条的0属性 @01/01/2022
- 未去除工作日日期列表统计 @7
- 日期输入错误查询 @无数据
- 去除工作日日期列表第0条的0属性 @01/03/2022
- 去除工作日日期间隔1天列表统计 @3
- 未去除工作日间隔2天日期列表第0条的0属性 @01/01/2022
- 未去除工作日间隔2天日期列表统计 @1

*/

$start    = array('2022-01-01', '2025-01-01');
$end      = array('2022-01-07', '2025-01-07');
$count    = array(0, 1);
$type     = array('noweekend', 'week');
$interval = array(1, 7);

$execution = new executionModelTest();
r($execution->getDateListTest($start[0], $end[0], $type[0] ,$count[0]))               && p('0:0') && e('01/03/2022'); // 去除工作日日期列表
r($execution->getDateListTest($start[0], $end[0], $type[0] ,$count[1]))               && p()      && e('5');          // 去除工作日日期列表统计
r($execution->getDateListTest($start[0], $end[0], $type[1] ,$count[0]))               && p('0:0') && e('01/01/2022'); // 未去除工作日日期列表
r($execution->getDateListTest($start[0], $end[0], $type[1] ,$count[1]))               && p()      && e('7');          // 未去除工作日日期列表统计
r($execution->getDateListTest($start[1], $end[0], $type[1] ,$count[0]))               && p()      && e('无数据');     // 日期输入错误查询
r($execution->getDateListTest($start[0], $end[0], $type[0] ,$count[0]))               && p('0:0') && e('01/03/2022'); // 去除工作日日期列表
r($execution->getDateListTest($start[0], $end[0], $type[0] ,$count[1], $interval[0])) && p()      && e('3');          // 去除工作日日期间隔1天列表统计
r($execution->getDateListTest($start[0], $end[0], $type[1] ,$count[0], $interval[1])) && p('0:0') && e('01/01/2022'); // 未去除工作日间隔2天日期列表
r($execution->getDateListTest($start[0], $end[0], $type[1] ,$count[1], $interval[1])) && p()      && e('1');          // 未去除工作日间隔2天日期列表统计

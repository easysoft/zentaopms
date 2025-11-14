#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(8);
zenData('kanbanlane')->loadYaml('kanbanlane')->gen(10);
zenData('kanbancolumn')->loadYaml('kanbancolumn')->gen(18);
zenData('kanbancell')->loadYaml('kanbancell')->gen(18);

/**

title=taskModel->appendLane();
timeout=0
cid=18765

- 测试空数据 @0
- 测试给任务1、2追加泳道名称第1条的lane属性 @lane1
- 测试给任务1、2追加泳道名称的数量 @2
- 测试给任务3、4追加泳道名称第3条的lane属性 @`^$`
- 测试给任务3、4追加泳道名称的数量 @2
- 测试给任务1-8追加泳道名称第2条的lane属性 @lane1
- 测试给任务1-8追加泳道名称的数量 @8

*/

$taskIdList = array(array(), array(1, 2), array(3, 4), array(1, 2, 3, 4, 5, 6 ,7 ,8));

$taskTest = new taskTest();
r($taskTest->appendLaneObject($taskIdList[0]))        && p()         && e('0');     // 测试空数据
r($taskTest->appendLaneObject($taskIdList[1]))        && p('1:lane') && e('lane1'); // 测试给任务1、2追加泳道名称
r(count($taskTest->appendLaneObject($taskIdList[1]))) && p()         && e('2');     // 测试给任务1、2追加泳道名称的数量
r($taskTest->appendLaneObject($taskIdList[2]))        && p('3:lane') && e('`^$`');  // 测试给任务3、4追加泳道名称
r(count($taskTest->appendLaneObject($taskIdList[2]))) && p('')       && e('2');     // 测试给任务3、4追加泳道名称的数量
r($taskTest->appendLaneObject($taskIdList[3]))        && p('2:lane') && e('lane1'); // 测试给任务1-8追加泳道名称
r(count($taskTest->appendLaneObject($taskIdList[3]))) && p()         && e('8');     // 测试给任务1-8追加泳道名称的数量
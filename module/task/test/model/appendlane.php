#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('task')->config('task')->gen(8);
zdTable('kanbanlane')->config('kanbanlane')->gen(10);
zdTable('kanbancolumn')->config('kanbancolumn')->gen(18);
zdTable('kanbancell')->config('kanbancell')->gen(18);

/**

title=taskModel->appendLane();
timeout=0
cid=1

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

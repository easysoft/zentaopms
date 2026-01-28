#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('project')->loadYaml('project', true)->gen(10);
zenData('task')->loadYaml('task', true)->gen(30);

/**

title=测试updateTaskEsDateByGantt方法
timeout=0
cid=18892

- 测试检查普通任务开始日期 @已超出阶段计划开始时间，请先修改阶段计划开始时间
- 测试检查普通任务结束日期 @已超出阶段计划结束时间，请先修改阶段计划结束时间
- 测试检查子任务开始日期 @已超出父任务计划开始时间，请先修改父任务计划开始时间
- 测试检查子任务开始日期 @已超出父任务计划结束时间，请先修改父任务计划结束时间
- 测试检查更新普通任务 @1
- 测试更新父任务 @1
- 测试更新子任务 @1
- 测试更新串行任务 @1
- 测试更新并行任务 @1

*/

$taskIdList = array(1, 6, 7, 8, 9);
$beginList  = array('2020-11-01', '2020-11-08');
$endList    = array('2022-02-01', '2020-12-30');

$taskTester = new taskTaoTest();

r($taskTester->updateTaskEsDateByGanttTest($taskIdList[0], $beginList[0], $endList[0])) && p('0') && e('已超出阶段计划开始时间，请先修改阶段计划开始时间');     // 测试检查普通任务开始日期
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[0], $beginList[1], $endList[0])) && p('0') && e('已超出阶段计划结束时间，请先修改阶段计划结束时间');     // 测试检查普通任务结束日期
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[2], $beginList[0], $endList[0])) && p('0') && e('已超出父任务计划开始时间，请先修改父任务计划开始时间'); // 测试检查子任务开始日期
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[2], $beginList[1], $endList[0])) && p('0') && e('已超出父任务计划结束时间，请先修改父任务计划结束时间'); // 测试检查子任务开始日期
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[0], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试检查更新普通任务
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[1], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新父任务
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[2], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新子任务
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[3], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新串行任务
r($taskTester->updateTaskEsDateByGanttTest($taskIdList[4], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新并行任务
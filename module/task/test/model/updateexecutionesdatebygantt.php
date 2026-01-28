#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('execution', true)->gen(30);

/**

title=taskModel->updateExecutionEsDateByGantt();
timeout=0
cid=0

- 测试检查阶段开始日期 @已超出项目计划开始时间，请先修改项目计划开始时间
- 测试检查阶段结束日期 @已超出项目计划结束时间，请先修改项目计划结束时间
- 测试检查子阶段结束日期 @已超出父阶段计划开始时间，请先修改父阶段计划开始时间
- 测试检查子阶段结束日期 @已超出父阶段计划结束时间，请先修改父阶段计划结束时间
- 测试更新阶段日期 @1
- 测试更新父阶段日期 @1
- 测试更新子阶段日期 @1

*/

$executionIdList = array(110, 111, 112);
$beginList       = array('2020-11-01', '2020-12-01');
$endList         = array('2022-11-01', '2021-12-01');

$taskTester = new taskModelTest();

r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[0], $beginList[0], $endList[0])) && p('0') && e('已超出项目计划开始时间，请先修改项目计划开始时间');     // 测试检查阶段开始日期
r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[0], $beginList[1], $endList[0])) && p('0') && e('已超出项目计划结束时间，请先修改项目计划结束时间');     // 测试检查阶段结束日期
r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[2], $beginList[0], $endList[0])) && p('0') && e('已超出父阶段计划开始时间，请先修改父阶段计划开始时间'); // 测试检查子阶段结束日期
r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[2], $beginList[1], $endList[0])) && p('0') && e('已超出父阶段计划结束时间，请先修改父阶段计划结束时间'); // 测试检查子阶段结束日期
r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[0], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新阶段日期
r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[1], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新父阶段日期
r($taskTester->updateExecutionEsDateByGanttTest($executionIdList[2], $beginList[1], $endList[1])) && p()    && e('1');                                                    // 测试更新子阶段日期

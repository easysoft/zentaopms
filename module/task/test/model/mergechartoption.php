#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=taskModel->mergeChartOption();
timeout=0
cid=18833

- 合并按迭代任务数统计报表的配置
 - 第graph条的xAxisName属性 @迭代
 - 第graph条的caption属性 @按迭代任务数统计
- 合并按模块任务数统计报表的配置
 - 第graph条的xAxisName属性 @模块
 - 第graph条的caption属性 @按模块任务数统计
- 合并按指派给统计报表的配置
 - 第graph条的xAxisName属性 @用户
 - 第graph条的caption属性 @按指派给统计
- 合并按任务类型统计报表的配置
 - 第graph条的xAxisName属性 @类型
 - 第graph条的caption属性 @按任务类型统计
- 合并按优先级统计报表的配置
 - 第graph条的xAxisName属性 @优先级
 - 第graph条的caption属性 @按优先级统计
- 合并按任务状态统计报表的配置
 - 第graph条的xAxisName属性 @状态
 - 第graph条的caption属性 @按任务状态统计
- 合并按截止日期统计报表的配置
 - 第graph条的xAxisName属性 @日期
 - 第graph条的caption属性 @按截止日期统计
- 合并按预计时间统计报表的配置
 - 第graph条的xAxisName属性 @时间
 - 第graph条的caption属性 @按预计时间统计
- 合并按剩余时间统计报表的配置
 - 第graph条的xAxisName属性 @时间
 - 第graph条的caption属性 @按剩余时间统计
- 合并按消耗时间统计报表的配置
 - 第graph条的xAxisName属性 @时间
 - 第graph条的caption属性 @按消耗时间统计
- 合并按由谁完成统计报表的配置
 - 第graph条的xAxisName属性 @用户
 - 第graph条的caption属性 @按由谁完成统计
- 合并按关闭原因统计报表的配置
 - 第graph条的xAxisName属性 @关闭原因
 - 第graph条的caption属性 @按关闭原因统计
- 合并按每天完成统计报表的配置
 - 第graph条的xAxisName属性 @日期
 - 第graph条的caption属性 @按每天完成统计

*/

$chartTypeList = array('tasksPerExecution', 'tasksPerModule', 'tasksPerAssignedTo', 'tasksPerType', 'tasksPerPri', 'tasksPerStatus', 'tasksPerDeadline', 'tasksPerEstimate', 'tasksPerLeft', 'tasksPerConsumed', 'tasksPerFinishedBy', 'tasksPerClosedReason', 'finishedTasksPerDay');

$taskTester = new taskModelTest();
r($taskTester->mergeChartOptionTest($chartTypeList[0]))  && p('graph:xAxisName,caption') && e('迭代,按迭代任务数统计');   // 合并按迭代任务数统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[1]))  && p('graph:xAxisName,caption') && e('模块,按模块任务数统计');   // 合并按模块任务数统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[2]))  && p('graph:xAxisName,caption') && e('用户,按指派给统计');       // 合并按指派给统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[3]))  && p('graph:xAxisName,caption') && e('类型,按任务类型统计');     // 合并按任务类型统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[4]))  && p('graph:xAxisName,caption') && e('优先级,按优先级统计');     // 合并按优先级统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[5]))  && p('graph:xAxisName,caption') && e('状态,按任务状态统计');     // 合并按任务状态统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[6]))  && p('graph:xAxisName,caption') && e('日期,按截止日期统计');     // 合并按截止日期统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[7]))  && p('graph:xAxisName,caption') && e('时间,按预计时间统计');     // 合并按预计时间统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[8]))  && p('graph:xAxisName,caption') && e('时间,按剩余时间统计');     // 合并按剩余时间统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[9]))  && p('graph:xAxisName,caption') && e('时间,按消耗时间统计');     // 合并按消耗时间统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[10])) && p('graph:xAxisName,caption') && e('用户,按由谁完成统计');     // 合并按由谁完成统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[11])) && p('graph:xAxisName,caption') && e('关闭原因,按关闭原因统计'); // 合并按关闭原因统计报表的配置
r($taskTester->mergeChartOptionTest($chartTypeList[12])) && p('graph:xAxisName,caption') && e('日期,按每天完成统计');     // 合并按每天完成统计报表的配置

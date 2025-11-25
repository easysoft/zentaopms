#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('project')->loadYaml('project_isnostoryexecution')->gen(9);

/**

title=taskModel->isNoStoryExecution();
timeout=0
cid=18830

- 测试执行为短期执行 阶段类型为mix的执行是否有需求列表 @0
- 测试执行为短期执行 阶段类型request为的执行是否有需求列表 @0
- 测试执行为短期执行 阶段类型为review的执行是否有需求列表 @0
- 测试执行为长期执行 阶段类型为mix的执行是否有需求列表 @0
- 测试执行为长期执行 阶段类型request为的执行是否有需求列表 @0
- 测试执行为长期执行 阶段类型为review的执行是否有需求列表 @0
- 测试执行为运维执行 阶段类型为mix的执行是否有需求列表 @1
- 测试执行为运维执行 阶段类型request为的执行是否有需求列表 @1
- 测试执行为运维执行 阶段类型为review的执行是否有需求列表 @1

*/

$executionIdList = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

$task = new taskTest();
r($task->isNoStoryExecutionTest($executionIdList[0]))   && p() && e('0'); // 测试执行为短期执行 阶段类型为mix的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[1]))   && p() && e('0'); // 测试执行为短期执行 阶段类型request为的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[2]))   && p() && e('0'); // 测试执行为短期执行 阶段类型为review的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[3]))   && p() && e('0'); // 测试执行为长期执行 阶段类型为mix的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[4]))   && p() && e('0'); // 测试执行为长期执行 阶段类型request为的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[5]))   && p() && e('0'); // 测试执行为长期执行 阶段类型为review的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[6]))   && p() && e('1'); // 测试执行为运维执行 阶段类型为mix的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[7]))   && p() && e('1'); // 测试执行为运维执行 阶段类型request为的执行是否有需求列表
r($task->isNoStoryExecutionTest($executionIdList[8]))   && p() && e('1'); // 测试执行为运维执行 阶段类型为review的执行是否有需求列表
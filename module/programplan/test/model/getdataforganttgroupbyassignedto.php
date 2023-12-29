#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getDataForGanttGroupByAssignedTo();
cid=0

- 测试获取阶段数据第0条的type属性 @group
- 测试获取任务数据
 - 第4条的id属性 @8
 - 第4条的type属性 @task
- 测试获取阶段数据(JSON类型返回值)第0条的type属性 @group
- 测试获取任务数据(JSON类型返回值)
 - 第4条的id属性 @8
 - 第4条的type属性 @task
- 测试自定义参数为空的情况获取任务数据 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('task')->config('task')->gen(8);
zdTable('projectproduct')->config('projectproduct')->gen(5);

$programplan = new programplanTest();

$selectCustom     = 'date,task';
$selectNoneCustom = '';
r($programplan->getDataForGanttGroupByAssignedToTest(1, 1, 0, $selectCustom, false))            && p('0:type')    && e('group');  // 测试获取阶段数据
r($programplan->getDataForGanttGroupByAssignedToTest(1, 1, 0, $selectCustom, false))            && p('4:id,type') && e('8,task'); // 测试获取任务数据
r($programplan->getDataForGanttGroupByAssignedToTest(1, 1, 0, $selectCustom, true))             && p('0:type')    && e('group');  // 测试获取阶段数据(JSON类型返回值)
r($programplan->getDataForGanttGroupByAssignedToTest(1, 1, 0, $selectCustom, true))             && p('4:id,type') && e('8,task'); // 测试获取任务数据(JSON类型返回值)
r(count($programplan->getDataForGanttGroupByAssignedToTest(1, 1, 0, $selectNoneCustom, false))) && p()            && e('1');      // 测试自定义参数为空的情况获取任务数据

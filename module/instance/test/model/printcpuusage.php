#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::printCpuUsage();
timeout=0
cid=16810

- 步骤1：停止状态显示灰色属性color @gray
- 步骤2：用户来源返回空值属性color @~~
- 步骤3：低使用率显示secondary属性color @secondary
- 步骤4：高使用率显示warning属性color @warning
- 步骤5：极高使用率显示danger属性color @danger

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

$instance = zenData('instance');
$instance->id->range('1-5');
$instance->source->range('cloud{2},user{1},system{2}');
$instance->status->range('running{3},stopped{2}');
$instance->gen(5);

su('admin');

$instanceTest = new instanceTest();

// 测试步骤1：用户来源实例应返回空数组
$userInstance = new stdClass();
$userInstance->source = 'user';
$userInstance->status = 'running';

// 测试步骤2：停止状态实例应显示灰色
$stoppedInstance = new stdClass();
$stoppedInstance->source = 'cloud';
$stoppedInstance->status = 'stopped';

$metrics2 = new stdClass();
$metrics2->rate = 80;
$metrics2->usage = 80;
$metrics2->limit = 100;

r($instanceTest->printCpuUsageTest($stoppedInstance, $metrics2)) && p('color') && e('gray'); // 步骤1：停止状态显示灰色

$metrics1 = new stdClass();
$metrics1->rate = 50;
$metrics1->usage = 50;
$metrics1->limit = 100;

r($instanceTest->printCpuUsageTest($userInstance, $metrics1)) && p('color') && e('~~'); // 步骤2：用户来源返回空值

// 测试步骤3：正常运行低使用率应显示secondary颜色
$runningInstance = new stdClass();
$runningInstance->source = 'cloud';
$runningInstance->status = 'running';

$metrics3 = new stdClass();
$metrics3->rate = 30;
$metrics3->usage = 30;
$metrics3->limit = 100;

r($instanceTest->printCpuUsageTest($runningInstance, $metrics3)) && p('color') && e('secondary'); // 步骤3：低使用率显示secondary

// 测试步骤4：高使用率应显示warning颜色
$metrics4 = new stdClass();
$metrics4->rate = 60;
$metrics4->usage = 60;
$metrics4->limit = 100;

r($instanceTest->printCpuUsageTest($runningInstance, $metrics4)) && p('color') && e('warning'); // 步骤4：高使用率显示warning

// 测试步骤5：极高使用率应显示danger颜色
$metrics5 = new stdClass();
$metrics5->rate = 90;
$metrics5->usage = 90;
$metrics5->limit = 100;

r($instanceTest->printCpuUsageTest($runningInstance, $metrics5)) && p('color') && e('danger'); // 步骤5：极高使用率显示danger
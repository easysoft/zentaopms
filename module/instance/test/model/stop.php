#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::stop();
timeout=0
cid=0

- 步骤1：正常停止运行中的实例属性code @600
- 步骤2：停止异常状态的实例属性code @600
- 步骤3：停止已停止的实例属性code @600
- 步骤4：验证实例k8name属性 @test-k8-1
- 步骤5：验证实例chart属性 @zentao

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备
$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->name->range('test-app{5}');
$instanceTable->k8name->range('test-k8-{5}');
$instanceTable->chart->range('zentao,gitlab,jenkins,sonar,mysql');
$instanceTable->status->range('running{2},abnormal{1},stopped{1},destroying{1}');
$instanceTable->channel->range('stable{5}');
$instanceTable->space->range('1-5');
$instanceTable->deleted->range('0{5}');
$instanceTable->gen(5);

$spaceTable = zenData('space');
$spaceTable->id->range('1-5');
$spaceTable->k8space->range('default,test-space,dev-space,prod-space,staging-space');
$spaceTable->name->range('默认空间,测试空间,开发空间,生产空间,演示空间');
$spaceTable->deleted->range('0{5}');
$spaceTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$instanceTest = new instanceTest();

// 5. 测试步骤（至少5个）
// 构造测试实例对象
$runningInstance = new stdclass();
$runningInstance->id = 1;
$runningInstance->k8name = 'test-k8-1';
$runningInstance->chart = 'zentao';
$runningInstance->channel = 'stable';
$runningInstance->spaceData = new stdclass();
$runningInstance->spaceData->k8space = 'default';

$abnormalInstance = new stdclass();
$abnormalInstance->id = 2;
$abnormalInstance->k8name = 'test-k8-2';
$abnormalInstance->chart = 'gitlab';
$abnormalInstance->channel = 'stable';
$abnormalInstance->spaceData = new stdclass();
$abnormalInstance->spaceData->k8space = 'test-space';

$stoppedInstance = new stdclass();
$stoppedInstance->id = 3;
$stoppedInstance->k8name = 'test-k8-3';
$stoppedInstance->chart = 'jenkins';
$stoppedInstance->channel = 'stable';
$stoppedInstance->spaceData = new stdclass();
$stoppedInstance->spaceData->k8space = 'dev-space';

r($instanceTest->stopTest($runningInstance)) && p('code') && e('600'); // 步骤1：正常停止运行中的实例
r($instanceTest->stopTest($abnormalInstance)) && p('code') && e('600'); // 步骤2：停止异常状态的实例  
r($instanceTest->stopTest($stoppedInstance)) && p('code') && e('600'); // 步骤3：停止已停止的实例
r($runningInstance->k8name) && p() && e('test-k8-1'); // 步骤4：验证实例k8name属性
r($runningInstance->chart) && p() && e('zentao'); // 步骤5：验证实例chart属性
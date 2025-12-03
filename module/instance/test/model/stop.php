#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::stop();
timeout=0
cid=0

- 步骤1:正常停止实例属性code @600
- 步骤2:停止运行中的实例属性code @600
- 步骤3:停止异常状态的实例属性code @600
- 步骤4:停止已停止的实例属性code @600
- 步骤5:验证实例k8name属性 @test-k8-1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->name->range('test-app{5}');
$instanceTable->k8name->range('test-k8-{5}');
$instanceTable->chart->range('zentao,gitlab,jenkins,sonar,mysql');
$instanceTable->status->range('running{2},abnormal{2},stopped{1}');
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
$instanceTest = new instanceModelTest();

// 5. 测试步骤(至少5个)
// 构造测试实例对象
$validInstance = new stdclass();
$validInstance->id = 1;
$validInstance->k8name = 'test-k8-1';
$validInstance->chart = 'zentao';
$validInstance->channel = 'stable';
$validInstance->spaceData = new stdclass();
$validInstance->spaceData->k8space = 'default';

$runningInstance = new stdclass();
$runningInstance->id = 2;
$runningInstance->k8name = 'test-k8-2';
$runningInstance->chart = 'gitlab';
$runningInstance->channel = 'stable';
$runningInstance->spaceData = new stdclass();
$runningInstance->spaceData->k8space = 'test-space';

$abnormalInstance = new stdclass();
$abnormalInstance->id = 3;
$abnormalInstance->k8name = 'test-k8-3';
$abnormalInstance->chart = 'jenkins';
$abnormalInstance->channel = 'stable';
$abnormalInstance->spaceData = new stdclass();
$abnormalInstance->spaceData->k8space = 'dev-space';

$stoppedInstance = new stdclass();
$stoppedInstance->id = 4;
$stoppedInstance->k8name = 'test-k8-4';
$stoppedInstance->chart = 'sonar';
$stoppedInstance->channel = 'stable';
$stoppedInstance->spaceData = new stdclass();
$stoppedInstance->spaceData->k8space = 'prod-space';

r($instanceTest->stopTest($validInstance)) && p('code') && e('600'); // 步骤1:正常停止实例
r($instanceTest->stopTest($runningInstance)) && p('code') && e('600'); // 步骤2:停止运行中的实例
r($instanceTest->stopTest($abnormalInstance)) && p('code') && e('600'); // 步骤3:停止异常状态的实例
r($instanceTest->stopTest($stoppedInstance)) && p('code') && e('600'); // 步骤4:停止已停止的实例
r($validInstance->k8name) && p() && e('test-k8-1'); // 步骤5:验证实例k8name属性
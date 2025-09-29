#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::stop();
timeout=0
cid=0

- 步骤1：成功停止运行中的实例属性code @200
- 步骤2：停止实例时API调用失败属性code @400
- 步骤3：停止实例时服务器错误属性code @500
- 步骤4：停止不存在的实例属性code @404
- 步骤5：验证成功停止时的消息属性message @Success

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
$successInstance = new stdclass();
$successInstance->id = 1;
$successInstance->k8name = 'test-k8-1';
$successInstance->chart = 'zentao';
$successInstance->channel = 'stable';
$successInstance->spaceData = new stdclass();
$successInstance->spaceData->k8space = 'default';

$failedInstance = new stdclass();
$failedInstance->id = 2;
$failedInstance->k8name = 'test-k8-2';
$failedInstance->chart = 'gitlab';
$failedInstance->channel = 'stable';
$failedInstance->spaceData = new stdclass();
$failedInstance->spaceData->k8space = 'test-space';

$serverErrorInstance = new stdclass();
$serverErrorInstance->id = 3;
$serverErrorInstance->k8name = 'test-k8-3';
$serverErrorInstance->chart = 'jenkins';
$serverErrorInstance->channel = 'stable';
$serverErrorInstance->spaceData = new stdclass();
$serverErrorInstance->spaceData->k8space = 'dev-space';

$notFoundInstance = new stdclass();
$notFoundInstance->id = 4;
$notFoundInstance->k8name = 'test-k8-4';
$notFoundInstance->chart = 'sonar';
$notFoundInstance->channel = 'stable';
$notFoundInstance->spaceData = new stdclass();
$notFoundInstance->spaceData->k8space = 'prod-space';

r($instanceTest->stopTest($successInstance)) && p('code') && e('200'); // 步骤1：成功停止运行中的实例
r($instanceTest->stopTest($failedInstance)) && p('code') && e('400'); // 步骤2：停止实例时API调用失败
r($instanceTest->stopTest($serverErrorInstance)) && p('code') && e('500'); // 步骤3：停止实例时服务器错误
r($instanceTest->stopTest($notFoundInstance)) && p('code') && e('404'); // 步骤4：停止不存在的实例
r($instanceTest->stopTest($successInstance)) && p('message') && e('Success'); // 步骤5：验证成功停止时的消息
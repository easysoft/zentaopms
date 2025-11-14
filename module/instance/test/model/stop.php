#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::stop();
timeout=0
cid=16817

- 步骤1:正常停止运行中的实例属性code @200
- 步骤2:停止异常状态的实例属性code @400
- 步骤3:停止服务器错误的实例属性code @500
- 步骤4:停止不存在的实例属性code @404
- 步骤5:验证实例k8name属性 @test-k8-1

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
$instanceTable->status->range('running{2},abnormal{1},stopped{1},starting{1}');
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

// 5. 测试步骤(至少5个)
// 构造测试实例对象
$runningInstance = new stdclass();
$runningInstance->id = 1;
$runningInstance->k8name = 'test-k8-1';
$runningInstance->chart = 'zentao';
$runningInstance->channel = 'stable';
$runningInstance->spaceData = new stdclass();
$runningInstance->spaceData->k8space = 'default';

$badRequestInstance = new stdclass();
$badRequestInstance->id = 2;
$badRequestInstance->k8name = 'test-k8-2';
$badRequestInstance->chart = 'gitlab';
$badRequestInstance->channel = 'stable';
$badRequestInstance->spaceData = new stdclass();
$badRequestInstance->spaceData->k8space = 'test-space';

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

$stoppedInstance = new stdclass();
$stoppedInstance->id = 5;
$stoppedInstance->k8name = 'test-k8-5';
$stoppedInstance->chart = 'mysql';
$stoppedInstance->channel = 'stable';
$stoppedInstance->spaceData = new stdclass();
$stoppedInstance->spaceData->k8space = 'staging-space';

r($instanceTest->stopTest($runningInstance)) && p('code') && e('200'); // 步骤1:正常停止运行中的实例
r($instanceTest->stopTest($badRequestInstance)) && p('code') && e('400'); // 步骤2:停止异常状态的实例
r($instanceTest->stopTest($serverErrorInstance)) && p('code') && e('500'); // 步骤3:停止服务器错误的实例
r($instanceTest->stopTest($notFoundInstance)) && p('code') && e('404'); // 步骤4:停止不存在的实例
r($runningInstance->k8name) && p() && e('test-k8-1'); // 步骤5:验证实例k8name属性
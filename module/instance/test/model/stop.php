#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::stop();
timeout=0
cid=0

- 执行instanceTest模块的stopTest方法，参数是$successInstance 属性code @200
- 执行instanceTest模块的stopTest方法，参数是$failedInstance 属性code @400
- 执行instanceTest模块的stopTest方法，参数是$serverErrorInstance 属性code @500
- 执行instanceTest模块的stopTest方法，参数是$notFoundInstance 属性code @404
- 执行instanceTest模块的stopTest方法，参数是$messageInstance 属性message @Success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$instanceTest = new instanceTest();

// 4. 构造测试数据对象
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
$failedInstance->spaceData->k8space = 'default';

$serverErrorInstance = new stdclass();
$serverErrorInstance->id = 3;
$serverErrorInstance->k8name = 'test-k8-3';
$serverErrorInstance->chart = 'jenkins';
$serverErrorInstance->channel = 'stable';
$serverErrorInstance->spaceData = new stdclass();
$serverErrorInstance->spaceData->k8space = 'default';

$notFoundInstance = new stdclass();
$notFoundInstance->id = 4;
$notFoundInstance->k8name = 'test-k8-4';
$notFoundInstance->chart = 'sonar';
$notFoundInstance->channel = 'stable';
$notFoundInstance->spaceData = new stdclass();
$notFoundInstance->spaceData->k8space = 'default';

$messageInstance = new stdclass();
$messageInstance->id = 1;
$messageInstance->k8name = 'test-k8-1';
$messageInstance->chart = 'zentao';
$messageInstance->channel = 'stable';
$messageInstance->spaceData = new stdclass();
$messageInstance->spaceData->k8space = 'default';

// 5. 测试步骤（必须至少5个）
r($instanceTest->stopTest($successInstance)) && p('code') && e('200');
r($instanceTest->stopTest($failedInstance)) && p('code') && e('400');
r($instanceTest->stopTest($serverErrorInstance)) && p('code') && e('500');
r($instanceTest->stopTest($notFoundInstance)) && p('code') && e('404');
r($instanceTest->stopTest($messageInstance)) && p('message') && e('Success');
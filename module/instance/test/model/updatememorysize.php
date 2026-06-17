#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::updateMemorySize();
timeout=0
cid=16823

- 执行instanceTest模块的updateMemorySizeTest方法，参数是$instance1, 1024  @0
- 执行instanceTest模块的updateMemorySizeTest方法，参数是$instance2, 0  @0
- 执行instanceTest模块的updateMemorySizeTest方法，参数是$instance3, 2048  @0
- 执行instanceTest模块的updateMemorySizeTest方法，参数是$instance4, 4096  @0
- 执行 @调整内存失败

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$instanceTest = new instanceModelTest();

$instance1 = new stdClass();
$instance1->id = 1;
$instance1->k8name = 'test-app-1';
$instance1->chart = 'zentao';
$instance1->channel = 'stable';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'default';
$instance1->oldValue = 512;

$instance2 = clone $instance1;
$instance2->id = 2;
$instance2->k8name = 'test-app-2';
$instance2->oldValue = 1024;

$instance3 = clone $instance1;
$instance3->id = 3;
$instance3->k8name = 'test-app-3';
$instance3->oldValue = 2048;

$instance4 = clone $instance1;
$instance4->id = 4;
$instance4->k8name = 'test-app-4';
$instance4->oldValue = 1024;

r($instanceTest->updateMemorySizeTest($instance1, 1024)) && p('0') && e('调整内存失败');
r($instanceTest->updateMemorySizeTest($instance2, 0)) && p('0') && e('调整内存失败');
r($instanceTest->updateMemorySizeTest($instance3, 2048)) && p('0') && e('调整内存失败');
r($instanceTest->updateMemorySizeTest($instance4, 4096)) && p('0') && e('调整内存失败');
r(isset($instance4->spaceData->k8space) && $instance4->spaceData->k8space == 'default') && p() && e('1');

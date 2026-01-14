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

zenData('instance')->loadYaml('instance_updatememorysize', false, 2)->gen(5);
zenData('space')->loadYaml('space')->gen(5);

su('admin');

$instanceTest = new instanceModelTest();

$instance1 = $instanceTest->objectModel->getByID(1);
$instance1->oldValue = 512;

$instance2 = $instanceTest->objectModel->getByID(2);
$instance2->oldValue = 1024;

$instance3 = $instanceTest->objectModel->getByID(3);
$instance3->oldValue = 2048;

$instance4 = $instanceTest->objectModel->getByID(4);
$instance4->oldValue = 1024;

$instance5 = $instanceTest->objectModel->getByID(5);
$instance5->oldValue = 512;

r($instanceTest->updateMemorySizeTest($instance1, 1024)) && p() && e('0');
r($instanceTest->updateMemorySizeTest($instance2, 0)) && p() && e('0');
r($instanceTest->updateMemorySizeTest($instance3, 2048)) && p() && e('0');
r($instanceTest->updateMemorySizeTest($instance4, 4096)) && p() && e('0');
r(dao::getError()) && p('0') && e('调整内存失败');
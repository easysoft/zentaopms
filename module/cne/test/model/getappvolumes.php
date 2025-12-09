#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppVolumes();
timeout=0
cid=15613

- 步骤1:不指定组件获取数据卷(component=false) @0
- 步骤2:指定mysql组件(component=true) @0
- 步骤3:指定具体组件名称(component='mysql') @0
- 步骤4:指定其他组件名称(component='redis') @0
- 步骤5:空字符串组件名称(component='') @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

// 准备测试用的实例对象
$validInstance = new stdClass();
$validInstance->spaceData = new stdClass();
$validInstance->spaceData->k8space = 'default';
$validInstance->k8name = 'test-app';

r($cneTest->getAppVolumesTest($validInstance, false)) && p() && e('0'); // 步骤1:不指定组件获取数据卷(component=false)
r($cneTest->getAppVolumesTest($validInstance, true)) && p() && e('0'); // 步骤2:指定mysql组件(component=true)
r($cneTest->getAppVolumesTest($validInstance, 'mysql')) && p() && e('0'); // 步骤3:指定具体组件名称(component='mysql')
r($cneTest->getAppVolumesTest($validInstance, 'redis')) && p() && e('0'); // 步骤4:指定其他组件名称(component='redis')
r($cneTest->getAppVolumesTest($validInstance, '')) && p() && e('0'); // 步骤5:空字符串组件名称(component='')
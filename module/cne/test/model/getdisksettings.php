#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDiskSettings();
timeout=0
cid=15618

- 步骤1:不指定组件获取磁盘配置(component=false) @1
- 步骤2:指定mysql组件(component=true) @1
- 步骤3:指定具体mysql组件名称 @1
- 步骤4:指定其他组件名称redis @1
- 步骤5:空字符串组件名称 @1

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

r(is_object($cneTest->getDiskSettingsTest($validInstance, false))) && p() && e('1'); // 步骤1:不指定组件获取磁盘配置(component=false)
r(is_object($cneTest->getDiskSettingsTest($validInstance, true))) && p() && e('1'); // 步骤2:指定mysql组件(component=true)
r(is_object($cneTest->getDiskSettingsTest($validInstance, 'mysql'))) && p() && e('1'); // 步骤3:指定具体mysql组件名称
r(is_object($cneTest->getDiskSettingsTest($validInstance, 'redis'))) && p() && e('1'); // 步骤4:指定其他组件名称redis
r(is_object($cneTest->getDiskSettingsTest($validInstance, ''))) && p() && e('1'); // 步骤5:空字符串组件名称
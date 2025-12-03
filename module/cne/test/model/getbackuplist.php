#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupList();
timeout=0
cid=15614

- 执行cneTest模块的getBackupListTest方法，参数是$instance1  @1
- 执行cneTest模块的getBackupListTest方法，参数是$instance2  @1
- 执行cneTest模块的getBackupListTest方法，参数是$instance3  @1
- 执行cneTest模块的getBackupListTest方法，参数是$instance4  @1
- 执行cneTest模块的getBackupListTest方法，参数是$instance5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

// 构建测试用的instance对象 - 测试步骤1
$instance1 = new stdclass();
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace';
$instance1->k8name = 'test-app';
$instance1->channel = 'stable';

// 构建测试用的instance对象 - 测试步骤2
$instance2 = new stdclass();
$instance2->spaceData = new stdclass();
$instance2->spaceData->k8space = 'default';
$instance2->k8name = 'app-instance';
$instance2->channel = '';

// 构建测试用的instance对象 - 测试步骤3
$instance3 = new stdclass();
$instance3->spaceData = new stdclass();
$instance3->spaceData->k8space = 'prod-namespace';
$instance3->k8name = 'prod-app';
$instance3->channel = 'dev';

// 构建测试用的instance对象 - 测试步骤4
$instance4 = new stdclass();
$instance4->spaceData = new stdclass();
$instance4->spaceData->k8space = 'backup-namespace';
$instance4->k8name = 'backup-app';
$instance4->channel = 'test';

// 构建测试用的instance对象 - 测试步骤5
$instance5 = new stdclass();
$instance5->spaceData = new stdclass();
$instance5->spaceData->k8space = 'production';
$instance5->k8name = 'production-app';
$instance5->channel = 'production';

r(is_object($cneTest->getBackupListTest($instance1))) && p() && e('1');
r(is_object($cneTest->getBackupListTest($instance2))) && p() && e('1');
r(is_object($cneTest->getBackupListTest($instance3))) && p() && e('1');
r(is_object($cneTest->getBackupListTest($instance4))) && p() && e('1');
r(is_object($cneTest->getBackupListTest($instance5))) && p() && e('1');
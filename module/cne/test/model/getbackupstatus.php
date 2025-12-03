#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupStatus();
timeout=0
cid=15615

- 执行cneTest模块的getBackupStatusTest方法，参数是$instance1, 'backup-20240101'  @1
- 执行cneTest模块的getBackupStatusTest方法，参数是$instance2, 'backup-20240102'  @1
- 执行cneTest模块的getBackupStatusTest方法，参数是$instance3, 'backup-20240103'  @1
- 执行cneTest模块的getBackupStatusTest方法，参数是$instance4, 'backup-manual-001'  @1
- 执行cneTest模块的getBackupStatusTest方法，参数是$instance5, 'backup-system-001'  @1

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
$instance2->channel = 'stable';

// 构建测试用的instance对象 - 测试步骤3
$instance3 = new stdclass();
$instance3->spaceData = new stdclass();
$instance3->spaceData->k8space = 'prod-namespace';
$instance3->k8name = 'prod-app';
$instance3->channel = '';

// 构建测试用的instance对象 - 测试步骤4
$instance4 = new stdclass();
$instance4->spaceData = new stdclass();
$instance4->spaceData->k8space = 'backup-namespace';
$instance4->k8name = 'backup-app';
$instance4->channel = 'dev';

// 构建测试用的instance对象 - 测试步骤5
$instance5 = new stdclass();
$instance5->spaceData = new stdclass();
$instance5->spaceData->k8space = 'production';
$instance5->k8name = 'production-app';
$instance5->channel = 'production';

r(is_object($cneTest->getBackupStatusTest($instance1, 'backup-20240101'))) && p() && e('1');
r(is_object($cneTest->getBackupStatusTest($instance2, 'backup-20240102'))) && p() && e('1');
r(is_object($cneTest->getBackupStatusTest($instance3, 'backup-20240103'))) && p() && e('1');
r(is_object($cneTest->getBackupStatusTest($instance4, 'backup-manual-001'))) && p() && e('1');
r(is_object($cneTest->getBackupStatusTest($instance5, 'backup-system-001'))) && p() && e('1');
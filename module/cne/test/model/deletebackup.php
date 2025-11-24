#!/usr/bin/env php
<?php

/**

title=测试 cneModel::deleteBackup();
timeout=0
cid=0

- 执行cneTest模块的deleteBackupTest方法，参数是$instance1, 'backup-20240101-120000'  @1
- 执行cneTest模块的deleteBackupTest方法，参数是$instance2, 'backup-20240102-120000'  @1
- 执行cneTest模块的deleteBackupTest方法，参数是$instance3, 'backup-20240103-120000'  @1
- 执行cneTest模块的deleteBackupTest方法，参数是$instance4, 'backup-20240104-120000'  @1
- 执行cneTest模块的deleteBackupTest方法，参数是$instance5, 'backup-test-special'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

// 构建测试用的instance对象 - 正常实例
$instance1 = new stdclass();
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace';
$instance1->k8name = 'test-app';
$instance1->channel = 'stable';

// 构建测试用的instance对象 - 空channel
$instance2 = new stdclass();
$instance2->spaceData = new stdclass();
$instance2->spaceData->k8space = 'default';
$instance2->k8name = 'app-instance';
$instance2->channel = '';

// 构建测试用的instance对象 - 生产环境
$instance3 = new stdclass();
$instance3->spaceData = new stdclass();
$instance3->spaceData->k8space = 'prod-namespace';
$instance3->k8name = 'prod-app';
$instance3->channel = 'stable';

// 构建测试用的instance对象 - 不同k8name
$instance4 = new stdclass();
$instance4->spaceData = new stdclass();
$instance4->spaceData->k8space = 'test-namespace';
$instance4->k8name = 'another-app';
$instance4->channel = 'dev';

// 构建测试用的instance对象 - 用于特殊字符测试
$instance5 = new stdclass();
$instance5->spaceData = new stdclass();
$instance5->spaceData->k8space = 'test-namespace';
$instance5->k8name = 'special-app';
$instance5->channel = 'stable';

r(is_object($cneTest->deleteBackupTest($instance1, 'backup-20240101-120000'))) && p() && e('1');
r(is_object($cneTest->deleteBackupTest($instance2, 'backup-20240102-120000'))) && p() && e('1');
r(is_object($cneTest->deleteBackupTest($instance3, 'backup-20240103-120000'))) && p() && e('1');
r(is_object($cneTest->deleteBackupTest($instance4, 'backup-20240104-120000'))) && p() && e('1');
r(is_object($cneTest->deleteBackupTest($instance5, 'backup-test-special'))) && p() && e('1');
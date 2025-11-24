#!/usr/bin/env php
<?php

/**

title=测试 cneModel::backup();
timeout=0
cid=0

- 执行cneTest模块的backupTest方法，参数是$instance1, 'testuser', 'manual'  @1
- 执行cneTest模块的backupTest方法，参数是$instance2, '', 'system'  @1
- 执行cneTest模块的backupTest方法，参数是$instance3, 'customuser', 'upgrade'  @1
- 执行cneTest模块的backupTest方法，参数是$instance1, null, 'downgrade'  @1
- 执行cneTest模块的backupTest方法，参数是$instance2, 'admin', ''  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

// 构建测试用的instance对象
$instance1 = new stdclass();
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace';
$instance1->k8name = 'test-app';
$instance1->channel = 'stable';

$instance2 = new stdclass();
$instance2->spaceData = new stdclass();
$instance2->spaceData->k8space = 'default';
$instance2->k8name = 'app-instance';
$instance2->channel = '';

$instance3 = new stdclass();
$instance3->spaceData = new stdclass();
$instance3->spaceData->k8space = 'prod-namespace';
$instance3->k8name = 'prod-app';
$instance3->channel = 'dev';

r(is_object($cneTest->backupTest($instance1, 'testuser', 'manual'))) && p() && e('1');
r(is_object($cneTest->backupTest($instance2, '', 'system'))) && p() && e('1');
r(is_object($cneTest->backupTest($instance3, 'customuser', 'upgrade'))) && p() && e('1');
r(is_object($cneTest->backupTest($instance1, null, 'downgrade'))) && p() && e('1');
r(is_object($cneTest->backupTest($instance2, 'admin', ''))) && p() && e('1');
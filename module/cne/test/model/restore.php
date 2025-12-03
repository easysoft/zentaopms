#!/usr/bin/env php
<?php

/**

title=测试 cneModel::restore();
timeout=0
cid=15627

- 执行cneTest模块的restoreTest方法，参数是$instance1, 'backup-001' 属性code @600
- 执行cneTest模块的restoreTest方法，参数是$instance1, '' 属性code @600
- 执行cneTest模块的restoreTest方法，参数是$instance1, 'backup-002', 'testuser' 属性code @600
- 执行cneTest模块的restoreTest方法，参数是$instance2, 'backup-003' 属性code @600
- 执行cneTest模块的restoreTest方法，参数是$instance1, 'backup-004', '' 属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

$instance1 = new stdclass();
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace';
$instance1->k8name = 'test-app';
$instance1->channel = '';

$instance2 = new stdclass();
$instance2->spaceData = new stdclass();
$instance2->spaceData->k8space = 'prod-namespace';
$instance2->k8name = 'prod-app';
$instance2->channel = 'stable';

r($cneTest->restoreTest($instance1, 'backup-001')) && p('code') && e('600');
r($cneTest->restoreTest($instance1, '')) && p('code') && e('600');
r($cneTest->restoreTest($instance1, 'backup-002', 'testuser')) && p('code') && e('600');
r($cneTest->restoreTest($instance2, 'backup-003')) && p('code') && e('600');
r($cneTest->restoreTest($instance1, 'backup-004', '')) && p('code') && e('600');
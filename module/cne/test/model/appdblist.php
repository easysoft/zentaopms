#!/usr/bin/env php
<?php

/**

title=测试 cneModel::appDBList();
timeout=0
cid=15602

- 执行cneTest模块的appDBListByInstanceTest方法，参数是$instance1  @0
- 执行cneTest模块的appDBListByInstanceTest方法，参数是$instance2  @0
- 执行cneTest模块的appDBListByInstanceTest方法，参数是$instance3  @0
- 执行cneTest模块的appDBListByInstanceTest方法，参数是$instance4  @0
- 执行cneTest模块的appDBListByInstanceTest方法，参数是$instance5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

// 测试步骤1：创建有效实例对象
$instance1 = new stdClass();
$instance1->k8name = 'test-app1';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'test-namespace1';

// 测试步骤2：空实例对象
$instance2 = null;

// 测试步骤3：k8name为空的实例
$instance3 = new stdClass();
$instance3->k8name = '';
$instance3->spaceData = new stdClass();
$instance3->spaceData->k8space = 'test-namespace3';

// 测试步骤4：spaceData为空的实例
$instance4 = new stdClass();
$instance4->k8name = 'test-app4';
$instance4->spaceData = null;

// 测试步骤5：spaceData->k8space为空的实例
$instance5 = new stdClass();
$instance5->k8name = 'test-app5';
$instance5->spaceData = new stdClass();
$instance5->spaceData->k8space = '';

r($cneTest->appDBListByInstanceTest($instance1)) && p() && e('0');
r($cneTest->appDBListByInstanceTest($instance2)) && p() && e('0');
r($cneTest->appDBListByInstanceTest($instance3)) && p() && e('0');
r($cneTest->appDBListByInstanceTest($instance4)) && p() && e('0');
r($cneTest->appDBListByInstanceTest($instance5)) && p() && e('0');
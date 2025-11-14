#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::batchFresh();
timeout=0
cid=16781

- 步骤1：空实例数组 @0
- 步骤2：单个实例ID第0条的id属性 @1
- 步骤3：多个实例第二个ID第1条的id属性 @2
- 步骤4：状态获取第0条的status属性 @creating
- 步骤5：异常实例ID第0条的id属性 @999

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备
zenData('instance')->gen(0);
zenData('space')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$instanceTest = new instanceTest();

// 5. 创建测试实例对象
$instance1 = new stdClass();
$instance1->id = 1;
$instance1->status = 'running';
$instance1->k8name = 'test-k8name-1';
$instance1->version = '1.0.0';
$instance1->chart = 'zentao';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'test-space-1';

$instance2 = new stdClass();
$instance2->id = 2;
$instance2->status = 'stopped';
$instance2->k8name = 'test-k8name-2';
$instance2->version = '1.0.0';
$instance2->chart = 'gitlab';
$instance2->spaceData = new stdClass();
$instance2->spaceData->k8space = 'test-space-1';

$instance3 = new stdClass();
$instance3->id = 3;
$instance3->status = 'creating';
$instance3->k8name = 'test-k8name-3';
$instance3->version = '1.1.0';
$instance3->chart = 'jenkins';
$instance3->spaceData = new stdClass();
$instance3->spaceData->k8space = 'test-space-2';

$invalidInstance = new stdClass();
$invalidInstance->id = 999;
$invalidInstance->status = 'unknown';
$invalidInstance->k8name = 'invalid-k8name';
$invalidInstance->version = '1.0.0';
$invalidInstance->chart = 'unknown';
$invalidInstance->spaceData = new stdClass();
$invalidInstance->spaceData->k8space = 'invalid-space';

// 6. 测试步骤
r($instanceTest->batchFreshTest(array())) && p() && e('0'); // 步骤1：空实例数组
r($instanceTest->batchFreshTest(array($instance1))) && p('0:id') && e('1'); // 步骤2：单个实例ID
r($instanceTest->batchFreshTest(array($instance1, $instance2))) && p('1:id') && e('2'); // 步骤3：多个实例第二个ID
r($instanceTest->batchFreshTest(array($instance3))) && p('0:status') && e('creating'); // 步骤4：状态获取
r($instanceTest->batchFreshTest(array($invalidInstance))) && p('0:id') && e('999'); // 步骤5：异常实例ID
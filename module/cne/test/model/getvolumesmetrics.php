#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getVolumesMetrics();
timeout=0
cid=15624

- 步骤1：传入null实例获取卷指标
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 步骤2：传入实例ID=1但没有卷数据的情况
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 步骤3：传入实例ID=2有卷数据的情况
 - 属性limit @10737418240
 - 属性usage @5368709120
 - 属性rate @50
- 步骤4：传入实例ID=3满容量的情况属性rate @100
- 步骤5：传入不存在的实例ID=999
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('instance')->gen(0);
zenData('space')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getVolumesMetricsTest(null)) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤1：传入null实例获取卷指标
r($cneTest->getVolumesMetricsTest((object)array('id' => 1, 'k8name' => 'test-app-1', 'spaceData' => (object)array('k8space' => 'test-ns')))) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤2：传入实例ID=1但没有卷数据的情况
r($cneTest->getVolumesMetricsTest((object)array('id' => 2, 'k8name' => 'test-app-2', 'spaceData' => (object)array('k8space' => 'test-ns')))) && p('limit,usage,rate') && e('10737418240,5368709120,50'); // 步骤3：传入实例ID=2有卷数据的情况
r($cneTest->getVolumesMetricsTest((object)array('id' => 3, 'k8name' => 'test-app-3', 'spaceData' => (object)array('k8space' => 'test-ns')))) && p('rate') && e('100'); // 步骤4：传入实例ID=3满容量的情况
r($cneTest->getVolumesMetricsTest((object)array('id' => 999, 'k8name' => 'test-app-999', 'spaceData' => (object)array('k8space' => 'test-ns')))) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤5：传入不存在的实例ID=999
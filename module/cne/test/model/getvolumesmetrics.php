#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getVolumesMetrics();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0
- 步骤2：有效实例
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0
- 步骤3：另一个实例
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0
- 步骤4：无效ID
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0
- 步骤5：不存在的ID
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('instance')->loadYaml('instance', false, 2)->gen(2);
zendata('space')->loadYaml('space', false, 1)->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getVolumesMetricsTest(2)) && p('limit,usage,rate') && e('0,0,0'); // 步骤1：正常情况
r($cneTest->getVolumesMetricsTest(1)) && p('limit,usage,rate') && e('0,0,0'); // 步骤2：有效实例
r($cneTest->getVolumesMetricsTest(3)) && p('limit,usage,rate') && e('0,0,0'); // 步骤3：另一个实例
r($cneTest->getVolumesMetricsTest(0)) && p('limit,usage,rate') && e('0,0,0'); // 步骤4：无效ID
r($cneTest->getVolumesMetricsTest(999)) && p('limit,usage,rate') && e('0,0,0'); // 步骤5：不存在的ID
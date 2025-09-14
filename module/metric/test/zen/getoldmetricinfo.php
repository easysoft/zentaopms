#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getOldMetricInfo();
timeout=0
cid=0

- 步骤1：测试正常ID返回scope名称属性scope @度量范围
属性name @度量范围
- 步骤2：测试正常ID返回object名称属性object @度量对象
属性name @度量对象
- 步骤3：测试正常ID返回purpose名称属性purpose @度量目的
属性name @度量目的
- 步骤4：测试正常ID返回code名称属性code @度量代号
属性name @度量代号
- 步骤5：测试正常ID返回unit名称属性unit @度量单位
属性name @度量单位

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('basicmeas')->loadYaml('meas', true)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getOldMetricInfoZenTest(1)) && p('scope,name') && e('度量范围'); // 步骤1：测试正常ID返回scope名称
r($metricTest->getOldMetricInfoZenTest(2)) && p('object,name') && e('度量对象'); // 步骤2：测试正常ID返回object名称
r($metricTest->getOldMetricInfoZenTest(3)) && p('purpose,name') && e('度量目的'); // 步骤3：测试正常ID返回purpose名称
r($metricTest->getOldMetricInfoZenTest(4)) && p('code,name') && e('度量代号'); // 步骤4：测试正常ID返回code名称
r($metricTest->getOldMetricInfoZenTest(5)) && p('unit,name') && e('度量单位'); // 步骤5：测试正常ID返回unit名称
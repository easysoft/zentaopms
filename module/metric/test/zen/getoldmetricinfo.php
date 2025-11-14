#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getOldMetricInfo();
timeout=0
cid=17191

- 步骤1：测试第1个旧版度量项基本信息字段数量 @9
- 步骤2：测试第1个旧版度量项的scope字段name值第scope条的name属性 @度量范围
- 步骤3：测试第1个旧版度量项的purpose字段name值第purpose条的name属性 @度量目的
- 步骤4：测试第1个旧版度量项的code字段name值第code条的name属性 @度量项代号
- 步骤5：测试第1个旧版度量项的unit字段name值第unit条的name属性 @度量单位
- 步骤6：测试第1个旧版度量项的collectType字段name值第collectType条的name属性 @收集方式
- 步骤7：测试第1个旧版度量项的definition字段name值第definition条的name属性 @度量定义
- 步骤8：测试第1个旧版度量项的sql字段name值第sql条的name属性 @SQL语句

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('basicmeas')->loadYaml('meas', true)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($metricTest->getOldMetricInfoZenTest(1))) && p() && e('9'); // 步骤1：测试第1个旧版度量项基本信息字段数量
r($metricTest->getOldMetricInfoZenTest(1)) && p('scope:name') && e('度量范围'); // 步骤2：测试第1个旧版度量项的scope字段name值
r($metricTest->getOldMetricInfoZenTest(1)) && p('purpose:name') && e('度量目的'); // 步骤3：测试第1个旧版度量项的purpose字段name值
r($metricTest->getOldMetricInfoZenTest(1)) && p('code:name') && e('度量项代号'); // 步骤4：测试第1个旧版度量项的code字段name值
r($metricTest->getOldMetricInfoZenTest(1)) && p('unit:name') && e('度量单位'); // 步骤5：测试第1个旧版度量项的unit字段name值
r($metricTest->getOldMetricInfoZenTest(1)) && p('collectType:name') && e('收集方式'); // 步骤6：测试第1个旧版度量项的collectType字段name值
r($metricTest->getOldMetricInfoZenTest(1)) && p('definition:name') && e('度量定义'); // 步骤7：测试第1个旧版度量项的definition字段name值
r($metricTest->getOldMetricInfoZenTest(1)) && p('sql:name') && e('SQL语句'); // 步骤8：测试第1个旧版度量项的sql字段name值
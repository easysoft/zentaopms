#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getDefaultMethodAndParams();
timeout=0
cid=0

- 步骤1：正常情况，返回内置方法 @bugCreate
- 步骤2：边界值，分组不存在
 -  @
 - 属性1 @
- 步骤3：异常输入，无效维度ID
 -  @
 - 属性1 @
- 步骤4：权限验证，非第一维度无内置透视表
 -  @
 - 属性1 @
- 步骤5：业务规则，grade不为1的分组
 -  @
 - 属性1 @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$moduleTable = zenData('module');
$moduleTable->loadYaml('module_getdefaultmethodandparams', false, 2)->gen(10);

$dimensionTable = zenData('dimension');
$dimensionTable->loadYaml('dimension_getdefaultmethodandparams', false, 2)->gen(5);

$pivotTable = zenData('pivot');
$pivotTable->loadYaml('pivot_getdefaultmethodandparams', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getDefaultMethodAndParamsTest(1, 1)) && p('0') && e('bugCreate'); // 步骤1：正常情况，返回内置方法
r($pivotTest->getDefaultMethodAndParamsTest(1, 999)) && p('0,1') && e(','); // 步骤2：边界值，分组不存在
r($pivotTest->getDefaultMethodAndParamsTest(0, 1)) && p('0,1') && e(','); // 步骤3：异常输入，无效维度ID
r($pivotTest->getDefaultMethodAndParamsTest(2, 1)) && p('0,1') && e(','); // 步骤4：权限验证，非第一维度无内置透视表
r($pivotTest->getDefaultMethodAndParamsTest(1, 2)) && p('0,1') && e(','); // 步骤5：业务规则，grade不为1的分组
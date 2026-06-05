#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processStageForExport();
timeout=0
cid=19104

- 步骤1：单一阶段unittest处理属性stage @单元测试环节
- 步骤2：多个阶段unittest,feature处理属性stage @单元测试环节
- 步骤3：未定义阶段unknown处理属性stage @功能测试环节
- 步骤4：空阶段处理属性stage @unknown
- 步骤5：混合阶段feature,unknown,system处理属性stage @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processStageForExportTest((object)array('stage' => 'unittest'))) && p('stage') && e('单元测试环节'); // 步骤1：单一阶段unittest处理
r($testcaseTest->processStageForExportTest((object)array('stage' => 'unittest,feature'))) && p('stage') && e('单元测试环节'); // 步骤2：多个阶段unittest,feature处理
r($testcaseTest->processStageForExportTest((object)array('stage' => 'unknown'))) && p('stage') && e('功能测试环节'); // 步骤3：未定义阶段unknown处理
r($testcaseTest->processStageForExportTest((object)array('stage' => ''))) && p('stage') && e('unknown'); // 步骤4：空阶段处理
r($testcaseTest->processStageForExportTest((object)array('stage' => 'feature,unknown,system'))) && p('stage') && e('~~'); // 步骤5：混合阶段feature,unknown,system处理
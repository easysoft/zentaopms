#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getFieldsForExportTemplate();
timeout=0
cid=19091

- 步骤1：normal类型包含title字段属性title @用例名称
- 步骤2：branch类型包含branch字段属性branch @分支
- 步骤3：platform类型包含branch字段属性branch @平台
- 步骤4：验证包含stepDesc字段属性stepDesc @步骤
- 步骤5：验证包含stepExpected字段属性stepExpect @预期

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->getFieldsForExportTemplateTest('normal')) && p('title') && e('用例名称'); // 步骤1：normal类型包含title字段
r($testcaseTest->getFieldsForExportTemplateTest('branch')) && p('branch') && e('分支'); // 步骤2：branch类型包含branch字段
r($testcaseTest->getFieldsForExportTemplateTest('platform')) && p('branch') && e('平台'); // 步骤3：platform类型包含branch字段
r($testcaseTest->getFieldsForExportTemplateTest('normal')) && p('stepDesc') && e('步骤'); // 步骤4：验证包含stepDesc字段
r($testcaseTest->getFieldsForExportTemplateTest('normal')) && p('stepExpect') && e('预期'); // 步骤5：验证包含stepExpected字段
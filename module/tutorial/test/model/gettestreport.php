#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTestReport();
timeout=0
cid=19488

- 步骤1：正常获取测试报告对象的核心字段
 - 属性id @1
 - 属性title @Test testreport
 - 属性project @2
 - 属性product @1
 - 属性execution @3
- 步骤2：验证测试报告ID字段属性id @1
- 步骤3：验证测试报告标题字段属性title @Test testreport
- 步骤4：验证测试报告项目关联字段属性project @2
- 步骤5：验证测试报告产品关联字段属性product @1
- 步骤6：验证测试报告执行关联字段属性execution @3
- 步骤7：验证测试报告对象类型字段属性objectType @execution

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getTestReportTest()) && p('id,title,project,product,execution') && e('1,Test testreport,2,1,3'); // 步骤1：正常获取测试报告对象的核心字段
r($tutorialTest->getTestReportTest()) && p('id') && e('1');                                                        // 步骤2：验证测试报告ID字段
r($tutorialTest->getTestReportTest()) && p('title') && e('Test testreport');                                       // 步骤3：验证测试报告标题字段
r($tutorialTest->getTestReportTest()) && p('project') && e('2');                                                   // 步骤4：验证测试报告项目关联字段
r($tutorialTest->getTestReportTest()) && p('product') && e('1');                                                   // 步骤5：验证测试报告产品关联字段
r($tutorialTest->getTestReportTest()) && p('execution') && e('3');                                                 // 步骤6：验证测试报告执行关联字段
r($tutorialTest->getTestReportTest()) && p('objectType') && e('execution');                                        // 步骤7：验证测试报告对象类型字段
#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getResult();
timeout=0
cid=19465

- 步骤1：正常获取结果对象的核心字段
 - 属性id @1
 - 属性case @1
 - 属性caseResult @fail
 - 属性lastRunner @admin
 - 属性version @1
- 步骤2：验证ID字段属性id @1
- 步骤3：验证用例结果字段属性caseResult @fail
- 步骤4：验证最后执行者字段属性lastRunner @admin
- 步骤5：验证版本字段属性version @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getResultTest()) && p('id,case,caseResult,lastRunner,version') && e('1,1,fail,admin,1'); // 步骤1：正常获取结果对象的核心字段
r($tutorialTest->getResultTest()) && p('id') && e('1');                                                       // 步骤2：验证ID字段
r($tutorialTest->getResultTest()) && p('caseResult') && e('fail');                                           // 步骤3：验证用例结果字段
r($tutorialTest->getResultTest()) && p('lastRunner') && e('admin');                                          // 步骤4：验证最后执行者字段
r($tutorialTest->getResultTest()) && p('version') && e('1');                                                 // 步骤5：验证版本字段
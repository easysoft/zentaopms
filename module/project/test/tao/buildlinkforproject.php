#!/usr/bin/env php
<?php

/**

title=测试 projectTao::buildLinkForProject();
timeout=0
cid=0

- 步骤1：测试execution方法错误 @Undefined variable $module
- 步骤2：测试managePriv方法错误 @Undefined variable $module
- 步骤3：测试showerrornone方法正常 @m=projectstory&f=story&projectID=%s
- 步骤4：测试预定义方法bug错误 @Undefined variable $module
- 步骤5：测试预定义方法view错误 @Undefined variable $module
- 步骤6：测试预定义方法testcase错误 @Undefined variable $module
- 步骤7：测试未定义方法错误 @projectTao::buildLinkForProject(): Return value must be of type string, none returned

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 4. 强制要求：必须包含至少7个测试步骤
r($projectTest->buildLinkForProjectTest('execution')) && p() && e('Undefined variable $module'); // 步骤1：测试execution方法错误
r($projectTest->buildLinkForProjectTest('managePriv')) && p() && e('Undefined variable $module'); // 步骤2：测试managePriv方法错误
r($projectTest->buildLinkForProjectTest('showerrornone')) && p() && e('m=projectstory&f=story&projectID=%s'); // 步骤3：测试showerrornone方法正常
r($projectTest->buildLinkForProjectTest('bug')) && p() && e('Undefined variable $module'); // 步骤4：测试预定义方法bug错误
r($projectTest->buildLinkForProjectTest('view')) && p() && e('Undefined variable $module'); // 步骤5：测试预定义方法view错误
r($projectTest->buildLinkForProjectTest('testcase')) && p() && e('Undefined variable $module'); // 步骤6：测试预定义方法testcase错误
r($projectTest->buildLinkForProjectTest('invalidmethod')) && p() && e('projectTao::buildLinkForProject(): Return value must be of type string, none returned'); // 步骤7：测试未定义方法错误
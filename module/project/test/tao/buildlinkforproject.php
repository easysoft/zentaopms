#!/usr/bin/env php
<?php

/**

title=- 步骤1：测试execution方法（预期错误） @baseHelper::createLink(): Argument
timeout=0
cid=1

- 步骤1：测试execution方法（预期错误） @Undefined variable $module:
- 步骤2：测试managePriv方法（预期错误） @Undefined variable $module:
- 步骤3：测试showerrornone方法（正常） @/repo/zentaopms/module/project/test/tao/buildlinkforproject.php?m=projectstory&f=story&projectID=%s
- 步骤4：测试预定义方法bug（预期错误） @Undefined variable $module:
- 步骤5：测试预定义方法view（预期错误） @Undefined variable $module:
- 步骤6：测试预定义方法testcase（预期错误） @Undefined variable $module:
- 步骤7：测试未定义方法 @projectTao::buildLinkForProject(): Return value must be of type string, none returned

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($projectTest->buildLinkForProjectTest('execution')) && p() && e('Undefined variable $module:'); // 步骤1：测试execution方法（预期错误）
r($projectTest->buildLinkForProjectTest('managePriv')) && p() && e('Undefined variable $module:'); // 步骤2：测试managePriv方法（预期错误）
r($projectTest->buildLinkForProjectTest('showerrornone')) && p() && e('/repo/zentaopms/module/project/test/tao/buildlinkforproject.php?m=projectstory&f=story&projectID=%s'); // 步骤3：测试showerrornone方法（正常）
r($projectTest->buildLinkForProjectTest('bug')) && p() && e('Undefined variable $module:'); // 步骤4：测试预定义方法bug（预期错误）
r($projectTest->buildLinkForProjectTest('view')) && p() && e('Undefined variable $module:'); // 步骤5：测试预定义方法view（预期错误）
r($projectTest->buildLinkForProjectTest('testcase')) && p() && e('Undefined variable $module:'); // 步骤6：测试预定义方法testcase（预期错误）
r($projectTest->buildLinkForProjectTest('invalidmethod')) && p() && e('projectTao::buildLinkForProject(): Return value must be of type string, none returned'); // 步骤7：测试未定义方法
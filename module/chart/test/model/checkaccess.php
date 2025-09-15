#!/usr/bin/env php
<?php

/**

title=测试 chartModel::checkAccess();
timeout=0
cid=0

- 步骤1：管理员访问图表，应该有权限属性hasAccess @1
- 步骤2：用户访问自己创建的图表，应该有权限属性hasAccess @1
- 步骤3：用户访问开放图表，应该有权限属性hasAccess @1
- 步骤4：用户访问白名单中的私有图表，应该有权限属性hasAccess @1
- 步骤5：用户无权限访问私有图表，应该被拒绝属性hasAccess @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');
$chartTest = new chartTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($chartTest->checkAccessTest(1, 'preview', 'adminAccess')) && p('hasAccess') && e('1'); // 步骤1：管理员访问图表，应该有权限
r($chartTest->checkAccessTest(2, 'edit', 'userOwnChart')) && p('hasAccess') && e('1'); // 步骤2：用户访问自己创建的图表，应该有权限
r($chartTest->checkAccessTest(3, 'preview', 'userOpenChart')) && p('hasAccess') && e('1'); // 步骤3：用户访问开放图表，应该有权限
r($chartTest->checkAccessTest(4, 'preview', 'userWhitelistChart')) && p('hasAccess') && e('1'); // 步骤4：用户访问白名单中的私有图表，应该有权限
r($chartTest->checkAccessTest(5, 'preview', 'userNoAccess')) && p('hasAccess') && e('0'); // 步骤5：用户无权限访问私有图表，应该被拒绝